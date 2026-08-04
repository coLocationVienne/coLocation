<?php
namespace App\Tests;
use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase {
    private array $userData;

    protected function setUp(): void {
        $this->userData = [
            'id_utilisateur' => 1,
            'prenom' => 'John',
            'nom' => 'Doe',
            'email' => 'john.doe@example.com',
            'mot_de_passe' => password_hash('password123', PASSWORD_DEFAULT),
            'situation_professionnel' => 'Étudiant',
            'garant' => true,
            'retraite' => 0.0,
            'caisse_allocation_familial' => 150.50,
            'date_naissance' => '2000-01-01',
            'photo_profil' => 'profile.jpg',
            'salaire_mensuel_net' => 500.0,
            'revenu_fiscal' => 12000.0,
            'id_role' => 3,
            'type_compte' => 'colocataire'
        ];
    }

    public function testConstructorAndGetters(): void {
        $user = new User($this->userData);

        $this->assertEquals(1, $user->getIdUtilisateur());
        $this->assertEquals('John', $user->getPrenom());
        $this->assertEquals('Doe', $user->getNom());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertEquals('Étudiant', $user->getSituationProfessionnel());
        $this->assertTrue($user->isGarant());
        $this->assertEquals(150.50, $user->getCaisseAllocationFamilial());
        $this->assertEquals('2000-01-01', $user->getDateNaissance());
        $this->assertEquals('profile.jpg', $user->getPhotoProfil());
        $this->assertEquals(500.0, $user->getSalaireMensuelNet());
        $this->assertEquals(12000.0, $user->getRevenuFiscal());
        $this->assertEquals(3, $user->getIdRole());
        $this->assertEquals('colocataire', $user->getTypeCompte());
    }

    public function testSetters(): void {
        $user = new User([]);

        $user->setPrenom('Jane');
        $user->setNom('Smith');
        $user->setEmail('jane.smith@example.com');
        $user->setSituationProfessionnel('Salarié');
        $user->setGarant(false);
        $user->setSalaireMensuelNet(2500.0);

        $this->assertEquals('Jane', $user->getPrenom());
        $this->assertEquals('Smith', $user->getNom());
        $this->assertEquals('jane.smith@example.com', $user->getEmail());
        $this->assertEquals('Salarié', $user->getSituationProfessionnel());
        $this->assertFalse($user->isGarant());
        $this->assertEquals(2500.0, $user->getSalaireMensuelNet());
    }

    public function testPasswordVerification(): void {
        $user = new User($this->userData);
        
        $this->assertTrue($user->verifierMotDePasse('password123'));
        $this->assertFalse($user->verifierMotDePasse('wrongpassword'));
    }
}
