<?php

class CategoryAdminController extends Controller
{

    private function checkAdmin()
    {
        // Las categorías se administran solo desde el panel autenticado.
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

        $repo = new CategoryModel();

        $categories = $repo->all();

        $this->adminView('admin/categories/index', [
            'categories' => $categories,
            'pageTitle' => 'Categorías',
            'currentSection' => 'categories'
        ]);
    }

    public function createForm()
    {
        $this->checkAdmin();

        $this->adminView('admin/categories/create', [
            'pageTitle' => 'Crear categoría',
            'currentSection' => 'categories'
        ]);
    }

    public function create()
    {
        $this->checkAdmin();

        $repo = new CategoryModel();

        // La creación es directa porque el modelo recibe únicamente el nombre saneado desde el form.
        $repo->create($_POST['name']);

        header("Location: " . App::url('/admin/categories'));
        exit;
    }

    public function editForm($id)
    {
        $this->checkAdmin();

        $repo = new CategoryModel();

        // Se obtiene la categoría puntual para poblar el formulario de edición.
        $category = $repo->find($id);

        $this->adminView('admin/categories/edit', [
            'category' => $category,
            'pageTitle' => 'Editar categoría',
            'currentSection' => 'categories'
        ]);
    }

    public function update()
    {
        $this->checkAdmin();

        $repo = new CategoryModel();

        $repo->update($_POST['id'], $_POST['name']);

        header("Location: " . App::url('/admin/categories'));
    }

    public function delete($id)
    {
        $this->checkAdmin();

        $repo = new CategoryModel();

        $repo->delete($id);

        header("Location: " . App::url('/admin/categories'));
    }
}
