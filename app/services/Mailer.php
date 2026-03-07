<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class Mailer
{
    public static function verifyEmail(string $toEmail, string $otp): bool
    {
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host = Env::get('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = Env::get('MAIL_USER');
            $mail->Password = Env::get('MAIL_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = Env::get('MAIL_PORT');

            $mail->setFrom(
                Env::get('MAIL_FROM'),
                Env::get('MAIL_FROM_NAME')
            );

            $mail->addAddress($toEmail);

            $mail->isHTML(true);

            $mail->Subject = "Codigo de verificacion - Zohan Tech Store";

            $mail->Body = "
            <h2>Zohan Tech Store</h2>

            <p>Tu codigo de verificacion es:</p>

            <h1 style='letter-spacing:5px'>$otp</h1>

            <p>Este codigo expira en 10 minutos.</p>
            ";

            $mail->send();

            return true;
        } catch (Exception $e) {

            error_log("Mailer error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
