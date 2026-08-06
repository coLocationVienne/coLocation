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
$pass = getenv("MYSQL_PASSWORD") ?: ($_ENV['MYSQL_PASSWORD'] ?? "root_password");

if ($db === 'colocation_test') {
    $max_retries = 10;
    $retry_count = 0;
    $connected = false;
    $pdo = null;

    // Retry loop for the initial connection
    while (!$connected && $retry_count < $max_retries) {
        try {
            // Connect to MySQL without specifying a database name first
            $dsn = "mysql:host=$host;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            
            $pdo = new PDO($dsn, $user, $pass, $options);
            $connected = true;
        } catch (Exception $e) {
            $retry_count++;
            if ($retry_count >= $max_retries) {
                error_log("Test bootstrap error: Could not connect to MySQL after $max_retries attempts. Error: " . $e->getMessage());
                exit(1); // Exit with error to stop PHPUnit if DB is required
            }
            error_log("Test bootstrap: Connection failed, retrying ($retry_count/$max_retries)...");
            sleep(2);
        }
    }

    try {
        // Create the test database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `colocation_test` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        $pdo->exec("USE `colocation_test`");
        
        // Check if tables exist by checking for 'annonce_photo' which was missing in CI
        $stmt = $pdo->query("SHOW TABLES LIKE 'annonce_photo'");
        if (!$stmt->fetch()) {
            error_log("Test bootstrap: 'annonce_photo' table not found, initializing database...");
            $sqlFile = __DIR__ . '/../SQL/colocation.sql';
            if (file_exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                
                // Remove comments
                $sql = preg_replace('/--.*$/m', '', $sql);
                $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
                
                // Remove USE and CREATE DATABASE statements to avoid switching to production DB
                $sql = preg_replace('/^USE\s+.*;/mi', '', $sql);
                $sql = preg_replace('/^CREATE\s+DATABASE\s+.*;/mi', '', $sql);
                
                // Split by semicolon followed by newline
                $statements = preg_split("/;\s*$/m", $sql);
                $statements = array_filter(array_map('trim', $statements));
                
                foreach ($statements as $query) {
                    if (!empty($query)) {
                        try {
                            $pdo->exec($query);
                        } catch (Exception $e) {
                            // Log warning but continue
                            error_log("Test bootstrap warning: Failed to execute query: " . substr($query, 0, 50) . "... Error: " . $e->getMessage());
                        }
                    }
                }
                error_log("Test bootstrap: Database 'colocation_test' initialized successfully.");
            } else {
                error_log("Test bootstrap error: SQL file not found at $sqlFile");
                exit(1);
            }
        }
    } catch (Exception $e) {
        error_log("Test bootstrap error during initialization: " . $e->getMessage());
        exit(1);
    }
}
