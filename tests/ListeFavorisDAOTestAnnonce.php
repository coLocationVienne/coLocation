<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\ListeFavorisAnnonceDAO;
use App\Models\Annonce;

class ListeFavorisDAOTestAnnonce extends TestCase {
    private $dbMock;
    private $stmtMock;
    private $dao;

    protected function setUp(): void {
        // Mock de PDO et PDOStatement
        $this->dbMock = $this->createMock(\PDO::class);
        $this->stmtMock = $this->createMock(\PDOStatement::class);
        
        // Création du DAO sans appeler le constructeur pour éviter la connexion DB
        // On ne mocke aucune méthode pour utiliser les implémentations réelles
        $this->dao = $this->getMockBuilder(ListeFavorisAnnonceDAO::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
        
        // Injection du mock de base de données via réflexion
        $reflection = new \ReflectionClass(ListeFavorisAnnonceDAO::class);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($this->dao, $this->dbMock);
    }

    /**
     * Test unitaire 1 : Vérifier l'ajout d'une annonce à une liste de favoris (addAnnonceToListe).
     */
    public function testAddAnnonceToListe(): void {
        $listeId = 1;
        $annonceId = 10;

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo("INSERT INTO favoris (id_listeFavoris, id_annonce) VALUES (:liste_id, :annonce_id)"))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->dao->addAnnonceToListe($listeId, $annonceId);

        $this->assertTrue($result, "L'ajout de l'annonce aux favoris doit retourner true en cas de succès.");
    }

    /**
     * Test unitaire 2 : Vérifier la suppression d'une annonce d'une liste de favoris (removeAnnonceFromListe).
     */
    public function testRemoveAnnonceFromListe(): void {
        $listeId = 1;
        $annonceId = 10;

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo("DELETE FROM favoris WHERE id_listeFavoris = :liste_id AND id_annonce = :annonce_id"))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->dao->removeAnnonceFromListe($listeId, $annonceId);

        $this->assertTrue($result, "La suppression de l'annonce des favoris doit retourner true en cas de succès.");
    }

    /**
     * Test unitaire 3 : Vérifier si une annonce est déjà dans une liste (isAnnonceInListe).
     */
    public function testIsAnnonceInListe(): void {
        $listeId = 1;
        $annonceId = 10;

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute');

        // Simuler que l'annonce est présente (COUNT = 1)
        $this->stmtMock->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(1);

        $result = $this->dao->isAnnonceInListe($listeId, $annonceId);

        $this->assertTrue($result, "isAnnonceInListe doit retourner true si l'annonce est dans la liste.");
    }
}
