<?php

class PaymentController extends Controller
{
    public function capture()
    {
        // 1. Validar que la petición sea JSON y el usuario tenga sesión
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
            return;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $orderID = $data['orderID'] ?? null;

        if (!$orderID) {
            echo json_encode(['success' => false, 'message' => 'No se recibió el ID de la orden']);
            return;
        }

        // 2. Obtener Token de Acceso de PayPal
        $accessToken = $this->getPayPalAccessToken();
        if (!$accessToken) {
            echo json_encode(['success' => false, 'message' => 'Error de autenticación con PayPal']);
            return;
        }

        // 3. Capturar el pago en la API de PayPal
        $captureResult = $this->capturePayPalOrder($orderID, $accessToken);

        // 4. Validar si PayPal confirma que el cobro se completó
        if (isset($captureResult['status']) && $captureResult['status'] === 'COMPLETED') {

            $paypalCaptureId = $captureResult['purchase_units'][0]['payments']['captures'][0]['id'];
            $montoTotalUSD = $captureResult['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

            // Registrar en base de datos
            $guardadoExitoso = $this->guardarVentaEnBD($orderID, $paypalCaptureId, $montoTotalUSD);

            if ($guardadoExitoso) {
                // Vaciar el carrito tras el pago exitoso
                unset($_SESSION['cart']);
                echo json_encode(['success' => true, 'message' => 'Pago y registro completados']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Pago realizado, pero falló el registro en BD']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'El pago no pudo ser capturado']);
        }
    }

    // --- MÉTODOS PRIVADOS AUXILIARES ---

    private function getPayPalAccessToken()
    {
        $clientId = Env::get('PAYPAL_CLIENT_ID');
        $secret = Env::get('PAYPAL_SECRET');
        $url = Env::get('PAYPAL_URL') . '/v1/oauth2/token';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $secret);

        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        return $json['access_token'] ?? null;
    }

    private function capturePayPalOrder($orderID, $accessToken): mixed
    {
        $url = Env::get('PAYPAL_URL') . "/v2/checkout/orders/{$orderID}/capture";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$accessToken}"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private function guardarVentaEnBD($paypalOrderId, $paypalCaptureId, $totalUSD)
    {
        $pdo = Database::connection();
        $idCuenta = $_SESSION['user']['id_cuenta'];
        $estadoCompletado = 4; // Asumiendo que 4 es 'Completado' en tu ESTADO_TB

        try {
            $pdo->beginTransaction();

            // 1. Insertar en VENTA_TB
            $stmt = $pdo->prepare("INSERT INTO VENTA_TB (ID_CUENTA, FECHA_VENTA, ID_ESTADO) VALUES (?, NOW(), ?)");
            $stmt->execute([$idCuenta, $estadoCompletado]);
            $idVenta = $pdo->lastInsertId();

            // 2. Insertar productos del carrito en VENTA_PRODUCTO_TB
            $cart = $_SESSION['cart'] ?? [];
            $subtotalColones = 0;

            foreach ($cart as $item) {
                $idProducto = $item['producto']['ID_PRODUCTO'];
                $cantidad = $item['cantidad'];
                $precio = $item['producto']['PRECIO'];

                $stmtDetalle = $pdo->prepare("INSERT INTO VENTA_PRODUCTO_TB (ID_VENTA, ID_PRODUCTO, CANTIDAD, PRECIO) VALUES (?, ?, ?, ?)");
                $stmtDetalle->execute([$idVenta, $idProducto, $cantidad, $precio]);

                $subtotalColones += ($precio * $cantidad);
            }

            // 3. Insertar en FACTURA_TB (Calculamos impuesto base 13% IVA aprox)
            $impuesto = $subtotalColones * 0.13;
            $totalColones = $subtotalColones + $impuesto;

            $stmtFactura = $pdo->prepare("INSERT INTO FACTURA_TB (ID_VENTA, IMPUESTO, SUBTOTAL, TOTAL, ID_ESTADO) VALUES (?, ?, ?, ?, ?)");
            $stmtFactura->execute([$idVenta, $impuesto, $subtotalColones, $totalColones, $estadoCompletado]);
            $idFactura = $pdo->lastInsertId();

            // 4. Insertar en PAGO_PAYPAL_TB (ID_MONEDA 1 = USD)
            $stmtPago = $pdo->prepare("INSERT INTO PAGO_PAYPAL_TB (ID_FACTURA, PAYPAL_ORDER_ID, PAYPAL_CAPTURE_ID, TOTAL, ID_MONEDA, ID_ESTADO) VALUES (?, ?, ?, ?, 1, ?)");
            $stmtPago->execute([$idFactura, $paypalOrderId, $paypalCaptureId, $totalUSD, $estadoCompletado]);

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }
}