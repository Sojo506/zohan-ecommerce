<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ProductModel.php';

class ProfileController extends Controller
{
    public function index(){
        if (!isset($_SESSION['user'])) {
            header("Location: " . App::url('/login'));
            exit;
        }

        $usuarioModel = new UserModel();
        $productoModel = new ProductModel();

        // obtener id del usuario desde la sesión
        $idUsuario = $_SESSION['user']['identificacion'];

        // datos del usuario
        $user = $usuarioModel->obtenerUsuario($idUsuario);

        // ahora trae facturas
        $facturas = $productoModel->obtenerFacturasUsuario($idUsuario);

        $stats = $usuarioModel->obtenerStatsUsuario($idUsuario);

        $this->view('user/profile', [
            'user' => $user,
            'facturas' => $facturas,
            'stats' => $stats
        ]);
    }
}
?>