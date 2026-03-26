<?php
require_once __DIR__ . '/../helpers/Security.php';
require_once __DIR__ . '/../services/Mailer.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/InvoiceModel.php';
require_once __DIR__ . '/../models/CommentModel.php';


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

        // La vista de perfil mezcla datos personales, historial de compra y métricas resumidas.
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

        // Si cambia el correo, primero se valida posesión del nuevo buzón mediante OTP.
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

            // La actualización definitiva queda pendiente hasta que el usuario verifique el código.
            Mailer::verifyEmail($correo, $otp);

            $_SESSION['flash_success'] = "Se envió un código de verificación al nuevo correo.";

            header("Location: " . App::url('/verify-otp'));
            exit;
        }

        try {

            // Si el correo no cambió, el perfil se actualiza directamente en una transacción corta.
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

    public function sendPasswordOtp()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . App::url('/login'));
            exit;
        }

        $identificacion = $_SESSION['user']['identificacion'];

        $pdo = Database::connection();

        // obtener id cuenta
        $stmt = $pdo->prepare("
        SELECT ID_CUENTA 
        FROM CUENTA_TB
        WHERE IDENTIFICACION = :ident
        LIMIT 1");

        $stmt->execute([':ident' => $identificacion]);

        $idCuenta = $stmt->fetchColumn();

        if (!$idCuenta) {
            $_SESSION['flash_error'] = "No se pudo generar el código.";
            header("Location: " . App::url('/profile'));
            exit;
        }

        // generar OTP
        $otp = Security::generateOtp(6);
        $otpHash = password_hash($otp, PASSWORD_BCRYPT);
        $expiresAt = (new DateTime('+10 minutes'))->format('Y-m-d H:i:s');

        $sql = "INSERT INTO CODIGO_OTP_TB
        (OTP_CODE, ID_CUENTA, ID_TIPO_OTP, HASH, EXPIRES_AT, INTENTOS, ACTIVE_FLAG, ID_ESTADO)
        VALUES
        (:otp, :idCuenta, :tipo, :hash, :exp, 0, 1, 1)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':otp' => $otp,
            ':idCuenta' => $idCuenta,
            ':tipo' => 2, // OTP cambiar contraseña
            ':hash' => $otpHash,
            ':exp' => $expiresAt
        ]);

        // Este ID de cuenta luego lo reutiliza AuthController al validar el OTP ingresado.
        $_SESSION['pending_account_id'] = $idCuenta;

        $stmtCorreo = $pdo->prepare("
            SELECT CORREO
            FROM CORREO_TB
            WHERE IDENTIFICACION = :ident
            LIMIT 1");

        $stmtCorreo->execute([
            ':ident' => $identificacion
        ]);

        $correo = $stmtCorreo->fetchColumn();

        // enviar correo
        Mailer::verifyEmail($correo, $otp);

        $_SESSION['flash_success'] = "Te enviamos un código de verificación a tu correo.";

        header("Location: " . App::url('/verify-otp'));
        exit;
    }

    public function changePassword()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . App::url('/login'));
            exit;
        }

        // Solo se permite llegar aquí después de validar correctamente el OTP.
        if (!isset($_SESSION['password_otp_verified'])) {
            header("Location: " . App::url('/profile'));
            exit;
        }

        $identificacion = $_SESSION['user']['identificacion'];

        $userModel = new UserModel();
        $user = $userModel->obtenerUsuario($identificacion);

        $this->view('user/changePassword', [
            'user' => $user
        ]);
    }

    public function updatePassword()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . App::url('/login'));
            exit;
        }

        if (!isset($_SESSION['password_otp_verified'])) {
            header("Location: " . App::url('/profile'));
            exit;
        }

        $pass1 = trim($_POST['password'] ?? '');
        $pass2 = trim($_POST['password2'] ?? '');

        if ($pass1 === '' || $pass2 === '') {
            $_SESSION['flash_error'] = "Debes completar ambos campos.";
            header("Location: " . App::url('/changePassword'));
            exit;
        }

        if ($pass1 !== $pass2) {
            $_SESSION['flash_error'] = "Las contraseñas no coinciden.";
            header("Location: " . App::url('/changePassword'));
            exit;
        }

        if (strlen($pass1) < 6) {
            $_SESSION['flash_error'] = "La contraseña debe tener mínimo 6 caracteres.";
            header("Location: " . App::url('/changePassword'));
            exit;
        }

        $identificacion = $_SESSION['user']['identificacion'];

        $pdo = Database::connection();

        // La contraseña nunca se guarda en texto plano; solo persiste el hash bcrypt.
        $hash = password_hash($pass1, PASSWORD_BCRYPT);

        try {

            $stmt = $pdo->prepare("
            UPDATE CUENTA_TB
            SET PASSWORD = :pass
            WHERE IDENTIFICACION = :ident
            ");

            $stmt->execute([
                ':pass' => $hash,
                ':ident' => $identificacion
            ]);

            // limpiar sesión OTP
            unset($_SESSION['password_otp_verified']);

            $_SESSION['flash_success'] = "Contraseña actualizada correctamente.";

            header("Location: " . App::url('/profile'));
            exit;
        } catch (PDOException $e) {

            $_SESSION['flash_error'] = "Error actualizando contraseña.";
            header("Location: " . App::url('/changePassword'));
            exit;
        }
    }

        public function invoiceDetail($id)
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . App::url('/login'));
            exit;
        }

        $invoiceId = $id;
        $identificacion = $_SESSION['user']['identificacion'];

        $invoiceRepo = new InvoiceModel();

        // Valida que la factura realmente pertenezca al usuario autenticado.
        $invoice = $invoiceRepo->findForUser($invoiceId, $identificacion);

        if (!$invoice) {
            $_SESSION['flash_error'] = "Factura no encontrada.";
            header("Location: " . App::url('/profile'));
            exit;
        }

        $products = $invoiceRepo->saleProductsDetailed($invoiceId);

        $this->view('user/invoiceDetail', [
            'invoice' => $invoice,
            'products' => $products,
            'user' => $_SESSION['user']
        ]);
    }

    public function commentProduct()
    {
        // El endpoint puede responder AJAX, así que limpia cualquier salida previa inesperada.
        if (ob_get_length()) {
            ob_clean();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: " . App::url('/login'));
            exit;
        }

        // Acepta payload JSON o form tradicional para ser reutilizable desde distintas vistas.
        $payload = json_decode(file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            $payload = $_POST;
        }

        $invoiceId = (int)($payload['invoice_id'] ?? 0);
        $productId = (int)($payload['product_id'] ?? 0);
        $comment = trim((string)($payload['comment'] ?? ''));
        $rating = (int)($payload['rating'] ?? 5);

        // Acota la puntuación al rango esperado por la lógica de comentarios.
        if ($rating < 1) {
            $rating = 1;
        }
        if ($rating > 5) {
            $rating = 5;
        }

        if ($invoiceId <= 0 || $productId <= 0 || $comment === '') {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
            return;
        }

        if (mb_strlen($comment) > 1000) {
            echo json_encode(['success' => false, 'message' => 'El comentario es demasiado largo.']);
            return;
        }

        $identificacion = $_SESSION['user']['identificacion'];

        $commentRepo = new CommentModel();
        $saved = $commentRepo->createForProduct($productId, $identificacion, $comment, $rating);
        if ($saved) {
            echo json_encode(['success' => true, 'message' => 'Comentario enviado.']);
            return;
        }

        echo json_encode(['success' => false, 'message' => 'No se pudo guardar el comentario.']);
    }

}
?>
