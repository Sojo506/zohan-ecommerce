<?php

// Coordina el paso de "pago externo capturado" a registros internos: venta, inventario, factura y correo.
class PaymentModel
{
    private PDO $db;
    private CartModel $cartModel;

    public function __construct(?PDO $db = null, ?CartModel $cartModel = null)
    {
        $this->db = $db ?? Database::connection();
        $this->cartModel = $cartModel ?? new CartModel();
    }

    public function getPayPalAccessToken(): ?string
    {
        // El token es de uso inmediato para el capture; no se guarda ni se reutiliza entre requests.
        $clientId = Env::get('PAYPAL_CLIENT_ID');
        $secret = Env::get('PAYPAL_SECRET');
        $url = Env::get('PAYPAL_URL') . '/v1/oauth2/token';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $secret);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log('Error cURL Token: ' . curl_error($ch));
        }
        curl_close($ch);

        $json = json_decode((string)$response, true);

        return $json['access_token'] ?? null;
    }

    public function capturePayPalOrder(string $orderId, string $accessToken): array
    {
        // Primero se confirma la orden en PayPal; la BD local solo se toca cuando esa captura ya fue aceptada.
        $url = Env::get('PAYPAL_URL') . "/v2/checkout/orders/{$orderId}/capture";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer {$accessToken}"
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log('Error cURL Capture: ' . curl_error($ch));
        }
        curl_close($ch);

        return json_decode((string)$response, true) ?? [];
    }

    public function registerCapturedPayment(string $paypalOrderId, string $paypalCaptureId, $totalUSD, ?int $couponId = null): array
    {
        $idCuenta = (int)($_SESSION['user']['id_cuenta'] ?? 0);
        $estadoCompletado = 4;
        $cart = $this->cartModel->syncSessionCart();

        if ($idCuenta <= 0) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ];
        }

        if (empty($cart)) {
            return [
                'success' => false,
                'message' => 'No hay productos en el carrito para registrar la compra.'
            ];
        }

        try {
            // Venta, líneas, inventario, factura y pago deben confirmarse juntos para no dejar la compra partida.
            $this->db->beginTransaction();

            $stmt = $this->db->prepare('INSERT INTO VENTA_TB (ID_CUENTA, FECHA_VENTA, ID_ESTADO) VALUES (?, NOW(), ?)');
            $stmt->execute([$idCuenta, $estadoCompletado]);
            $saleId = (int)$this->db->lastInsertId();

            $items = [];

            // Cada producto se lee con bloqueo para evitar que dos compras descuenten el mismo stock a la vez.
            foreach ($cart as $productId => $quantity) {
                $product = $this->fetchProductForSale((int)$productId);
                if (!$product) {
                    throw new RuntimeException('Producto no encontrado en la compra.');
                }

                $stockActual = isset($product['STOCK']) ? (int)$product['STOCK'] : 0;
                if ($stockActual < $quantity) {
                    throw new RuntimeException('Stock insuficiente para completar la compra.');
                }

                $price = (float)$product['PRECIO'];
                $this->registerSaleLine($saleId, (int)$productId, (int)$quantity, $price);
                $this->updateInventory((int)$productId, $stockActual - (int)$quantity);
                $this->registerInventoryMovement((int)$productId, (int)$quantity, $saleId);

                $items[] = [
                    'name' => $product['NOMBRE'],
                    'quantity' => (int)$quantity,
                    'subtotal' => $price * (int)$quantity,
                ];
            }

            $totalUSD = round((float)$totalUSD, 2);
            $subtotalUSDFactura = round($totalUSD / 1.13, 2);
            $tax = round($totalUSD - $subtotalUSDFactura, 2);

            $invoiceId = $this->registerInvoice($saleId, $tax, $subtotalUSDFactura, $totalUSD, $estadoCompletado);
            $this->registerPayPalPayment($invoiceId, $paypalOrderId, $paypalCaptureId, $totalUSD, $estadoCompletado);

            $this->db->commit();

            // El correo se envía después del commit: si falla, la compra sigue siendo válida y ya quedó registrada.
            $emailSent = $this->sendPurchaseEmail([
                'customer_name' => trim((string)(($_SESSION['user']['nombre'] ?? '') . ' ' . ($_SESSION['user']['apellido'] ?? ''))),
                'sale_id' => $saleId,
                'invoice_id' => $invoiceId,
                'paypal_order_id' => $paypalOrderId,
                'subtotal' => $subtotalUSDFactura,
                'tax' => $tax,
                'total' => $totalUSD,
                'items' => $items,
            ]);

            return [
                'success' => true,
                'email_sent' => $emailSent,
            ];
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            error_log('Error Base Datos: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'El pago fue capturado, pero no se pudo registrar localmente: ' . $e->getMessage()
            ];
        }
    }

    public function clearCart(): void
    {
        $this->cartModel->clear();
    }

    private function fetchProductForSale(int $productId)
    {
        // FOR UPDATE mantiene bloqueada la fila consultada hasta el commit/rollback de la transacción actual.
        $stmt = $this->db->prepare("
            SELECT P.NOMBRE, P.PRECIO, I.STOCK
            FROM PRODUCTO_TB P
            LEFT JOIN INVENTARIO_TB I ON I.ID_PRODUCTO = P.ID_PRODUCTO
            WHERE P.ID_PRODUCTO = ?
            FOR UPDATE
        ");
        $stmt->execute([$productId]);

        return $stmt->fetch();
    }

    private function registerSaleLine(int $saleId, int $productId, int $quantity, float $price): void
    {
        $stmt = $this->db->prepare('INSERT INTO VENTA_PRODUCTO_TB (ID_VENTA, ID_PRODUCTO, CANTIDAD, PRECIO) VALUES (?, ?, ?, ?)');
        $stmt->execute([$saleId, $productId, $quantity, $price]);
    }

    private function updateInventory(int $productId, int $newStock): void
    {
        $stmt = $this->db->prepare('UPDATE INVENTARIO_TB SET STOCK = ? WHERE ID_PRODUCTO = ?');
        $stmt->execute([$newStock, $productId]);
    }

    private function registerInventoryMovement(int $productId, int $quantity, int $saleId): void
    {
        // Deja trazabilidad del rebajo de stock para auditoría y reportes de inventario.
        $stmt = $this->db->prepare('
            INSERT INTO MOVIMIENTO_INVENTARIO_TB (ID_PRODUCTO, ID_TIPO_MOVIMIENTO, CANTIDAD, MOTIVO, ID_ESTADO)
            VALUES (?, ?, ?, ?, 1)
        ');
        $stmt->execute([
            $productId,
            $this->resolveSaleMovementTypeId(),
            $quantity,
            'Venta #' . $saleId . ' pagada con PayPal'
        ]);
    }

    private function registerInvoice(int $saleId, float $tax, float $subtotal, float $total, int $statusId): int
    {
        $stmt = $this->db->prepare('INSERT INTO FACTURA_TB (ID_VENTA, IMPUESTO, SUBTOTAL, TOTAL, ID_ESTADO) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$saleId, $tax, $subtotal, $total, $statusId]);

        return (int)$this->db->lastInsertId();
    }

    private function registerPayPalPayment(int $invoiceId, string $paypalOrderId, string $paypalCaptureId, float $total, int $statusId): void
    {
        $stmt = $this->db->prepare('INSERT INTO PAGO_PAYPAL_TB (ID_FACTURA, PAYPAL_ORDER_ID, PAYPAL_CAPTURE_ID, TOTAL, ID_MONEDA, ID_ESTADO) VALUES (?, ?, ?, ?, 1, ?)');
        $stmt->execute([$invoiceId, $paypalOrderId, $paypalCaptureId, $total, $statusId]);
    }

    private function resolveSaleMovementTypeId(): int
    {
        static $resolvedId = null;

        if ($resolvedId !== null) {
            return $resolvedId;
        }

        $stmt = $this->db->query("
            SELECT ID_TIPO_MOVIMIENTO, LOWER(NOMBRE) AS NOMBRE
            FROM TIPO_MOVIMIENTO_TB
            WHERE ID_ESTADO = 1
            ORDER BY ID_TIPO_MOVIMIENTO ASC
        ");

        // El catálogo puede variar según el ambiente, así que primero busca nombres "equivalentes" a una salida por venta.
        $types = $stmt->fetchAll();
        foreach ($types as $type) {
            $name = trim((string)$type['NOMBRE']);
            if (in_array($name, ['venta', 'salida', 'egreso'], true)) {
                $resolvedId = (int)$type['ID_TIPO_MOVIMIENTO'];
                return $resolvedId;
            }
        }

        foreach ($types as $type) {
            $id = (int)$type['ID_TIPO_MOVIMIENTO'];
            if ($id !== 1) {
                $resolvedId = $id;
                return $resolvedId;
            }
        }

        if (!empty($types)) {
            $resolvedId = (int)$types[0]['ID_TIPO_MOVIMIENTO'];
            return $resolvedId;
        }

        throw new RuntimeException('No existe un tipo de movimiento de inventario activo.');
    }

    private function sendPurchaseEmail(array $data): bool
    {
        // La ausencia de correo no invalida la compra; solo se le avisa al controller para ajustar el mensaje final.
        $email = $this->findCustomerEmail((string)($_SESSION['user']['identificacion'] ?? ''));
        if ($email === null) {
            return false;
        }

        return Mailer::purchaseConfirmation($email, $data);
    }

    private function findCustomerEmail(string $identificacion): ?string
    {
        if ($identificacion === '') {
            return null;
        }

        $stmt = $this->db->prepare('
            SELECT CORREO
            FROM CORREO_TB
            WHERE IDENTIFICACION = ?
            ORDER BY CORREO ASC
            LIMIT 1
        ');
        $stmt->execute([$identificacion]);
        $email = $stmt->fetchColumn();

        if ($email === false) {
            return null;
        }

        return (string)$email;
    }
}
