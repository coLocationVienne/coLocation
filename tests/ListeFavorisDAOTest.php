<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\ListeFavorisDAO;
use App\Models\ListeFavoris;

class ListeFavorisDAOTest extends TestCase {
    private $dbMock;
    private $stmtMock;
    private $dao;

    protected function setUp(): void {
        // Mock de PDO et PDOStatement
        $this->dbMock = $this->createMock(\PDO::class);
        $this->stmtMock = $this->createMock(\PDOStatement::class);
        
        // Création du DAO
        $this->dao = new ListeFavorisDAO();
        
        // Injection du mock de base de données via réflexion (car la propriété est protégée dans DAO.php)
        $reflection = new \ReflectionClass($this->dao);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($this->dao, $this->dbMock);
    }

    public function testGetListesByUserId(): void {
        $userId = 1;
        $fakeData = [
            ['id_listeFavoris' => 1, 'titre_liste' => 'Ma Liste', 'id_utilisateur' => 1]
        ];

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute');

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($fakeData);

        $result = $this->dao->getListesByUserId($userId);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(ListeFavoris::class, $result[0]);
        $this->assertEquals('Ma Liste', $result[0]->getTitreListe());
    }

    public function testGetListeByIdAndUserId(): void {
        $listeId = 1;
        $userId = 1;
        $fakeRow = ['id_listeFavoris' => 1, 'titre_liste' => 'Favoris', 'id_utilisateur' => 1];

        $this->dbMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('fetch')->willReturn($fakeRow);

        $result = $this->dao->getListeByIdAndUserId($listeId, $userId);

        $this->assertInstanceOf(ListeFavoris::class, $result);
        $this->assertEquals(1, $result->getIdListeFavoris());
    }
}
