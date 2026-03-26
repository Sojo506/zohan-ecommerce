<?php

require_once __DIR__ . '/../models/PaymentModel.php';
require_once __DIR__ . '/../models/CouponModel.php';

class PaymentController extends Controller
{
    private PaymentModel $paymentModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
    }

    public function capture()
    {
        // Limpia cualquier salida previa para no romper la respuesta JSON del endpoint.
        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
            return;
        }

        // PayPal envía el identificador de la orden en el cuerpo JSON de la petición.
        $data = json_decode(file_get_contents('php://input'), true);
        $orderId = $data['orderID'] ?? null;

        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'No se recibió el ID de la orden']);
            return;
        }

        $accessToken = $this->paymentModel->getPayPalAccessToken();
        if (!$accessToken) {
            echo json_encode(['success' => false, 'message' => 'Fallo la autenticacion con PayPal. Revisa tus credenciales o conexión cURL.']);
            return;
        }

        $captureResult = $this->paymentModel->capturePayPalOrder($orderId, $accessToken);

        if (($captureResult['status'] ?? null) !== 'COMPLETED') {
            $errorMsg = $captureResult['message'] ?? 'El pago no fue completado por PayPal';
            echo json_encode(['success' => false, 'message' => $errorMsg]);
            return;
        }

        $paypalCaptureId = $captureResult['purchase_units'][0]['payments']['captures'][0]['id'] ?? '';
        $amountUsd = $captureResult['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0;

        // Validar y aplicar cupón si existe en la sesión.
        $coupon = $_SESSION['coupon'] ?? null;
        $couponId = null;

        if ($coupon) {
            $couponRepo = new CouponModel();
            $validCoupon = $couponRepo->findValidByCode($coupon['code']);

            if ($validCoupon) {
                $couponId = (int)$validCoupon['ID_CUPON'];
            }
        }

        // Solo después de una captura confirmada se registra la venta en la BD local.
        $result = $this->paymentModel->registerCapturedPayment($orderId, $paypalCaptureId, $amountUsd, $couponId);

        if (($result['success'] ?? false) === true) {
            // El carrito se vacía únicamente cuando el registro local terminó bien.
            $this->paymentModel->clearCart();

            // Decrementar uso del cupón y limpiar sesión.
            if ($couponId && isset($couponRepo)) {
                $couponRepo->decrementUsage($couponId);
            }
            unset($_SESSION['coupon']);

            $message = 'Pago y registro completados';
            if (($result['email_sent'] ?? true) === false) {
                $message .= '. La compra se registró, pero no se pudo enviar el correo.';
            }

            echo json_encode(['success' => true, 'message' => $message]);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => $result['message'] ?? 'El pago se hizo, pero ocurrio un error en la base de datos local'
        ]);
    }
}
