<?php
/**
 * Application Initialization
 * This file handles session start and class loading.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Manually require the classes for simplicity and reliability
// Order matters: Database -> DAO -> Models/Specific DAOs
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/DAO.class.php';
require_once __DIR__ . '/classes/User.class.php';

use colocation\UserDAO;

// Global instances for convenience in procedural pages
try {
    $userDAO = new UserDAO();
} catch (Exception $e) {
    // Silently handle or log error if DB is not ready
    error_log("Initialization error: " . $e->getMessage());
}
