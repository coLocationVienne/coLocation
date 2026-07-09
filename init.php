<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/DAO.class.php';
require_once __DIR__ . '/classes/User.class.php';
require_once __DIR__ . '/classes/annonce.class.php';

use colocation\UserDAO;
use colocation\MessageDAO;

try {
    $userDAO = new UserDAO();
    $messageDAO = new MessageDAO();
} catch (Exception $e) {

    error_log("Initialization error: " . $e->getMessage());
}
