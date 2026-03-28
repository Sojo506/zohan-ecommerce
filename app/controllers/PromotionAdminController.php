<?php

class PromotionAdminController extends Controller
{

    private function checkAdmin()
    {
        // Crear y asignar promociones requiere acceso administrativo.
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

        $repo = new PromotionModel();

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

        $repo = new PromotionModel();

        $repo->create($_POST);

        header("Location: " . App::url('/admin/promotions'));
    }

    public function editForm($id)
    {
        $this->checkAdmin();

        $repo = new PromotionModel();
        $productRepo = new ProductModel();

        $promotion = $repo->find($id);
        $products = $productRepo->all();

        // Además de la promoción, carga qué productos ya están vinculados para editar la relación.
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

        $repo = new PromotionModel();

        $repo->update($_POST['id'], $_POST);

        header("Location: " . App::url('/admin/promotions'));
    }

    public function delete($id)
    {
        $this->checkAdmin();

        $repo = new PromotionModel();

        $repo->delete($id);

        header("Location: " . App::url('/admin/promotions'));
    }

    public function assignProduct()
    {
        $this->checkAdmin();

        $repo = new PromotionModel();

        // Asocia un producto existente a la promoción seleccionada desde el formulario.
        $repo->assignProduct(
            $_POST['promotion'],
            $_POST['product']
        );

        header("Location: " . App::url('/admin/promotions/edit/' . $_POST['promotion']));
    }

    public function removeProduct($promoId, $productId)
    {
        $this->checkAdmin();

        $repo = new PromotionModel();

        $repo->removeProduct($promoId, $productId);

        header("Location: " . App::url('/admin/promotions/edit/' . $promoId));
    }
}
