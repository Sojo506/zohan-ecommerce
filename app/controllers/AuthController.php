<?php

require_once __DIR__ . '/../helpers/Security.php';
require_once __DIR__ . '/../services/Mailer.php';
require_once __DIR__ . '/../repositories/ProductRepository.php';

class AuthController extends Controller
{
    private int $ESTADO_ACTIVO = 1;
    private int $ESTADO_INACTIVO = 2;
    private int $ESTADO_PENDIENTE = 3;

    private int $OTP_ACTIVAR_CUENTA = 1;

    public function loginForm()
    {
        $this->view('auth/login');
    }

    public function login()
    {
        $user = trim($_POST['user'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($user === '' || $password === '') {
            $_SESSION['flash_error'] = "Debe completar los campos.";
            header("Location: " . App::url('/login'));
            exit;
        }

        $pdo = Database::connection();

        $sql = "SELECT 
            c.ID_CUENTA,
            c.USERNAME,
            c.PASSWORD,
            c.ID_ESTADO,
            u.IDENTIFICACION,
            u.NOMBRE,
            u.APELLIDO_PATERNO,
            t.NOMBRE AS TIPO_USUARIO
        FROM CUENTA_TB c
            JOIN USUARIO_TB u ON u.IDENTIFICACION = c.IDENTIFICACION
            JOIN TIPO_USUARIO_TB t ON t.ID_TIPO_USUARIO = u.ID_TIPO_USUARIO
            LEFT JOIN CORREO_TB co ON co.IDENTIFICACION = c.IDENTIFICACION
        WHERE c.USERNAME = :username OR co.CORREO = :correo
        LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $user,
            ':correo' => $user
        ]);

        $cuenta = $stmt->fetch();

        if (!$cuenta) {
            $_SESSION['flash_error'] = "Usuario no encontrado.";
            header("Location: " . App::url('/login'));
            exit;
        }

        // validar password
        if (!password_verify($password, $cuenta['PASSWORD'])) {

            // aumentar intentos fallidos
            $pdo->prepare("UPDATE CUENTA_TB SET INTENTOS_FALLIDOS = INTENTOS_FALLIDOS + 1 WHERE ID_CUENTA = :id")
                ->execute([':id' => $cuenta['ID_CUENTA']]);

            $_SESSION['flash_error'] = "Contraseña incorrecta.";
            header("Location: " . App::url('/login'));
            exit;
        }

        // validar estado activo
        if ($cuenta['ID_ESTADO'] != 1) {
            $_SESSION['flash_error'] = "La cuenta no está activada.";
            header("Location: " . App::url('/login'));
            exit;
        }

        // reset intentos
        $pdo->prepare("UPDATE CUENTA_TB SET INTENTOS_FALLIDOS = 0, ULTIMO_LOGIN = NOW() WHERE ID_CUENTA = :id")
            ->execute([':id' => $cuenta['ID_CUENTA']]);

        // crear sesión
        $_SESSION['user'] = [
            'id_cuenta' => $cuenta['ID_CUENTA'],
            'identificacion' => $cuenta['IDENTIFICACION'],
            'nombre' => $cuenta['NOMBRE'],
            'apellido' => $cuenta['APELLIDO_PATERNO'],
            'tipo' => $cuenta['TIPO_USUARIO']
        ];

        try {
            $repo = new ProductRepository();
            $sessionCart = $repo->sanitizeCart($_SESSION['cart'] ?? []);
            $idCuenta = (int)$cuenta['ID_CUENTA'];

            if (!empty($sessionCart)) {
                $repo->saveCartForAccount($idCuenta, $sessionCart);
            } else {
                $dbCart = $repo->fetchCartForAccount($idCuenta);
                if (!empty($dbCart)) {
                    $_SESSION['cart'] = $dbCart;
                }
            }
        } catch (PDOException $e) {
            // Evitar romper login si la sincronizacion falla.
        }

        header("Location: " . App::url('/'));
        exit;
    }

