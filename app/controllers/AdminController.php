<?php

class AdminController extends Controller
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

        $this->adminView('admin/dashboard', [
            'pageTitle' => 'Dashboard',
            'currentSection' => 'dashboard'
        ]);
    }

    public function products()
    {
        $this->checkAdmin();

        $this->adminView('admin/products', [
            'pageTitle' => 'Productos',
            'currentSection' => 'products'
        ]);
    }

    public function orders()
    {
        $this->checkAdmin();

        $this->adminView('admin/orders', [
            'pageTitle' => 'Ventas',
            'currentSection' => 'orders'
        ]);
    }

    public function users()
    {
        $this->checkAdmin();

        $this->adminView('admin/users', [
            'pageTitle' => 'Usuarios',
            'currentSection' => 'users'
        ]);
    }

    public function inventory()
    {
        $this->checkAdmin();

        $this->adminView('admin/inventory', [
            'pageTitle' => 'Inventario',
            'currentSection' => 'inventory'
        ]);
    }

    public function promotions()
    {
        $this->checkAdmin();

        $this->adminView('admin/promotions', [
            'pageTitle' => 'Promociones',
            'currentSection' => 'promotions'
        ]);
    }
}