<?php

require_once __DIR__ . '/../repositories/CommentRepository.php';

class CommentAdminController extends Controller
{

    private function checkAdmin()
    {
        // Moderar comentarios requiere permisos de administración.
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

        $repo = new CommentRepository();

        $comments = $repo->all();

        $this->adminView('admin/comments/index', [
            'comments' => $comments,
            'pageTitle' => 'Comentarios',
            'currentSection' => 'comments'
        ]);
    }

    public function approve($id)
    {
        $this->checkAdmin();

        $repo = new CommentRepository();

        // El estado 1 representa un comentario visible/aprobado.
        $repo->changeStatus($id, 1);

        header("Location: " . App::url('/admin/comments'));
    }

    public function hide($id)
    {
        $this->checkAdmin();

        $repo = new CommentRepository();

        // El estado 2 permite ocultarlo sin eliminar su registro histórico.
        $repo->changeStatus($id, 2);

        header("Location: " . App::url('/admin/comments'));
    }

    public function delete($id)
    {
        $this->checkAdmin();

        $repo = new CommentRepository();

        $repo->delete($id);

        header("Location: " . App::url('/admin/comments'));
    }
}
