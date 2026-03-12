<?php

class PaymentController extends Controller
{
    public function capture()
    {
        // Limpiamos cualquier output previo para asegurar que el JSON sea puro
        if (ob_get_length()) ob_clean();
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

        // Obtener el token
        $accessToken = $this->getPayPalAccessToken();
        if (!$accessToken) {
            echo json_encode(['success' => false, 'message' => 'Fallo la autenticacion con PayPal. Revisa tus credenciales o conexión cURL.']);
            return;
        }

        // Capturar la orden
        $captureResult = $this->capturePayPalOrder($orderID, $accessToken);

        if (isset($captureResult['status']) && $captureResult['status'] === 'COMPLETED') {
            
            $paypalCaptureId = $captureResult['purchase_units'][0]['payments']['captures'][0]['id'];
            $montoTotalUSD = $captureResult['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            
            $guardadoExitoso = $this->guardarVentaEnBD($orderID, $paypalCaptureId, $montoTotalUSD);

            if ($guardadoExitoso) {
                unset($_SESSION['cart']); 
                echo json_encode(['success' => true, 'message' => 'Pago y registro completados']);
            } else {
                echo json_encode(['success' => false, 'message' => 'El pago se hizo, pero ocurrio un error en la base de datos local']);
            }
        } else {
            // Si PayPal rechaza o hay un error en la captura, enviamos el error real de PayPal
            $errorMsg = $captureResult['message'] ?? 'El pago no fue completado por PayPal';
            echo json_encode(['success' => false, 'message' => $errorMsg]);
        }
    }

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
        
        // --- EVITA ERRORES SSL EN LOCALHOST ---
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        
        $response = curl_exec($ch);
        
        if(curl_errno($ch)){
            // Si falla cURL, logueamos el error (opcional) pero no rompemos el script
            error_log('Error cURL Token: ' . curl_error($ch));
        }
        
        curl_close($ch);

        $json = json_decode($response, true);
        return $json['access_token'] ?? null;
    }

    private function capturePayPalOrder($orderID, $accessToken)
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
        
        // --- EVITA ERRORES SSL EN LOCALHOST ---
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        
        $response = curl_exec($ch);
        
        if(curl_errno($ch)){
            error_log('Error cURL Capture: ' . curl_error($ch));
        }
        
        curl_close($ch);

        return json_decode($response, true);
    }

    private function guardarVentaEnBD($paypalOrderId, $paypalCaptureId, $totalUSD)
    {
        $pdo = Database::connection();
        $idCuenta = $_SESSION['user']['id_cuenta'];
        $estadoCompletado = 4;

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO VENTA_TB (ID_CUENTA, FECHA_VENTA, ID_ESTADO) VALUES (?, NOW(), ?)");
            $stmt->execute([$idCuenta, $estadoCompletado]);
            $idVenta = $pdo->lastInsertId();

            $cart = $_SESSION['cart'] ?? [];
            $subtotalUSD_calculado = 0;

            foreach ($cart as $idProducto => $cantidad) {
                $stmtProd = $pdo->prepare("SELECT PRECIO FROM PRODUCTO_TB WHERE ID_PRODUCTO = ?");
                $stmtProd->execute([$idProducto]);
                $producto = $stmtProd->fetch();

                if ($producto) {
                    $precio = $producto['PRECIO'];
                    
                    $stmtDetalle = $pdo->prepare("INSERT INTO VENTA_PRODUCTO_TB (ID_VENTA, ID_PRODUCTO, CANTIDAD, PRECIO) VALUES (?, ?, ?, ?)");
                    $stmtDetalle->execute([$idVenta, $idProducto, $cantidad, $precio]);
                    
                    $subtotalUSD_calculado += ($precio * $cantidad);
                }
            }

            $impuesto = $subtotalUSD_calculado * 0.13;

            $stmtFactura = $pdo->prepare("INSERT INTO FACTURA_TB (ID_VENTA, IMPUESTO, SUBTOTAL, TOTAL, ID_ESTADO) VALUES (?, ?, ?, ?, ?)");
            $stmtFactura->execute([$idVenta, $impuesto, $subtotalUSD_calculado, $totalUSD, $estadoCompletado]);
            $idFactura = $pdo->lastInsertId();

            $stmtPago = $pdo->prepare("INSERT INTO PAGO_PAYPAL_TB (ID_FACTURA, PAYPAL_ORDER_ID, PAYPAL_CAPTURE_ID, TOTAL, ID_MONEDA, ID_ESTADO) VALUES (?, ?, ?, ?, 1, ?)");
            $stmtPago->execute([$idFactura, $paypalOrderId, $paypalCaptureId, $totalUSD, $estadoCompletado]);

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error Base Datos: " . $e->getMessage()); 
            return false;
        }
    }
}