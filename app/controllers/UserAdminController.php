<?php

require_once __DIR__ . '/../models/UserModel.php';

class UserAdminController extends Controller
{

    private function checkAdmin()
    {
        // Cambios de rol o estado se protegen para evitar administración anónima.
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

        $repo = new UserModel();

        $users = $repo->all();

        $this->adminView('admin/users/index', [
            'users' => $users,
            'pageTitle' => 'Usuarios',
            'currentSection' => 'users'
        ]);
    }

    public function detail($id)
    {
        $this->checkAdmin();

        $repo = new UserModel();

        // El detalle reúne datos base del usuario y sus medios de contacto asociados.
        $user = $repo->find($id);
        $emails = $repo->emails($id);
        $phones = $repo->phones($id);

        $this->adminView('admin/users/detail', [
            'user' => $user,
            'emails' => $emails,
            'phones' => $phones,
            'pageTitle' => 'Detalle usuario',
            'currentSection' => 'users'
        ]);
    }

    public function changeStatus($id, $status)
    {
        $this->checkAdmin();

        $repo = new UserModel();

        $repo->changeStatus($id, $status);

        header("Location: " . App::url('/admin/users/' . $id));
    }

    public function changeRole($id, $role)
    {
        $this->checkAdmin();

        $repo = new UserModel();

        // El valor del rol llega desde la ruta para simplificar acciones rápidas desde la tabla.
        $repo->changeRole($id, $role);

        header("Location: " . App::url('/admin/users/' . $id));
    }
}
