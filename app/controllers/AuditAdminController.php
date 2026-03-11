<?php

require_once __DIR__ . '/../repositories/AuditRepository.php';

class AuditAdminController extends Controller
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

        $repo = new AuditRepository();

        $logs = $repo->all();

        $this->adminView('admin/audit/index', [
            'logs' => $logs,
            'pageTitle' => 'Auditoría',
            'currentSection' => 'audit'
        ]);
    }
}
