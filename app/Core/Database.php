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
        $this->host = "localhost";
        $this->db_name = "colocation";
        $this->username = "root";
        $this->password = "";

        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            throw new \RuntimeException("Connection error: " . $exception->getMessage());
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
