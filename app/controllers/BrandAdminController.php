<?php

class BrandAdminController extends Controller
{

    private function checkAdmin()
    {
        // Todas las operaciones de marcas pasan por esta validación simple de acceso.
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

        $repo = new BrandModel();

        $brands = $repo->all();

        $this->adminView('admin/brands/index', [
            'brands' => $brands,
            'pageTitle' => 'Marcas',
            'currentSection' => 'brands'
        ]);
    }

    public function createForm()
    {
        $this->checkAdmin();

        $this->adminView('admin/brands/create', [
            'pageTitle' => 'Crear marca',
            'currentSection' => 'brands'
        ]);
    }

    public function create()
    {
        $this->checkAdmin();

        $repo = new BrandModel();

        // El formulario envía solo el nombre, y el modelo resuelve la persistencia.
        $repo->create($_POST['name']);

        header("Location: " . App::url('/admin/brands'));
    }

    public function editForm($id)
    {
        $this->checkAdmin();

        $repo = new BrandModel();

        // Carga la marca actual para reutilizar el mismo dato como estado inicial del formulario.
        $brand = $repo->find($id);

        $this->adminView('admin/brands/edit', [
            'brand' => $brand,
            'pageTitle' => 'Editar marca',
            'currentSection' => 'brands'
        ]);
    }

    public function update()
    {
        $this->checkAdmin();

        $repo = new BrandModel();

        $repo->update($_POST['id'], $_POST['name']);

        header("Location: " . App::url('/admin/brands'));
    }

    public function delete($id)
    {
        $this->checkAdmin();

        $repo = new BrandModel();

        $repo->delete($id);

        header("Location: " . App::url('/admin/brands'));
    }
}
