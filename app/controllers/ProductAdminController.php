<?php

use Cloudinary\Cloudinary;

require_once __DIR__ . '/../repositories/ProductRepository.php';
require_once __DIR__ . '/../services/CloudinaryService.php';

class ProductAdminController extends Controller
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

        $repo = new ProductRepository();
        $products = $repo->all();

        $this->adminView('admin/products/index', [
            'products' => $products,
            'pageTitle' => 'Productos',
            'currentSection' => 'products'
        ]);
    }

    public function createForm()
    {
        $this->checkAdmin();

        $repo = new ProductRepository();

        $categories = $repo->getCategories();
        $brands = $repo->getBrands();

        $this->adminView('admin/products/create', [
            'categories' => $categories,
            'brands' => $brands,
            'pageTitle' => 'Crear producto',
            'currentSection' => 'products'
        ]);
    }

    public function create()
    {
        $this->checkAdmin();

        $repo = new ProductRepository();
        $repo->create($_POST);

        header("Location: " . App::url('/admin/products'));
        exit;
    }

    public function editForm($id)
    {
        $this->checkAdmin();

        $repo = new ProductRepository();

        $product = $repo->find($id);

        $categories = $repo->getCategories();
        $brands = $repo->getBrands();

        $images = $repo->getImages($id);

        if (!$product) {
            echo "Producto no encontrado";
            return;
        }

        $this->adminView('admin/products/edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'images' => $images,
            'pageTitle' => 'Editar producto',
            'currentSection' => 'products'
        ]);
    }

    public function update()
    {
        $this->checkAdmin();

        $id = $_POST['id'];

        $repo = new ProductRepository();

        $repo->update($id, $_POST);

        header("Location: " . App::url('/admin/products/list'));
    }

    public function delete($id)
    {
        $this->checkAdmin();

        $repo = new ProductRepository();

        $repo->delete($id);

        header("Location: " . App::url('/admin/products/list'));
    }

    public function uploadImage()
    {
        $this->checkAdmin();

        $productId = $_POST['product_id'];

        $cloudinary = new CloudinaryService();

        $url = $cloudinary->upload($_FILES['image']['tmp_name']);

        $repo = new ProductRepository();
        $repo->addImage($productId, $url);

        header("Location: " . App::url('/admin/products/edit/' . $productId));
    }

    public function deleteImage($id)
    {
        $this->checkAdmin();

        $repo = new ProductRepository();
        $repo->deleteImage($id);

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
