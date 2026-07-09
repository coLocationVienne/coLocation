<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/DAO.class.php';
require_once __DIR__ . '/classes/User.class.php';
require_once __DIR__ . '/classes/Message.class.php';
require_once __DIR__ . '/classes/Annonce.class.php';

use colocation\UserDAO;
use colocation\MessageDAO;
use colocation\AnnonceDAO;

try {
    $userDAO = new UserDAO();
    $messageDAO = new MessageDAO();
    $annonceDAO = new AnnonceDAO();
} catch (Exception $e) {
    error_log("Initialization error: " . $e->getMessage());
}
