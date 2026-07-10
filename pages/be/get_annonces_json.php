<?php
require_once "../../init.php";
header('Content-Type: application/json');

/** @var \colocation\AnnonceDAO $annonceDAO */
try {
    $annonces = $annonceDAO->getAllWithPhotos();
    $data = [];
    
    foreach ($annonces as $a) {
        $data[] = [
            'id' => $a->getId(),
            'titre' => $a->getTitre(),
            'loyer' => $a->getLoyer(),
            'gps' => $a->getGps(),
            'photo' => $a->getPhoto(),
            'ville' => $a->getVille()
        ];
    }
    
    echo json_encode(['success' => true, 'annonces' => $data]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
