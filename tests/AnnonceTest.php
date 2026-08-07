<?php
namespace App\Tests;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Group;
use App\Models\Annonce;
use App\Models\AnnonceDAO;
use App\Core\Database;

#[Group('integration')]
#[Group('database')]
class AnnonceTest extends TestCase {
    private AnnonceDAO $annonceDAO;
    private \PDO $pdo;

    protected function setUp(): void {
        parent::setUp();
        // Use a test database connection
        $this->pdo = Database::getInstance()->getConnection();
        $this->annonceDAO = new AnnonceDAO();

        // Disable foreign key checks to clear tables
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $this->pdo->exec("DELETE FROM annonce_photo");
        $this->pdo->exec("DELETE FROM annonce_utilisateur");
        $this->pdo->exec("DELETE FROM annonce_mode_vie");
        $this->pdo->exec("DELETE FROM annonce_regime_alimentaire");
        $this->pdo->exec("DELETE FROM annonce_age");
        $this->pdo->exec("DELETE FROM photo");
        $this->pdo->exec("DELETE FROM annonce");
        $this->pdo->exec("DELETE FROM utilisateur");
        $this->pdo->exec("DELETE FROM role");
        $this->pdo->exec("DELETE FROM mode_vie");
        $this->pdo->exec("DELETE FROM regime_alimentaire");
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

        // Insert dummy data for foreign key constraints and basic tests
        $this->pdo->exec("INSERT INTO role (id_role, role) VALUES (1, 'admin'), (2, 'owner'), (3, 'colocataire')");
        $this->pdo->exec("INSERT INTO utilisateur (id_utilisateur, prenom, nom, email, mot_de_passe, situation_professionnel, garant, retraite, caisse_allocation_familial, date_naissance, photo_profil, salaire_mensuel_net, revenu_fiscal, id_role, type_compte) VALUES (1, 'Test', 'User', 'test@example.com', 'password_hash', 'student', 1, 0, 0, '2000-01-01', '', 0, 0, 3, 'colocataire')");
        $this->pdo->exec("INSERT INTO mode_vie (id_mode_vie, mode_avis) VALUES (1, 'Calme'), (2, 'Fêtard')");
        $this->pdo->exec("INSERT INTO regime_alimentaire (id_regime_alimentaire, regime_alimentaire) VALUES (1, 'Végétarien'), (2, 'Vegan')");
    }

    public function testConstructorAndGetters(): void {
        $data = [
            'id_annonce' => 1,
            'titre' => 'Appartement à louer',
            'adresse_1' => '1 Rue de la Paix',
            'ville' => 'Paris',
            'code_postal' => 75001,
            'loyer_location_chez_habitant' => 800.0,
            'description' => 'Description de l\'appartement',
            'surface_logement' => 50.0,
            'surface_chambres' => 15.0,
            'nombre_chambre' => 2,
            'date_publication' => '2023-01-01',
            'loyer_colocation' => 400.0
        ];
        $annonce = new Annonce($data);

        $this->assertEquals(1, $annonce->getId());
        $this->assertEquals('Appartement à louer', $annonce->getTitre());
        $this->assertEquals('1 Rue de la Paix', $annonce->getAdresse1());
        $this->assertEquals('Paris', $annonce->getVille());
        $this->assertEquals(75001, $annonce->getCodePostal());
        $this->assertEquals(800.0, $annonce->getLoyerHabitant());
        $this->assertEquals('Description de l\'appartement', $annonce->getDescription());
        $this->assertEquals(50.0, $annonce->getSurfaceLogement());
        $this->assertEquals(15.0, $annonce->getSurfaceChambres());
        $this->assertEquals(2, $annonce->getNombreChambre());
        $this->assertEquals('2023-01-01', $annonce->getDatePublication());
        $this->assertEquals(400.0, $annonce->getLoyerColoc());
        $this->assertEquals(400.0, $annonce->getLoyer());
    }

    public function testSetters(): void {
        $annonce = new Annonce([]);
        $annonce->setTitre('Nouveau Titre');
        $this->assertEquals('Nouveau Titre', $annonce->getTitre());
    }

    public function testSaveAndGetById(): void {
        $data = [
            'titre' => 'Annonce Test Save',
            'adresse_1' => '2 Rue de la Paix',
            'ville' => 'Lyon',
            'code_postal' => 69001,
            'loyer_location_chez_habitant' => 700.0,
            'description' => 'Description test',
            'surface_logement' => 40.0,
            'surface_chambres' => 12.0,
            'nombre_chambre' => 1,
            'date_publication' => '2023-01-02',
            'loyer_colocation' => 350.0
        ];
        $annonce = new Annonce($data);
        $this->annonceDAO->save($annonce);

        $savedAnnonce = $this->annonceDAO->getById($this->pdo->lastInsertId());
        $this->assertNotNull($savedAnnonce);
        $this->assertEquals('Annonce Test Save', $savedAnnonce->getTitre());
    }

