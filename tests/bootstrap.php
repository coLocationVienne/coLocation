<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/Autoloader.php';

// Define any constants needed for tests
if (!defined('COLOCATION_INIT_LOADED')) {
    define('COLOCATION_INIT_LOADED', true);
}

// Automatically create test database if it doesn't exist
$host = getenv("MYSQL_HOST") ?: "localhost";
$db = getenv("MYSQL_DATABASE");
$user = getenv("MYSQL_USER") ?: "root";
$pass = getenv("MYSQL_PASSWORD") ?: "";

if ($db === 'colocation_test') {
    try {
        $pdo = new PDO("mysql:host=$host", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS colocation_test");
        $pdo->exec("USE colocation_test");
        
        // Check if tables exist
        $stmt = $pdo->query("SHOW TABLES LIKE 'annonce'");
        if (!$stmt->fetch()) {
            $sql = file_get_contents(__DIR__ . '/../SQL/colocation.sql');
            
            // Remove comments
            $sql = preg_replace('/--.*$/m', '', $sql);
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
            
            // Split by semicolon, but be careful with escaped semicolons or those inside strings
            // For simplicity, we'll use a regex that matches semicolons at the end of lines
            $statements = preg_split('/;[ \t]*\r?\n/', $sql);
            
            foreach ($statements as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    try {
                        $pdo->exec($query);
                    } catch (Exception $e) {
                        // Ignore errors for individual statements
                    }
                }
            }
        }
    } catch (Exception $e) {
        // If we are in a test environment, we might not have 'mysql' client
        // But we should have PDO.
    }
}
