<?php

require_once __DIR__ . '/init.php';

use colocation\Message;
use colocation\MessageDAO;

echo "--- Starting Messaging Test ---\n";

try {
    $messageDAO = new MessageDAO();

    // 1. Test sending a message
    echo "[1/3] Sending a test message... ";
    $testMsg = new Message([
        'id_utilisateur' => 3, // Thomas
        'id_utilisateur_1' => 2, // Sophie (Owner of Annonce 1)
        'id_annonce' => 1,
        'date_' => date('Y-m-d'),
        'contenu' => 'Bonjour, je suis très intéressé par votre colocation !'
    ]);

    if ($messageDAO->save($testMsg)) {
        echo "SUCCESS\n";
    } else {
        echo "FAILED\n";
    }

    // 2. Test fetching conversation
    echo "[2/3] Fetching conversation... ";
    $conversation = $messageDAO->getConversation(3, 2, 1);
    echo "Found " . count($conversation) . " messages.\n";
    foreach ($conversation as $msg) {
        echo " - [" . $msg->getDate() . "] From " . $msg->getSenderId() . ": " . $msg->getContenu() . "\n";
    }

    // 3. Test unread count
    echo "[3/3] Checking message count for User 2... ";
    $count = $messageDAO->countUnread(2);
    echo "Total received: $count\n";

    echo "\n--- Messaging tests completed! ---\n";

} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
}
