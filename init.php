<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('COLOCATION_INIT_LOADED')) {
    define('COLOCATION_INIT_LOADED', true);

    require_once __DIR__ . '/app/Core/Autoloader.php';

    try {
        // Classes are now auto-loaded via App\Core\Autoloader
        $GLOBALS['userDAO'] = new \App\Models\UserDAO();
        $GLOBALS['messageDAO'] = new \App\Models\MessageDAO();
        $GLOBALS['annonceDAO'] = new \App\Models\AnnonceDAO();
        $GLOBALS['commentDAO'] = new \App\Models\CommentDAO();
        
        $userDAO = $GLOBALS['userDAO'];
        $messageDAO = $GLOBALS['messageDAO'];
        $annonceDAO = $GLOBALS['annonceDAO'];
        $commentDAO = $GLOBALS['commentDAO'];
        
    } catch (Exception $e) {
        error_log("Initialization error: " . $e->getMessage());
    }
} else {
    $userDAO = $GLOBALS['userDAO'] ?? null;
    $messageDAO = $GLOBALS['messageDAO'] ?? null;
    $annonceDAO = $GLOBALS['annonceDAO'] ?? null;
    $commentDAO = $GLOBALS['commentDAO'] ?? null;
}
