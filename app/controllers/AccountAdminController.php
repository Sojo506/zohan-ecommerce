<?php

class AccountAdminController extends Controller
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

        $repo = new AccountModel();

        $accounts = $repo->all();

        $this->adminView('admin/accounts/index', [
            'accounts' => $accounts,
            'pageTitle' => 'Cuentas',
            'currentSection' => 'accounts'
        ]);
    }

    public function createForm()
    {
        $this->checkAdmin();

        $repo = new AccountModel();

        $this->adminView('admin/accounts/create', [
            'users' => $repo->availableUsers(),
            'statuses' => $repo->statuses(),
            'pageTitle' => 'Crear cuenta',
            'currentSection' => 'accounts'
        ]);
    }

    public function create()
    {
        $this->checkAdmin();

        $repo = new AccountModel();
        $identificacion = trim((string) ($_POST['identificacion'] ?? ''));
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($identificacion === '' || $username === '' || $password === '') {
            header("Location: " . App::url('/admin/accounts/create'));
            exit;
        }

        $repo->create([
            'identificacion' => $identificacion,
            'username' => $username,
            'password' => $password,
            'estado' => (int) ($_POST['estado'] ?? 1)
        ]);

        header("Location: " . App::url('/admin/accounts'));
        exit;
    }

    public function editForm($id)
    {
        $this->checkAdmin();

        $repo = new AccountModel();
        $account = $repo->find((int) $id);

        if (!$account) {
            header("Location: " . App::url('/admin/accounts'));
            exit;
        }

        $this->adminView('admin/accounts/edit', [
            'account' => $account,
            'users' => $repo->availableUsers((int) $id),
            'statuses' => $repo->statuses(),
            'pageTitle' => 'Editar cuenta',
            'currentSection' => 'accounts'
        ]);
    }

    public function update()
    {
        $this->checkAdmin();

        $repo = new AccountModel();
        $id = (int) ($_POST['id'] ?? 0);
        $identificacion = trim((string) ($_POST['identificacion'] ?? ''));
        $username = trim((string) ($_POST['username'] ?? ''));

        if ($id <= 0 || $identificacion === '' || $username === '') {
            header("Location: " . App::url('/admin/accounts'));
            exit;
        }

        $repo->update($id, [
            'identificacion' => $identificacion,
            'username' => $username,
            'password' => (string) ($_POST['password'] ?? ''),
            'estado' => (int) ($_POST['estado'] ?? 1)
        ]);

        header("Location: " . App::url('/admin/accounts'));
        exit;
    }

    public function delete($id)
    {
        $this->checkAdmin();

        $repo = new AccountModel();
        $repo->delete((int) $id);

        header("Location: " . App::url('/admin/accounts'));
        exit;
    }
}
