<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('COLOCATION_INIT_LOADED')) {
    define('COLOCATION_INIT_LOADED', true);

    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/app/Core/Autoloader.php';

    // // Load environment variables
    // if (file_exists(__DIR__ . '/.env')) {
    //     $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    //     $dotenv->load();
    // }

    try {
        // Classes are now auto-loaded via App\Core\Autoloader
        $GLOBALS['userDAO'] = new \App\Models\UserDAO();
        $GLOBALS['messageDAO'] = new \App\Models\MessageDAO();
        $GLOBALS['annonceDAO'] = new \App\Models\AnnonceDAO();
        $GLOBALS['commentDAO'] = new \App\Models\CommentDAO();
        $GLOBALS['visitSlotDAO'] = new \App\Models\VisitSlotDAO();
        $GLOBALS['visitDAO'] = new \App\Models\VisitDAO();
        $GLOBALS['listeFavorisDAO'] = new \App\Models\ListeFavorisDAO();
        $GLOBALS['listeFavorisAnnonceDAO'] = new \App\Models\ListeFavorisAnnonceDAO();
        
        $userDAO = $GLOBALS['userDAO'];
        $messageDAO = $GLOBALS['messageDAO'];
        $annonceDAO = $GLOBALS['annonceDAO'];
        $commentDAO = $GLOBALS['commentDAO'];
        $visitSlotDAO = $GLOBALS['visitSlotDAO'];
        $visitDAO = $GLOBALS['visitDAO'];
        $listeFavorisDAO = $GLOBALS['listeFavorisDAO'];
        $listeFavorisAnnonceDAO = $GLOBALS['listeFavorisAnnonceDAO'];

        // Update last activity for logged in user
        if (!empty($_SESSION['isLoggedin']) && !empty($_SESSION['user_id'])) {
            $userDAO->updateLastActivity((int)$_SESSION['user_id']);
        }
        
    } catch (Exception $e) {
        error_log("Initialization error: " . $e->getMessage());
    }
} else {
    $userDAO = $GLOBALS['userDAO'] ?? null;
    $messageDAO = $GLOBALS['messageDAO'] ?? null;
    $annonceDAO = $GLOBALS['annonceDAO'] ?? null;
    $commentDAO = $GLOBALS['commentDAO'] ?? null;
    $visitSlotDAO = $GLOBALS['visitSlotDAO'] ?? null;
    $visitDAO = $GLOBALS['visitDAO'] ?? null;
    $listeFavorisDAO = $GLOBALS['listeFavorisDAO'] ?? null;
    $listeFavorisAnnonceDAO = $GLOBALS['listeFavorisAnnonceDAO'] ?? null;
}
