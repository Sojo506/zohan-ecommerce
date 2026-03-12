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

    public static function purchaseConfirmation(string $toEmail, array $data): bool
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
            $mail->Subject = "Confirmacion de compra - Zohan Tech Store";

            $customerName = htmlspecialchars((string)($data['customer_name'] ?? 'Cliente'));
            $saleId = (int)($data['sale_id'] ?? 0);
            $invoiceId = (int)($data['invoice_id'] ?? 0);
            $paypalOrderId = htmlspecialchars((string)($data['paypal_order_id'] ?? ''));
            $subtotal = number_format((float)($data['subtotal'] ?? 0), 2);
            $tax = number_format((float)($data['tax'] ?? 0), 2);
            $total = number_format((float)($data['total'] ?? 0), 2);

            $rows = '';
            foreach (($data['items'] ?? []) as $item) {
                $rows .= sprintf(
                    '<tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;">%s</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;text-align:center;">%d</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;text-align:right;">$ %s</td></tr>',
                    htmlspecialchars((string)($item['name'] ?? 'Producto')),
                    (int)($item['quantity'] ?? 0),
                    number_format((float)($item['subtotal'] ?? 0), 2)
                );
            }

            $mail->Body = "
            <h2>Zohan Tech Store</h2>
            <p>Hola {$customerName}, tu compra fue procesada correctamente.</p>
            <p><strong>Venta:</strong> #{$saleId}<br><strong>Factura:</strong> #{$invoiceId}<br><strong>Orden PayPal:</strong> {$paypalOrderId}</p>
            <table style='width:100%;border-collapse:collapse;margin:20px 0;'>
                <thead>
                    <tr>
                        <th style='text-align:left;padding:8px;border-bottom:2px solid #111827;'>Producto</th>
                        <th style='text-align:center;padding:8px;border-bottom:2px solid #111827;'>Cantidad</th>
                        <th style='text-align:right;padding:8px;border-bottom:2px solid #111827;'>Subtotal</th>
                    </tr>
                </thead>
                <tbody>{$rows}</tbody>
            </table>
            <p><strong>Subtotal:</strong> $ {$subtotal}<br><strong>Impuesto:</strong> $ {$tax}<br><strong>Total:</strong> $ {$total}</p>
            <p>Gracias por comprar con nosotros.</p>
            ";

            $mail->send();

            return true;
        } catch (Exception $e) {
            error_log("Mailer error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
