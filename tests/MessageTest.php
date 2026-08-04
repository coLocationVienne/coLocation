<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\Message;
use App\Models\MessageDAO;

class MessageTest extends TestCase {
    
    private $pdo;
    private $pdoStatement;
    private $dao;

    protected function setUp(): void {
        // Mock PDO and PDOStatement
        $this->pdo = $this->createMock(\PDO::class);
        $this->pdoStatement = $this->createMock(\PDOStatement::class);
        
        // MessageDAO needs a Database instance which is a singleton.
        // For testing, we'll mock the DAO behavior or use a test database.
        // However, the user's MessageDAO extends DAO which gets connection from Database::getInstance().
        // To keep it simple for this fix, I'll mock the MessageDAO itself if needed, 
        // but the error was "Class name must be a valid object or a string" at line 16.
        // The issue was: new $this->createMock(...) which is invalid syntax.
    }

    public function testConstructorAndGetters(): void {
        $MsgData = [
            'id_utilisateur' => 1,
            'id_utilisateur_1' => 2,
            'id_annonce' => 10,
            'contenu' => 'Hello, this is a test message.',
            'date_' => '2024-06-01 12:00:00'
        ];
        
        $message = new Message($MsgData);

        $this->assertEquals(1, $message->getSenderId());
        $this->assertEquals(2, $message->getReceiverId());
        $this->assertEquals(10, $message->getAnnonceId());
        $this->assertEquals('Hello, this is a test message.', $message->getContenu());
        $this->assertEquals('2024-06-01 12:00:00', $message->getDate());
    }
}
