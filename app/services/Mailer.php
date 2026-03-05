<?php

class Mailer
{
    // DEV: no envía correo real todavía; solo devuelve true
    public static function sendOtp(string $toEmail, string $otp): bool
    {
        // En producción aquí va SMTP (Gmail/Sendgrid)
        // Por ahora lo dejamos visible para pruebas:
        $_SESSION['DEV_LAST_OTP'] = $otp;
        $_SESSION['DEV_LAST_OTP_EMAIL'] = $toEmail;
        return true;
    }
}
