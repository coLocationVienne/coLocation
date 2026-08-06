<?php

namespace App\Models;

class VisitSlotDAO extends DAO {
    public function __construct() {
        parent::__construct('creneau_visite', 'id_creneauVisite');
    }

    protected function hydrate(array $row): VisitSlot {
        return new VisitSlot($row);
    }

    protected function dehydrate(object $slot): array {
        /** @var VisitSlot $slot */
        return [
            'id_creneauVisite' => $slot->getId(),
            'date_visite' => $slot->getDateVisite(),
            'heure_debut' => $slot->getHeureDebut(),
            'heure_fin' => $slot->getHeureFin(),
            'nb_personne_max' => $slot->getNbPersonneMax(),
            'id_annonce' => $slot->getIdAnnonce()
        ];
    }

    public function getByAnnonce(int $annonceId): array {
        $query = "SELECT * FROM creneau_visite WHERE id_annonce = :annonceId ORDER BY date_visite ASC, heure_debut ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['annonceId' => $annonceId]);
        $results = $stmt->fetchAll();
        
        $slots = [];
        foreach ($results as $row) {
            $slots[] = $this->hydrate($row);
        }
        return $slots;
    }
}
