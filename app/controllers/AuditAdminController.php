<?php

class AuditAdminController extends Controller
{

    private function checkAdmin()
    {
        // La bitácora solo se expone a administradores porque contiene trazas operativas.
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

        $this->adminView('admin/audit/index', [
            'pageTitle' => 'Auditoría',
            'currentSection' => 'audit'
        ]);
    }
}
