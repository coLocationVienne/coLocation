<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService {
    
    private static function configureSMTP(PHPMailer $mail) {
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
        $mail->setFrom(getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@colocation.com', getenv('MAIL_FROM_NAME') ?: 'CoLocation');
    }

    public static function sendNotification(string $toEmail, string $toName, string $annonceTitle, string $senderName) {
        $mail = new PHPMailer(true);
        try {
            self::configureSMTP($mail);
            $mail->addAddress($toEmail, $toName);
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

    public static function sendVisitConfirmation(array $details) {
        $mail = new PHPMailer(true);
        try {
            self::configureSMTP($mail);
            
            // Send to Tenant
            $mail->addAddress($details['tenant_email'], $details['tenant_prenom']);
            $mail->Subject = "Confirmation de votre visite : " . $details['annonce_titre'];
            
            $body = "
                <h2>Visite Confirmée !</h2>
                <p>Bonjour {$details['tenant_prenom']},</p>
                <p>Votre demande de visite pour l'annonce <strong>\"{$details['annonce_titre']}\"</strong> a été confirmée par le propriétaire.</p>
                <p><strong>Détails du rendez-vous :</strong></p>
                <ul>
                    <li>Date : " . date('d/m/Y', strtotime($details['date_visite'])) . "</li>
                    <li>Heure : {$details['heure_debut']} - {$details['heure_fin']}</li>
                    <li>Lieu : {$details['ville']}</li>
                </ul>
                <p>Propriétaire : {$details['owner_prenom']} {$details['owner_nom']}</p>
                <br>
                <p>Cordialement,<br>L'équipe CoLocation</p>
            ";
            
            $mail->isHTML(true);
            $mail->Body = $body;
            $mail->send();
            
            // Clear and send to Owner
            $mail->clearAddresses();
            $mail->addAddress($details['owner_email'], $details['owner_prenom']);
            $mail->Subject = "Visite confirmée pour votre annonce : " . $details['annonce_titre'];
            $mail->Body = str_replace("Bonjour {$details['tenant_prenom']}", "Bonjour {$details['owner_prenom']}", $body);
            $mail->send();
            
            return true;
        } catch (Exception $e) {
            error_log("Visit Mail Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    public static function sendVisitReminder(array $details) {
        $mail = new PHPMailer(true);
        try {
            self::configureSMTP($mail);
            
            // Send to Tenant
            $mail->addAddress($details['tenant_email'], $details['tenant_prenom']);
            $mail->Subject = "Rappel : Votre visite demain pour " . $details['annonce_titre'];
            
            $body = "
                <h2>Rappel de Visite</h2>
                <p>Bonjour {$details['tenant_prenom']},</p>
                <p>Ceci est un rappel pour votre visite prévue demain pour l'annonce <strong>\"{$details['annonce_titre']}\"</strong>.</p>
                <p><strong>Détails du rendez-vous :</strong></p>
                <ul>
                    <li>Date : " . date('d/m/Y', strtotime($details['date_visite'])) . "</li>
                    <li>Heure : {$details['heure_debut']} - {$details['heure_fin']}</li>
                    <li>Lieu : {$details['ville']}</li>
                </ul>
                <p>Propriétaire : {$details['owner_prenom']} {$details['owner_nom']}</p>
                <br>
                <p>À demain !<br>L'équipe CoLocation</p>
            ";
            
            $mail->isHTML(true);
            $mail->Body = $body;
            $mail->send();
            
            // Clear and send to Owner
            $mail->clearAddresses();
            $mail->addAddress($details['owner_email'], $details['owner_prenom']);
            $mail->Subject = "Rappel : Visite prévue demain pour votre annonce : " . $details['annonce_titre'];
            $mail->Body = str_replace("Bonjour {$details['tenant_prenom']}", "Bonjour {$details['owner_prenom']}", $body);
            $mail->send();
            
            return true;
        } catch (Exception $e) {
            error_log("Reminder Mail Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
