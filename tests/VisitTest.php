<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\Visit;

class VisitTest extends TestCase {
    private array $visitData;

    protected function setUp(): void {
        $this->visitData = [
            'id_visite' => 1,
            'id_creneauVisite' => 5,
            'id_utilisateur' => 10,
            'statut' => 'confirme',
            'date_demande' => '2026-08-05 10:00:00',
            'message' => 'Je suis très intéressé par cette annonce.',
            'date_annulation' => null
        ];
    }

    public function testConstructorAndGetters(): void {
        $visit = new Visit($this->visitData);

        $this->assertEquals(1, $visit->getId());
        $this->assertEquals(5, $visit->getIdCreneau());
        $this->assertEquals(10, $visit->getIdUtilisateur());
        $this->assertEquals('confirme', $visit->getStatut());
        $this->assertEquals('2026-08-05 10:00:00', $visit->getDateDemande());
        $this->assertEquals('Je suis très intéressé par cette annonce.', $visit->getMessage());
        $this->assertNull($visit->getDateAnnulation());
    }

    public function testStatusHelpers(): void {
        $visit = new Visit(['statut' => 'en_attente']);
        $this->assertTrue($visit->isPending());
        $this->assertFalse($visit->isConfirmed());

        $visit->setStatut('confirme');
        $this->assertTrue($visit->isConfirmed());

        $visit->setStatut('refuse');
        $this->assertTrue($visit->isRefused());

        $visit->setStatut('annule');
        $this->assertTrue($visit->isCanceled());
    }

    public function testSetters(): void {
        $visit = new Visit([]);

        $visit->setId(2);
        $visit->setIdCreneau(6);
        $visit->setIdUtilisateur(11);
        $visit->setStatut('refuse');
        $visit->setDateDemande('2026-08-06 12:00:00');
        $visit->setMessage('Autre message');
        $visit->setDateAnnulation('2026-08-07 09:00:00');

        $this->assertEquals(2, $visit->getId());
        $this->assertEquals(6, $visit->getIdCreneau());
        $this->assertEquals(11, $visit->getIdUtilisateur());
        $this->assertEquals('refuse', $visit->getStatut());
        $this->assertEquals('2026-08-06 12:00:00', $visit->getDateDemande());
        $this->assertEquals('Autre message', $visit->getMessage());
        $this->assertEquals('2026-08-07 09:00:00', $visit->getDateAnnulation());
    }
}
