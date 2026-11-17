<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\CommentDAO;
use App\Models\Comment;

class CommentDAOTest extends TestCase {
    private $dbMock;
    private $stmtMock;
    private $dao;

    protected function setUp(): void {
        // Mock de PDO et PDOStatement
        $this->dbMock = $this->createMock(\PDO::class);
        $this->stmtMock = $this->createMock(\PDOStatement::class);
        
        // Création du DAO sans appeler le constructeur pour éviter la connexion DB réelle
        $this->dao = $this->getMockBuilder(CommentDAO::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
        
        // Injection du mock de base de données via réflexion
        $reflection = new \ReflectionClass(CommentDAO::class);
        
        $dbProp = $reflection->getProperty('db');
        $dbProp->setAccessible(true);
        $dbProp->setValue($this->dao, $this->dbMock);

        // Initialisation des propriétés protégées de la classe parente DAO
        $tableProp = $reflection->getProperty('table_name');
        $tableProp->setAccessible(true);
        $tableProp->setValue($this->dao, 'annonce_avis');

        $pkProp = $reflection->getProperty('primary_key');
        $pkProp->setAccessible(true);
        $pkProp->setValue($this->dao, 'id_avis');
    }

    /**
     * Test unitaire : Vérifier la récupération des commentaires par ID d'annonce (getByAnnonceId).
     */
    public function testGetByAnnonceId(): void {
        $annonceId = 10;
        $fakeData = [
            [
                'id_avis' => 1,
                'note' => 4.5,
                'date_' => '2026-08-01',
                'commentaire' => 'Superbe appartement !',
                'id_annonce' => 10,
                'id_utilisateur' => 2,
                'id_utilisateur_1' => 1,
                'author_name' => 'Alice Martin'
            ]
        ];

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with(['id' => $annonceId]);

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($fakeData);

        $results = $this->dao->getByAnnonceId($annonceId);

        $this->assertCount(1, $results);
        $this->assertInstanceOf(Comment::class, $results[0]);
        $this->assertEquals(1, $results[0]->getId());
        $this->assertEquals(4.5, $results[0]->getNote());
        $this->assertEquals('Superbe appartement !', $results[0]->getCommentaire());
        $this->assertEquals('Alice Martin', $results[0]->getAuthorName());
    }

    /**
     * Test unitaire : Vérifier l'enregistrement d'un commentaire (save).
     */
    public function testSaveComment(): void {
        $data = [
            'id_avis' => null,
            'note' => 5,
            'date_' => '2026-08-05',
            'commentaire' => 'Excellent !',
            'id_annonce' => 12,
            'id_utilisateur' => 5,
            'id_utilisateur_1' => 1
        ];
        $comment = new Comment($data);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->dao->save($comment);

        $this->assertTrue($result, "L'enregistrement du commentaire doit retourner true.");
    }
}