    public function testUpdate(): void {
        $data = [
            'titre' => 'Annonce Original',
            'adresse_1' => '3 Rue de la Paix',
            'ville' => 'Marseille',
            'code_postal' => 13001,
            'loyer_location_chez_habitant' => 900.0,
            'description' => 'Original description',
            'surface_logement' => 60.0,
            'surface_chambres' => 20.0,
            'nombre_chambre' => 3,
            'date_publication' => '2023-01-03',
            'loyer_colocation' => 300.0
        ];
        $annonce = new Annonce($data);
        $this->annonceDAO->save($annonce);
        $id = $this->pdo->lastInsertId();

        $annonce->setTitre('Annonce Updated');
        $annonce->setId($id);

        $this->assertTrue($this->annonceDAO->update($annonce));

        $fetchedAnnonce = $this->annonceDAO->getById($id);
        $this->assertEquals('Annonce Updated', $fetchedAnnonce->getTitre());}

    public function testDelete(): void {
        $data = [
            'titre' => 'Annonce to Delete',
            'adresse_1' => '4 Rue de la Paix',
            'ville' => 'Nice',
            'code_postal' => 06000,
            'loyer_location_chez_habitant' => 600.0,
            'description' => 'Delete me',
            'surface_logement' => 30.0,
            'surface_chambres' => 10.0,
            'nombre_chambre' => 1,
            'date_publication' => '2023-01-04',
            'loyer_colocation' => 300.0
        ];
        $annonce = new Annonce($data);
        $this->annonceDAO->save($annonce);
        $id = $this->pdo->lastInsertId();

        $this->assertTrue($this->annonceDAO->delete($id));
        $this->assertNull($this->annonceDAO->getById($id));
    }

    public function testGetAllWithPhotos(): void {
        // Add an annonce with a photo
        $data1 = [
            'titre' => 'Annonce with Photo',
            'adresse_1' => '5 Rue de la Paix',
            'ville' => 'Bordeaux',
            'code_postal' => 33000,
            'loyer_location_chez_habitant' => 1000.0,
            'description' => 'Has a photo',
            'surface_logement' => 70.0,
            'surface_chambres' => 20.0,
            'nombre_chambre' => 2,
            'date_publication' => '2023-01-05',
            'loyer_colocation' => 500.0
        ];
        $annonce1 = new Annonce($data1);
        $id1 = $this->annonceDAO->ajouterAnnonce($annonce1, 1, [], []);
        $this->annonceDAO->addPhoto($id1, 'http://example.com/photo1.jpg');

        // Add an annonce without a photo
        $data2 = [
            'titre' => 'Annonce without Photo',
            'adresse_1' => '6 Rue de la Paix',
            'ville' => 'Toulouse',
            'code_postal' => 31000,
            'loyer_location_chez_habitant' => 900.0,
            'description' => 'No photo',
            'surface_logement' => 60.0,
            'surface_chambres' => 18.0,
            'nombre_chambre' => 2,
            'date_publication' => '2023-01-06',
            'loyer_colocation' => 450.0
        ];
        $annonce2 = new Annonce($data2);
        $id2 = $this->annonceDAO->ajouterAnnonce($annonce2, 1, [], []);

        $annonces = $this->annonceDAO->getAllWithPhotos();
        $this->assertCount(2, $annonces);

        $foundPhotoAnnonce = false;
        $foundNoPhotoAnnonce = false;

        foreach ($annonces as $annonce) {
            if ($annonce->getId() == $id1) {
                $this->assertEquals('http://example.com/photo1.jpg', $annonce->getPhoto());
                $this->assertEquals(1, $annonce->getOwnerId());
                $foundPhotoAnnonce = true;
            } elseif ($annonce->getId() == $id2) {
                $this->assertNull($annonce->getPhoto());
                $this->assertEquals(1, $annonce->getOwnerId());
                $foundNoPhotoAnnonce = true;
            }
        }
        $this->assertTrue($foundPhotoAnnonce);
        $this->assertTrue($foundNoPhotoAnnonce);
    }

    public function testGetFullById(): void {
        $data = [
            'titre' => 'Full Annonce Test',
            'adresse_1' => '7 Rue de la Paix',
            'ville' => 'Nantes',
            'code_postal' => 44000,
            'loyer_location_chez_habitant' => 1100.0,
            'description' => 'Full details',
            'surface_logement' => 80.0,
            'surface_chambres' => 25.0,
            'nombre_chambre' => 3,
            'date_publication' => '2023-01-07',
            'loyer_colocation' => 550.0
        ];
        $annonce = new Annonce($data);
        $id = $this->annonceDAO->ajouterAnnonce($annonce, 1, [], []);
        $this->annonceDAO->addPhoto($id, 'http://example.com/full_photo.jpg');

        $fullAnnonce = $this->annonceDAO->getFullById($id);
        $this->assertNotNull($fullAnnonce);
        $this->assertEquals('Full Annonce Test', $fullAnnonce->getTitre());
        $this->assertEquals('http://example.com/full_photo.jpg', $fullAnnonce->getPhoto());
        $this->assertEquals(1, $fullAnnonce->getOwnerId());
    }

