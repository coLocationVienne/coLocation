<?php 

namespace App\Models;

use PDO;
use App\Core\Database;

abstract class DAO {
    protected ?PDO $db;
    protected string $table_name;
    protected string $primary_key;

    public function __construct(string $table_name, string $primary_key = 'id') {
        $this->db = Database::getInstance()->getConnection();
        $this->table_name = $table_name;
        $this->primary_key = $primary_key;
    }

    /**
     * Get the PDO database connection
     */
    public function getDb(): ?PDO {
        return $this->db;
    }

    abstract protected function hydrate(array $row): object;
    abstract protected function dehydrate(object $entity): array;

    public function getById(int $id): ?object {
        $query = "SELECT * FROM " . $this->table_name . " WHERE " . $this->primary_key . " = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? $this->hydrate($result) : null;
    }

    public function save(object $entity): bool {
        $data = $this->dehydrate($entity);
        
        if (isset($data[$this->primary_key]) && $data[$this->primary_key] === null) {
            unset($data[$this->primary_key]);
        }

        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        
        $query = "INSERT INTO " . $this->table_name . " ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute($data);
    }

    public function update(object $entity): bool {
        $data = $this->dehydrate($entity);
        $id = $data[$this->primary_key];
        unset($data[$this->primary_key]);

        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $query = "UPDATE " . $this->table_name . " SET $setClause WHERE " . $this->primary_key . " = :id_val";
        
        $stmt = $this->db->prepare($query);
        
        $data['id_val'] = $id;
        
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $query = "DELETE FROM " . $this->table_name . " WHERE " . $this->primary_key . " = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getAll(): array {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->db->query($query);
        $results = $stmt->fetchAll();
        
        $entities = [];
        foreach ($results as $row) {
            $entities[] = $this->hydrate($row);
        }
        return $entities;
    }

    public function findBy(array $criteria): array {
        $whereClause = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($criteria)));
        $query = "SELECT * FROM " . $this->table_name . " WHERE $whereClause";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($criteria);
        $results = $stmt->fetchAll();
        
        $entities = [];
        foreach ($results as $row) {
            $entities[] = $this->hydrate($row);
        }
        return $entities;
    }
}
