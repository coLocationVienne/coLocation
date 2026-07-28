<?php 


namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\Annonce;

class AnnonceTest extends TestCase
{
    protected function setUp(): void 
    {
        $this->pdoMock = $this->createMock(\PDO::class);
         $this->stmtMock = $this->createMock(\PDOStatement::class);

         $this->dao = new AnnonceDAO($this->pdoMock);
    }

     public function testRetourneAnnonce(): void
    {
        // Ce tableau simule ce que MySQL renverrait pour un livre
        $ligneSimulee = [
            'id_annonce'=> 1,
             'titre'   => 'appartement avec balcon',
             'adresse_1'=>'50 rue aime rasseteau',
             'adresse_2'=> '',
             'adresse_3'=> '',
             'adresse_4'=> '',
             'ville' => 'chatellerault',
             'code_postal' => 86000,
             'loyer_location_chez_habitant' => 255,
             'description'=> "c'est un appartement en plein centre ville",
             'surface_logement' =>255,
             'surface_chambres' => 20,
             'nombre_chambre'=>5,
             'date_expiration' => 20/03/2044,
             'date_publication' => 14/03/2044,


        ];

        // On configure le mock :
        // - prepare() renvoie notre faux statement
        // - fetch() renvoie notre ligne simulée
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn($ligneSimulee);
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);

        $article = $this->dao->find(1);

        // On vérifie le résultat
        $this->assertInstanceOf(Livre::class, $article);
        $this->assertEquals('1984', $article->getTitre());
        $this->assertEquals('George Orwell', $article->getAuteur());
        $this->assertEquals(1949, $article->getAnnee());
        $this->assertEquals(1, $article->getId());

        // assertNotEquals : on s'assure que ce n'est PAS un Dvd
        $this->assertNotInstanceOf(Dvd::class, $article);
    }


}