    public function testGetAllAnouncesByUserId(): void {
        $data1 = [
            'titre' => 'User 1 Annonce 1',
            'adresse_1' => '8 Rue de la Paix',
            'ville' => 'Strasbourg',
            'code_postal' => 67000,
            'loyer_location_chez_habitant' => 750.0,
            'description' => 'User 1 annonce',
            'surface_logement' => 45.0,
            'surface_chambres' => 14.0,
            'nombre_chambre' => 1,
            'date_publication' => '2023-01-08',
            'loyer_colocation' => 375.0
        ];
        $annonce1 = new Annonce($data1);
        $this->annonceDAO->ajouterAnnonce($annonce1, 1, [], []);

        $data2 = [
            'titre' => 'User 1 Annonce 2',
            'adresse_1' => '9 Rue de la Paix',
            'ville' => 'Rennes',
            'code_postal' => 35000,
            'loyer_location_chez_habitant' => 850.0,
            'description' => 'Another User 1 annonce',
            'surface_logement' => 55.0,
            'surface_chambres' => 16.0,
            'nombre_chambre' => 2,
            'date_publication' => '2023-01-09',
            'loyer_colocation' => 425.0
        ];
        $annonce2 = new Annonce($data2);
        $this->annonceDAO->ajouterAnnonce($annonce2, 1, [], []);

        // Create another user
        $this->pdo->exec("INSERT INTO utilisateur (id_utilisateur, prenom, nom, email, mot_de_passe, situation_professionnel, garant, retraite, caisse_allocation_familial, date_naissance, photo_profil, salaire_mensuel_net, revenu_fiscal, id_role, type_compte) VALUES (2, 'Second', 'User', 'second@example.com', 'password_hash', 'student', 1, 0, 0, '2000-01-01', '', 0, 0, 3, 'colocataire')");
        $data3 = [
            'titre' => 'User 2 Annonce 1',
            'adresse_1' => '10 Rue de la Paix',
            'ville' => 'Lille',
            'code_postal' => 59000,
            'loyer_location_chez_habitant' => 700.0,
            'description' => 'User 2 annonce',
            'surface_logement' => 40.0,
            'surface_chambres' => 13.0,
            'nombre_chambre' => 1,
            'date_publication' => '2023-01-10',
            'loyer_colocation' => 350.0
        ];
        $annonce3 = new Annonce($data3);
        $this->annonceDAO->ajouterAnnonce($annonce3, 2, [], []);

        $user1Annonces = $this->annonceDAO->getAllAnouncesByUserId(1);
        $this->assertCount(2, $user1Annonces);
        $this->assertEquals('User 1 Annonce 2', $user1Annonces[0]->getTitre()); // Ordered by date_publication DESC
        $this->assertEquals('User 1 Annonce 1', $user1Annonces[1]->getTitre());

        $user2Annonces = $this->annonceDAO->getAllAnouncesByUserId(2);
        $this->assertCount(1, $user2Annonces);
        $this->assertEquals('User 2 Annonce 1', $user2Annonces[0]->getTitre());
    }

    public function testAddAndDeletePhoto(): void {
        $data = [
            'titre' => 'Photo Test Annonce',
            'adresse_1' => '11 Rue de la Paix',
            'ville' => 'Grenoble',
            'code_postal' => 38000,
            'loyer_location_chez_habitant' => 800.0,
            'description' => 'Photo test',
            'surface_logement' => 50.0,
            'surface_chambres' => 15.0,
            'nombre_chambre' => 2,
            'date_publication' => '2023-01-11',
            'loyer_colocation' => 400.0
        ];
        $annonce = new Annonce($data);
        $idAnnonce = $this->annonceDAO->ajouterAnnonce($annonce, 1, [], []);

        $this->assertTrue($this->annonceDAO->addPhoto($idAnnonce, 'http://example.com/test_photo.jpg'));
        $photos = $this->annonceDAO->getPhotos($idAnnonce);
        $this->assertCount(1, $photos);
        $this->assertEquals('http://example.com/test_photo.jpg', $photos[0]['url']);

        $idPhoto = $photos[0]['id_photo'];
        $this->assertTrue($this->annonceDAO->deletePhoto($idPhoto));
        $photosAfterDelete = $this->annonceDAO->getPhotos($idAnnonce);
        $this->assertCount(0, $photosAfterDelete);
    }

    public function testAppartientAUtilisateur(): void {
        $data = [
            'titre' => 'Ownership Test',
            'loyer_location_chez_habitant' => 500.0
        ];
        $annonce = new Annonce($data);
        $idAnnonce = $this->annonceDAO->ajouterAnnonce($annonce, 1, [], []);

        $this->assertTrue($this->annonceDAO->appartientAUtilisateur($idAnnonce, 1));
        $this->assertFalse($this->annonceDAO->appartientAUtilisateur($idAnnonce, 2));
    }
}
