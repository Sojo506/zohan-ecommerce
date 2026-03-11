<?php

require_once __DIR__ . '/../repositories/PromotionRepository.php';
require_once __DIR__ . '/../repositories/ProductRepository.php';

class PromotionAdminController extends Controller
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

        $repo = new PromotionRepository();

        $promotions = $repo->all();

        $this->adminView('admin/promotions/index', [
            'promotions' => $promotions,
            'pageTitle' => 'Promociones',
            'currentSection' => 'promotions'
        ]);
    }

    public function createForm()
    {
        $this->checkAdmin();

        $this->adminView('admin/promotions/create', [
            'pageTitle' => 'Crear promoción',
            'currentSection' => 'promotions'
        ]);
    }

    public function create()
    {
        $this->checkAdmin();

        $repo = new PromotionRepository();

        $repo->create($_POST);

        header("Location: " . App::url('/admin/promotions'));
    }

    public function editForm($id)
    {
        $this->checkAdmin();

        $repo = new PromotionRepository();
        $productRepo = new ProductRepository();

        $promotion = $repo->find($id);
        $products = $productRepo->all();
        $assigned = $repo->products($id);

        $this->adminView('admin/promotions/edit', [
            'promotion' => $promotion,
            'products' => $products,
            'assigned' => $assigned,
            'pageTitle' => 'Editar promoción',
            'currentSection' => 'promotions'
        ]);
    }

    public function update()
    {
        $this->checkAdmin();

        $repo = new PromotionRepository();

        $repo->update($_POST['id'], $_POST);

        header("Location: " . App::url('/admin/promotions'));
    }

    public function delete($id)
    {
        $this->checkAdmin();

        $repo = new PromotionRepository();

        $repo->delete($id);

        header("Location: " . App::url('/admin/promotions'));
    }

    public function assignProduct()
    {
        $this->checkAdmin();

        $repo = new PromotionRepository();

        $repo->assignProduct(
            $_POST['promotion'],
            $_POST['product']
        );

        header("Location: " . App::url('/admin/promotions/edit/' . $_POST['promotion']));
    }

    public function removeProduct($promoId, $productId)
    {
        $this->checkAdmin();

        $repo = new PromotionRepository();

        $repo->removeProduct($promoId, $productId);

        header("Location: " . App::url('/admin/promotions/edit/' . $promoId));
    }
}
