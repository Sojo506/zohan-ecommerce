<?php
require_once __DIR__ . '/../helpers/Security.php';
require_once __DIR__ . '/../services/Mailer.php';
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

        // verificar si el correo ya existe en otro usuario
        $sql = "SELECT COUNT(*) 
        FROM CORREO_TB 
        WHERE CORREO = :correo 
        AND IDENTIFICACION != :ident";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':correo' => $correo,
            ':ident' => $identificacion
        ]);

        $existe = $stmt->fetchColumn();

        if ($existe > 0) {
            $_SESSION['flash_error'] = "El correo ingresado ya está registrado en otra cuenta.";
            header("Location: " . App::url('/editProfile'));
            exit;
        }

        // obtener correo registrado en BD
        $sqlCorreoActual = "SELECT CORREO
                    FROM CORREO_TB
                    WHERE IDENTIFICACION = :ident
                    LIMIT 1";

        $stmtCorreoActual = $pdo->prepare($sqlCorreoActual);
        $stmtCorreoActual->execute([':ident' => $identificacion]);

        $correoActual = $stmtCorreoActual->fetchColumn();

        // si el correo cambió se requiere verificación OTP
        if ($correoActual !== $correo) {

            // obtener id de cuenta
            $sqlCuenta = "SELECT ID_CUENTA
                  FROM CUENTA_TB
                  WHERE IDENTIFICACION = :ident
                  LIMIT 1";

            $stmtCuenta = $pdo->prepare($sqlCuenta);
            $stmtCuenta->execute([':ident' => $identificacion]);

            $idCuenta = $stmtCuenta->fetchColumn();

            $otp = Security::generateOtp(6);
            $otpHash = password_hash($otp, PASSWORD_BCRYPT);
            $expiresAt = (new DateTime('+10 minutes'))->format('Y-m-d H:i:s');

            $sqlOtp = "INSERT INTO CODIGO_OTP_TB
            (OTP_CODE, ID_CUENTA, ID_TIPO_OTP, HASH, EXPIRES_AT, INTENTOS, ACTIVE_FLAG, ID_ESTADO)
            VALUES
            (:otp, :idCuenta, :tipo, :hash, :exp, 0, 1, :estado)";

            $stmtOtp = $pdo->prepare($sqlOtp);

            $stmtOtp->execute([
                ':otp' => $otp,
                ':idCuenta' => $idCuenta,
                ':tipo' => 3, // cambiar correo
                ':hash' => $otpHash,
                ':exp' => $expiresAt,
                ':estado' => 1
            ]);

            $_SESSION['pending_new_email'] = $correo;
            $_SESSION['pending_account_id'] = $idCuenta;

            $_SESSION['pending_profile_update'] = [
                'nombre' => $nombre,
                'ap1' => $ap1,
                'ap2' => $ap2
            ];

            Mailer::verifyEmail($correo, $otp);

            $_SESSION['flash_success'] = "Se envió un código de verificación al nuevo correo.";

            header("Location: " . App::url('/verify-otp'));
            exit;
        }

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