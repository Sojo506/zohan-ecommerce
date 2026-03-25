<?php

require_once __DIR__ . '/../repositories/CouponRepository.php';

class CouponController extends Controller
{
    private CouponRepository $repository;

    public function __construct()
    {
        $this->repository = new CouponRepository();
    }

    public function validate()
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
        $code = $data['code'] ?? '';

        if (empty($code)) {
            unset($_SESSION['coupon']);
            echo json_encode(['success' => false, 'message' => 'Ingresa un código de cupón']);
            return;
        }

        $coupon = $this->repository->findValidByCode($code);

        if (!$coupon) {
            unset($_SESSION['coupon']);
            echo json_encode(['success' => false, 'message' => 'Cupón inválido, expirado o sin usos disponibles']);
            return;
        }

        $_SESSION['coupon'] = [
            'id' => (int)$coupon['ID_CUPON'],
            'code' => $coupon['CODIGO'],
            'percentage' => (float)$coupon['PORCENTAJE'],
        ];

        echo json_encode([
            'success' => true,
            'message' => 'Cupón aplicado: ' . $coupon['PORCENTAJE'] . '% de descuento',
            'percentage' => (float)$coupon['PORCENTAJE'],
            'code' => $coupon['CODIGO'],
        ]);
    }

    public function remove()
    {
        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: application/json');
        unset($_SESSION['coupon']);

        echo json_encode(['success' => true, 'message' => 'Cupón removido']);
    }
}
