<?php 

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    private ?PDO $conn = null;

    private function __construct() {
        $this->host = getenv("MYSQL_HOST") ?: "localhost";
        $this->db_name = getenv("MYSQL_DATABASE") ?: "colocation";
        $this->username = getenv("MYSQL_USER") ?: "root";
        $this->password = getenv("MYSQL_PASSWORD") ?: "";

        $max_retries = 5;
        $retry_count = 0;
        $connected = false;

        while (!$connected && $retry_count < $max_retries) {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $connected = true;
            } catch (PDOException $exception) {
                $retry_count++;
                if ($retry_count >= $max_retries) {
                    throw new \RuntimeException("Connection error (Host: {$this->host}): " . $exception->getMessage());
                }
                sleep(2);
            }
        }
    }

    public static function getInstance(): Database {
        static $instance = null;
        if ($instance === null) {
            $instance = new Database();
        }
        return $instance;
    }

    public function getConnection(): ?PDO {
        return $this->conn;
    }

    /**
     * Compatibility helper for legacy code
     */
    public static function getDbConnection(): ?PDO {
        return self::getInstance()->getConnection();
    }

    private function __clone() {}
    public function __wakeup() { throw new \Exception("Cannot unserialize a singleton."); }
}
