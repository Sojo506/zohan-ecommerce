<?php

require_once __DIR__ . '/../repositories/PaymentRepository.php';

class PaymentController extends Controller
{
    private PaymentRepository $repository;

    public function __construct()
    {
        $this->repository = new PaymentRepository();
    }

    public function capture()
    {
        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $orderId = $data['orderID'] ?? null;

        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'No se recibió el ID de la orden']);
            return;
        }

        $accessToken = $this->repository->getPayPalAccessToken();
        if (!$accessToken) {
            echo json_encode(['success' => false, 'message' => 'Fallo la autenticacion con PayPal. Revisa tus credenciales o conexión cURL.']);
            return;
        }

        $captureResult = $this->repository->capturePayPalOrder($orderId, $accessToken);

        if (($captureResult['status'] ?? null) !== 'COMPLETED') {
            $errorMsg = $captureResult['message'] ?? 'El pago no fue completado por PayPal';
            echo json_encode(['success' => false, 'message' => $errorMsg]);
            return;
        }

        $paypalCaptureId = $captureResult['purchase_units'][0]['payments']['captures'][0]['id'] ?? '';
        $amountUsd = $captureResult['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0;
        $result = $this->repository->registerCapturedPayment($orderId, $paypalCaptureId, $amountUsd);

        if (($result['success'] ?? false) === true) {
            $this->repository->clearCart();

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
