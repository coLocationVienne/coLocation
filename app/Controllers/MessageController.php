<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Message;

class MessageController extends Controller {
    public function send() {
        global $messageDAO;
        
        if (empty($_SESSION['isLoggedin'])) {
            header("Location: /coLocation/auth/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_annonce = (int)($_POST['id_annonce'] ?? 0);
            $id_receiver = (int)($_POST['id_receiver'] ?? 0);
            $content = trim($_POST['message'] ?? '');

            if ($id_annonce > 0 && $id_receiver > 0 && !empty($content)) {
                $messageData = [
                    'contenu' => $content,
                    'date_' => date('Y-m-d H:i:s'),
                    'id_utilisateur' => $_SESSION['user_id'], // sender
                    'id_utilisateur_1' => $id_receiver, // receiver
                    'id_annonce' => $id_annonce
                ];

                $message = new Message($messageData);
                if ($messageDAO->save($message)) {
                    header("Location: /coLocation/pages/messages.php?annonce_id=$id_annonce&with_user=$id_receiver");
                    exit();
                }
            }
        }
        
        header("Location: /coLocation/pages/messages.php?error=send_failed");
        exit();
    }
}
