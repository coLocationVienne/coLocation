<?php
require_once __DIR__ . '/init.php';

use App\Models\VisitSlot;

global $visitSlotDAO;

$annonceId = 28; // From our discovery
$date = date('Y-m-d', strtotime('+2 days'));
$start = '14:00';
$end = '15:00';
$max = 3;

$slot = new VisitSlot([
    'date_visite' => $date,
    'heure_debut' => $start,
    'heure_fin' => $end,
    'nb_personne_max' => $max,
    'id_annonce' => $annonceId
]);

echo "Attempting to add slot for Annonce ID $annonceId on $date at $start...\n";

if ($visitSlotDAO->save($slot)) {
    echo "SUCCESS: Slot added successfully.\n";
    
    // Verify
    $slots = $visitSlotDAO->getByAnnonce($annonceId);
    echo "Current slots for this announcement:\n";
    foreach ($slots as $s) {
        echo "- ID: " . $s->getId() . " | Date: " . $s->getDateVisite() . " | Time: " . $s->getHeureDebut() . "-" . $s->getHeureFin() . " | Max: " . $s->getNbPersonneMax() . "\n";
    }
} else {
    echo "ERROR: Failed to add slot.\n";
}
