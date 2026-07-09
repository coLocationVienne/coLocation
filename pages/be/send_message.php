<?php

require_once "../../init.php";

if (empty($_SESSION['isLoggedin']) || empty($_POST['message'])) {
    header("Location: ../messages.php");
    exit();
}

$messageData = [
    'id_utilisateur' => (int)$_SESSION['user_id'],
    'id_utilisateur_1' => (int)$_POST['id_receiver'],
    'id_annonce' => (int)$_POST['id_annonce'],
    'date_' => date('Y-m-d H:i:s'),
    'contenu' => trim(htmlspecialchars($_POST['message']))
];

$msg = new \colocation\Message($messageData);

if ($messageDAO->save($msg)) {
    header("Location: ../messages.php?annonce_id=" . $messageData['id_annonce'] . "&with_user=" . $messageData['id_utilisateur_1']);
} else {
    header("Location: ../messages.php?error=send_failed");
}
exit();
