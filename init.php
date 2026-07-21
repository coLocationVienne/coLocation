<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/app/Core/Autoloader.php';
require_once __DIR__ . '/app/Models/User.php';
require_once __DIR__ . '/app/Models/Message.php';
require_once __DIR__ . '/app/Models/Annonce.php';
require_once __DIR__ . '/app/Models/Comment.php';

use App\Models\UserDAO;
use App\Models\MessageDAO;
use App\Models\AnnonceDAO;
use App\Models\CommentDAO;

try {
    $userDAO = new UserDAO();
    $messageDAO = new MessageDAO();
    $annonceDAO = new AnnonceDAO();
    $commentDAO = new CommentDAO();
} catch (Exception $e) {
    error_log("Initialization error: " . $e->getMessage());
}
