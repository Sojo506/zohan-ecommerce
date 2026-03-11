<?php

require_once __DIR__ . '/../repositories/CouponRepository.php';

class CouponAdminController extends Controller
{

    private function checkAdmin()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . App::url('/login'));
            exit;
        }

        if ($_SESSION['user']['tipo'] !== 'ADMIN') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $repo = new CouponRepository();

        $coupons = $repo->all();

        $this->adminView('admin/coupons/index', [
            'coupons' => $coupons,
            'pageTitle' => 'Cupones',
            'currentSection' => 'coupons'
        ]);
    }

    public function createForm()
    {
        $this->checkAdmin();

        $this->adminView('admin/coupons/create', [
            'pageTitle' => 'Crear cupón',
            'currentSection' => 'coupons'
        ]);
    }

    public function create()
    {
        $this->checkAdmin();

        $repo = new CouponRepository();

        $repo->create($_POST);

        header("Location: " . App::url('/admin/coupons'));
    }

    public function editForm($id)
    {
        $this->checkAdmin();

        $repo = new CouponRepository();

        $coupon = $repo->find($id);

        $this->adminView('admin/coupons/edit', [
            'coupon' => $coupon,
            'pageTitle' => 'Editar cupón',
            'currentSection' => 'coupons'
        ]);
    }

    public function update()
    {
        $this->checkAdmin();

        $repo = new CouponRepository();

        $repo->update($_POST['id'], $_POST);

        header("Location: " . App::url('/admin/coupons'));
    }

    public function delete($id)
    {
        $this->checkAdmin();

        $repo = new CouponRepository();

        $repo->delete($id);

        header("Location: " . App::url('/admin/coupons'));
    }
}
