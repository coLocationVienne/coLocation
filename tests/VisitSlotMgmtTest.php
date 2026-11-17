<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\VisitSlot;

class VisitSlotMgmtTest extends TestCase {
    private array $slotData;

    protected function setUp(): void {
        $this->slotData = [
            'id_creneauVisite' => 1,
            'date_visite' => '2026-08-10',
            'heure_debut' => '14:00:00',
            'heure_fin' => '15:00:00',
            'nb_personne_max' => 5,
            'id_annonce' => 10
        ];
    }

    public function testConstructorAndGetters(): void {
        $slot = new VisitSlot($this->slotData);

        $this->assertEquals(1, $slot->getId());
        $this->assertEquals('2026-08-10', $slot->getDateVisite());
        $this->assertEquals('14:00:00', $slot->getHeureDebut());
        $this->assertEquals('15:00:00', $slot->getHeureFin());
        $this->assertEquals(5, $slot->getNbPersonneMax());
        $this->assertEquals(10, $slot->getIdAnnonce());
    }

    public function testSetters(): void {
        $slot = new VisitSlot([]);

        $slot->setId(2);
        $slot->setDateVisite('2026-08-11');
        $slot->setHeureDebut('10:00:00');
        $slot->setHeureFin('11:00:00');
        $slot->setNbPersonneMax(3);
        $slot->setIdAnnonce(20);

        $this->assertEquals(2, $slot->getId());
        $this->assertEquals('2026-08-11', $slot->getDateVisite());
        $this->assertEquals('10:00:00', $slot->getHeureDebut());
        $this->assertEquals('11:00:00', $slot->getHeureFin());
        $this->assertEquals(3, $slot->getNbPersonneMax());
        $this->assertEquals(20, $slot->getIdAnnonce());
    }
}
