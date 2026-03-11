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

    public function edit(){
        if (!isset($_SESSION['user'])) {
            header("Location: " . App::url('/login'));
            exit;
        }

        $identificacion = $_SESSION['user']['identificacion'];

        $userModel = new UserModel();
        $user = $userModel->obtenerUsuario($identificacion);

        $this->view('user/editProfile', [
            'user' => $user
        ]);
    }

    public function update()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . App::url('/login'));
            exit;
        }

        $identificacion = $_SESSION['user']['identificacion'];

        $nombre = trim($_POST['nombre'] ?? '');
        $ap1 = trim($_POST['apellido_paterno'] ?? '');
        $ap2 = trim($_POST['apellido_materno'] ?? '');
        $correo = trim($_POST['correo'] ?? '');

        if ($nombre === '' || $ap1 === '' || $correo === '') {
            $_SESSION['flash_error'] = "Faltan campos obligatorios.";
            header("Location: " . App::url('/profile/edit'));
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = "Correo inválido.";
            header("Location: " . App::url('/profile/edit'));
            exit;
        }

        $pdo = Database::connection();

        try {

            $pdo->beginTransaction();

            // actualizar usuario
            $sqlUser = "UPDATE USUARIO_TB
                    SET 
                        NOMBRE = :nombre,
                        APELLIDO_PATERNO = :ap1,
                        APELLIDO_MATERNO = :ap2
                    WHERE IDENTIFICACION = :ident";

            $stmtUser = $pdo->prepare($sqlUser);

            $stmtUser->execute([
                ':nombre' => $nombre,
                ':ap1' => $ap1,
                ':ap2' => ($ap2 === '' ? null : $ap2),
                ':ident' => $identificacion
            ]);

            // actualizar correo
            $sqlCorreo = "UPDATE CORREO_TB
                      SET CORREO = :correo
                      WHERE IDENTIFICACION = :ident";

            $stmtCorreo = $pdo->prepare($sqlCorreo);

            $stmtCorreo->execute([
                ':correo' => $correo,
                ':ident' => $identificacion
            ]);

            $pdo->commit();

            // actualizar sesión
            $_SESSION['user']['nombre'] = $nombre;
            $_SESSION['user']['apellido'] = $ap1;

            $_SESSION['flash_success'] = "Perfil actualizado correctamente.";
            header("Location: " . App::url('/profile'));
            exit;
        } catch (PDOException $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $_SESSION['flash_error'] = "Error actualizando perfil.";
            header("Location: " . App::url('/profile/edit'));
            exit;
        }
    }
}
?>