    public function logout()
    {
        session_destroy();
        header("Location: " . App::url('/'));
    }

    public function registerForm()
    {
        $this->view('auth/register');
    }

    public function register()
    {
        $identificacion = trim($_POST['identificacion'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $ap1 = trim($_POST['apellido_paterno'] ?? '');
        $ap2 = trim($_POST['apellido_materno'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $pass1 = $_POST['password'] ?? '';
        $pass2 = $_POST['password2'] ?? '';

        if (
            $identificacion === '' ||
            $username === '' ||
            $nombre === '' ||
            $ap1 === '' ||
            $correo === '' ||
            $pass1 === '' ||
            $pass2 === ''
        ) {
            $_SESSION['flash_error'] = "Faltan campos obligatorios.";
            header("Location: " . App::url('/register'));
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = "Correo inválido.";
            header("Location: " . App::url('/register'));
            exit;
        }

        if ($pass1 !== $pass2) {
            $_SESSION['flash_error'] = "Las contraseñas no coinciden.";
            header("Location: " . App::url('/register'));
            exit;
        }

        $pdo = Database::connection();

        try {
            $pdo->beginTransaction();

            // 1) Insertar usuario en USUARIO_TB
            $sqlUser = "INSERT INTO USUARIO_TB
            (IDENTIFICACION, NOMBRE, APELLIDO_PATERNO, APELLIDO_MATERNO, ID_DIRECCION, ID_TIPO_USUARIO, ID_ESTADO)
            VALUES
            (:ident, :nom, :ap1, :ap2, NULL, :tipoUsuario, :estado)";

            $stmtUser = $pdo->prepare($sqlUser);
            $stmtUser->execute([
                ':ident' => $identificacion,
                ':nom' => $nombre,
                ':ap1' => $ap1,
                ':ap2' => ($ap2 === '' ? null : $ap2),
                ':tipoUsuario' => 2, // cliente
                ':estado' => $this->ESTADO_PENDIENTE,
            ]);

            // 2) Insertar correo en CORREO_TB
            $sqlCorreo = "INSERT INTO CORREO_TB
            (IDENTIFICACION, CORREO, ID_ESTADO)
            VALUES
            (:ident, :correo, :estado)";

            $stmtCorreo = $pdo->prepare($sqlCorreo);
            $stmtCorreo->execute([
                ':ident' => $identificacion,
                ':correo' => $correo,
                ':estado' => $this->ESTADO_PENDIENTE,
            ]);

            // 3) Insertar cuenta en CUENTA_TB
            $hash = Security::hashPassword($pass1);

            $sqlCuenta = "INSERT INTO CUENTA_TB
            (IDENTIFICACION, USERNAME, PASSWORD, INTENTOS_FALLIDOS, ULTIMO_LOGIN, ID_ESTADO)
            VALUES
            (:ident, :user, :pass, 0, NULL, :estado)";

            $stmtCuenta = $pdo->prepare($sqlCuenta);
            $stmtCuenta->execute([
                ':ident' => $identificacion,
                ':user' => $username,
                ':pass' => $hash,
                ':estado' => $this->ESTADO_PENDIENTE,
            ]);

            $idCuenta = (int)$pdo->lastInsertId();

            // 4) Generar OTP
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
                ':tipo' => $this->OTP_ACTIVAR_CUENTA,
                ':hash' => $otpHash,
                ':exp' => $expiresAt,
                ':estado' => $this->ESTADO_ACTIVO,
            ]);

            $pdo->commit();

            // 5) Enviar correo
            Mailer::verifyEmail($correo, $otp);

            $_SESSION['pending_account_id'] = $idCuenta;

            header("Location: " . App::url('/verify-otp'));
            exit;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $_SESSION['flash_error'] = "No se pudo registrar: " . $e->getMessage();
            header("Location: " . App::url('/register'));
            exit;
        }
    }

    public function verifyOtpForm()
    {
        if (!isset($_SESSION['pending_account_id'])) {
            header("Location: " . App::url('/login'));
            exit;
        }

        $this->view('auth/verify_otp');
    }

    public function verifyOtp()
    {
        $otp = trim($_POST['otp'] ?? '');
        $idCuenta = (int)($_SESSION['pending_account_id'] ?? 0);

        if ($idCuenta <= 0 || $otp === '') {
            $_SESSION['flash_error'] = "OTP inválido.";
            header("Location: " . App::url('/verify-otp'));
            exit;
        }

        $pdo = Database::connection();

        // Buscar OTP activo, no expirado, tipo activar cuenta
        $sql = "SELECT OTP_CODE, HASH, EXPIRES_AT, INTENTOS, ACTIVE_FLAG
                FROM CODIGO_OTP_TB
                WHERE ID_CUENTA = :idCuenta
                  AND ID_TIPO_OTP = :tipo
                  AND ACTIVE_FLAG = 1
                ORDER BY CREATED_AT DESC
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':idCuenta' => $idCuenta,
            ':tipo' => $this->OTP_ACTIVAR_CUENTA
        ]);

        $row = $stmt->fetch();
        if (!$row) {
            $_SESSION['flash_error'] = "No se encontró un OTP activo.";
            header("Location: " . App::url('/verify-otp'));
            exit;
        }

        // Validar expiración
        $now = new DateTime();
        $exp = new DateTime($row['EXPIRES_AT']);
        if ($now > $exp) {
            $_SESSION['flash_error'] = "El OTP expiró. (Luego hacemos botón Reenviar)";
            header("Location: " . App::url('/verify-otp'));
            exit;
        }

        // Validar OTP por hash
        if (!password_verify($otp, $row['HASH'])) {
            // aumentar intentos
            $pdo->prepare("UPDATE CODIGO_OTP_TB SET INTENTOS = INTENTOS + 1 WHERE ID_CUENTA = :id")
                ->execute([':id' => $idCuenta]);

            $_SESSION['flash_error'] = "Código incorrecto.";
            header("Location: " . App::url('/verify-otp'));
            exit;
        }

        // Si está correcto: desactivar OTP + activar cuenta
        try {
            $pdo->beginTransaction();

            $pdo->prepare("UPDATE CODIGO_OTP_TB 
                           SET ACTIVE_FLAG = 0 
                           WHERE ID_CUENTA = :id 
                           AND ID_TIPO_OTP = :tipo")
                ->execute([':id' => $idCuenta, ':tipo' => $this->OTP_ACTIVAR_CUENTA]);

            $pdo->prepare("UPDATE CUENTA_TB 
                           SET ID_ESTADO = :estado 
                           WHERE ID_CUENTA = :id")
                ->execute([':estado' => $this->ESTADO_ACTIVO, ':id' => $idCuenta]);

            // activar usuario ligado
            $pdo->prepare("UPDATE USUARIO_TB u
                           JOIN CUENTA_TB c ON c.IDENTIFICACION = u.IDENTIFICACION
                           SET u.ID_ESTADO = :estado
                           WHERE c.ID_CUENTA = :id")
                ->execute([':estado' => $this->ESTADO_ACTIVO, ':id' => $idCuenta]);

            // activar correo ligado
            $pdo->prepare("UPDATE CORREO_TB co
               JOIN CUENTA_TB c ON c.IDENTIFICACION = co.IDENTIFICACION
               SET co.ID_ESTADO = :estado
               WHERE c.ID_CUENTA = :id")
                ->execute([
                    ':estado' => $this->ESTADO_ACTIVO,
                    ':id' => $idCuenta
                ]);

            $pdo->commit();

            unset($_SESSION['pending_account_id']);
            $_SESSION['flash_success'] = "Cuenta activada | Ya podés iniciar sesión.";

            header("Location: " . App::url('/login'));
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['flash_error'] = "Error activando cuenta: " . $e->getMessage();
            header("Location: " . App::url('/verify-otp'));
            exit;
        }
    }
}

