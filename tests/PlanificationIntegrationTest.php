<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Group;
use App\Models\Visit;
use App\Models\VisitDAO;
use App\Models\VisitSlot;
use App\Models\VisitSlotDAO;
use App\Models\Annonce;
use App\Models\AnnonceDAO;
use App\Core\Database;

#[Group('integration')]
#[Group('database')]
class PlanificationIntegrationTest extends TestCase {
    private VisitDAO $visitDAO;
    private VisitSlotDAO $visitSlotDAO;
    private AnnonceDAO $annonceDAO;
    private \PDO $pdo;

    protected function setUp(): void {
        parent::setUp();
        $this->pdo = Database::getInstance()->getConnection();
        $this->visitDAO = new VisitDAO();
        $this->visitSlotDAO = new VisitSlotDAO();
        $this->annonceDAO = new AnnonceDAO();

        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $this->pdo->exec("DELETE FROM visite");
        $this->pdo->exec("DELETE FROM creneau_visite");
        $this->pdo->exec("DELETE FROM annonce_utilisateur");
        $this->pdo->exec("DELETE FROM annonce");
        $this->pdo->exec("DELETE FROM utilisateur");
        $this->pdo->exec("DELETE FROM role");
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

        // Fixtures
        $this->pdo->exec("INSERT INTO role (id_role, role) VALUES (1, 'admin'), (2, 'owner'), (3, 'colocataire')");
        $this->pdo->exec("INSERT INTO utilisateur (id_utilisateur, prenom, nom, email, mot_de_passe, situation_professionnel, garant, retraite, caisse_allocation_familial, date_naissance, photo_profil, salaire_mensuel_net, revenu_fiscal, id_role, type_compte) VALUES (1, 'Owner', 'User', 'owner@example.com', 'hash', 'Propriétaire', 1, 0, 0, '1980-01-01', '', 3000, '40000', 2, 'colocataire')");
        $this->pdo->exec("INSERT INTO utilisateur (id_utilisateur, prenom, nom, email, mot_de_passe, situation_professionnel, garant, retraite, caisse_allocation_familial, date_naissance, photo_profil, salaire_mensuel_net, revenu_fiscal, id_role, type_compte) VALUES (2, 'Tenant', 'User', 'tenant@example.com', 'hash', 'Étudiant', 1, 0, 0, '2000-01-01', '', 500, '10000', 3, 'colocataire')");
        
        $annonce = new Annonce([
            'id_annonce' => 1,
            'titre' => 'Annonce Test',
            'ville' => 'Vienne',
            'loyer_location_chez_habitant' => 500
        ]);
        $this->annonceDAO->save($annonce);
        $this->pdo->exec("INSERT INTO annonce_utilisateur (id_annonce, id_utilisateur) VALUES (1, 1)");
    }

    public function testSaveAndGetSlot(): void {
        $slot = new VisitSlot([
            'date_visite' => '2026-09-01',
            'heure_debut' => '10:00:00',
            'heure_fin' => '11:00:00',
            'nb_personne_max' => 2,
            'id_annonce' => 1
        ]);
        
        $this->visitSlotDAO->save($slot);
        $slotId = $this->pdo->lastInsertId();
        
        $savedSlot = $this->visitSlotDAO->getById($slotId);
        $this->assertNotNull($savedSlot);
        $this->assertEquals('2026-09-01', $savedSlot->getDateVisite());
        $this->assertEquals(1, $savedSlot->getIdAnnonce());
        
        $slots = $this->visitSlotDAO->getByAnnonce(1);
        $this->assertCount(1, $slots);
    }

    public function testSaveAndGetVisit(): void {
        // Create slot first
        $slot = new VisitSlot([
            'date_visite' => '2026-09-01',
            'heure_debut' => '10:00:00',
            'heure_fin' => '11:00:00',
            'nb_personne_max' => 2,
            'id_annonce' => 1
        ]);
        $this->visitSlotDAO->save($slot);
        $slotId = $this->pdo->lastInsertId();

        $visit = new Visit([
            'id_creneauVisite' => $slotId,
            'id_utilisateur' => 2,
            'statut' => 'en_attente',
            'message' => 'Test message'
        ]);
        
        $this->visitDAO->save($visit);
        $visitId = $this->pdo->lastInsertId();
        
        $savedVisit = $this->visitDAO->getById($visitId);
        $this->assertNotNull($savedVisit);
        $this->assertEquals('en_attente', $savedVisit->getStatut());
        
        $details = $this->visitDAO->getFullDetails($visitId);
        $this->assertNotNull($details);
        $this->assertEquals('Annonce Test', $details['annonce_titre']);
        $this->assertEquals('Tenant', $details['tenant_prenom']);
        $this->assertEquals('Owner', $details['owner_prenom']);
    }

    public function testGetByTenantAndOwner(): void {
        $slot = new VisitSlot([
            'date_visite' => '2026-09-01',
            'heure_debut' => '10:00:00',
            'heure_fin' => '11:00:00',
            'nb_personne_max' => 2,
            'id_annonce' => 1
        ]);
        $this->visitSlotDAO->save($slot);
        $slotId = $this->pdo->lastInsertId();

        $visit = new Visit([
            'id_creneauVisite' => $slotId,
            'id_utilisateur' => 2,
            'statut' => 'confirme'
        ]);
        $this->visitDAO->save($visit);
        
        $tenantVisits = $this->visitDAO->getByTenant(2);
        $this->assertCount(1, $tenantVisits);
        
        $ownerVisits = $this->visitDAO->getByOwner(1);
        $this->assertCount(1, $ownerVisits);
    }
}
