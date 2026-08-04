<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService {
    public static function sendNotification(string $toEmail, string $toName, string $annonceTitle, string $senderName) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = getenv('SMTP_HOST') ?: 'localhost';
            $smtpAuth = getenv('SMTP_AUTH');
            $mail->SMTPAuth   = ($smtpAuth === 'true' || $smtpAuth === '1');
            $mail->Username   = getenv('SMTP_USER') ?: '';
            $mail->Password   = getenv('SMTP_PASS') ?: '';
            $secure = getenv('SMTP_SECURE');
            if ($secure === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($secure === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $mail->SMTPSecure = false;
            }
            $mail->Port       = (int)(getenv('SMTP_PORT') ?: 587);
            $mail->CharSet    = 'UTF-8';

            // Recipients
            $mail->setFrom(getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@colocation.com', getenv('MAIL_FROM_NAME') ?: 'CoLocation');
            $mail->addAddress($toEmail, $toName);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Nouveau message concernant votre annonce : $annonceTitle";
            
            $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . Config::url('pages/messages.php');
            
            $mail->Body = "
                <h2>Bonjour $toName,</h2>
                <p>Vous avez reçu un nouveau message de <strong>$senderName</strong> concernant votre annonce <strong>\"$annonceTitle\"</strong>.</p>
                <p>Connectez-vous sur le site pour répondre :</p>
                <p><a href='$url' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Voir mes messages</a></p>
                <br>
                <p>Cordialement,<br>L'équipe CoLocation</p>
            ";
            
            $mail->AltBody = "Bonjour $toName,\n\nVous avez reçu un nouveau message de $senderName concernant votre annonce \"$annonceTitle\".\n\nConnectez-vous sur le site pour répondre : $url";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
