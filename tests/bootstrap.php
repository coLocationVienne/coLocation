<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/Autoloader.php';

// Define any constants needed for tests
if (!defined('COLOCATION_INIT_LOADED')) {
    define('COLOCATION_INIT_LOADED', true);
}

// Automatically create test database if it doesn't exist
// We check both getenv() and $_ENV to be sure
$host = getenv("MYSQL_HOST") ?: ($_ENV['MYSQL_HOST'] ?? "localhost");
$db = getenv("MYSQL_DATABASE") ?: ($_ENV['MYSQL_DATABASE'] ?? "colocation_test");
$user = getenv("MYSQL_USER") ?: ($_ENV['MYSQL_USER'] ?? "root");
$pass = getenv("MYSQL_PASSWORD") ?: ($_ENV['MYSQL_PASSWORD'] ?? "");

if ($db === 'colocation_test') {
    try {
        // Connect to MySQL without specifying a database name first
        $dsn = "mysql:host=$host;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        
        $pdo = new PDO($dsn, $user, $pass, $options);
        
        // Create the test database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `colocation_test` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        $pdo->exec("USE `colocation_test`");
        
        // Check if tables exist by checking for one core table
        $stmt = $pdo->query("SHOW TABLES LIKE 'annonce'");
        if (!$stmt->fetch()) {
            $sqlFile = __DIR__ . '/../SQL/colocation.sql';
            if (file_exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                
                // Remove comments and split into individual statements
                $sql = preg_replace('/--.*$/m', '', $sql);
                $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
                
                // More robust splitting for standard SQL files
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                
                foreach ($statements as $query) {
                    if (!empty($query)) {
                        try {
                            $pdo->exec($query);
                        } catch (Exception $e) {
                            // Some statements might fail if they are specific to a user or environment
                            // We log them but continue
                            error_log("Test bootstrap warning: Failed to execute query: " . substr($query, 0, 50) . "... Error: " . $e->getMessage());
                        }
                    }
                }
            }
        }
    } catch (Exception $e) {
        // Log the error so it can be seen in CI logs
        error_log("Test bootstrap error: " . $e->getMessage());
        // Do not throw here, as it might block tests that don't need the DB
        // But the integration tests will fail later with a clear connection error
    }
}
