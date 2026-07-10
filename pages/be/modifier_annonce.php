<?php

require_once '../../init.php';

use colocation\Annonce;
use colocation\AnnonceDAO;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../page_annonce.php');
    exit();
}

if (empty($_SESSION['user_id'])) {
    header('Location: ../connexion.php?error=erreur_connexion');
    exit();
}

$requiredFields = [
    'id_annonce',
    'titre',
    'rue_nom',
    'ville',
    'codePostal',
    'loyer',
    'surface_logement',
    'surface_chambres',
    'nombre_chambre',
    'date_expiration',
    'date_cloture',
    'descriptions'
];

$idAnnonce = (int)($_POST['id_annonce'] ?? 0);

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        header(
            'Location: ../formulaire_modifier_annonce.php?id_annonce='. $idAnnonce. '&error=missing_fields'
        );
        exit();
    }
}

$annonceDAO = new AnnonceDAO();
$ancienneAnnonce = $annonceDAO->getById($idAnnonce);

if (!$ancienneAnnonce) {
    header('Location: ../page_annonce.php?error=not_found');
    exit();
}

$annonce = new Annonce([
    'id_annonce' => $idAnnonce,
    'titre' => trim($_POST['titre']),
    'adresse_1' => trim($_POST['rue_nom']),
    'adresse_2' => trim($_POST['appartement'] ?? ''),
    'adresse_3' => trim($_POST['batiment'] ?? ''),
    'adresse_4' => trim($_POST['infocomplementaire'] ?? ''),
    'ville' => trim($_POST['ville']),
    'code_postal' => trim($_POST['codePostal']),
    'loyer_location_chez_habitant' => (float)$_POST['loyer'],
    'description' => trim($_POST['descriptions']),
    'surface_logement' => (float)$_POST['surface_logement'],
    'surface_chambres' => (float)$_POST['surface_chambres'],
    'nombre_chambre' => (int)$_POST['nombre_chambre'],
    'date_expiration' => $_POST['date_expiration'],
    'date_publication' => $ancienneAnnonce->getDatePublication(),
    'date_modification' => date('Y-m-d'),
    'carte_coordonnee_GPS' => trim(
        $_POST['carte_coordonnee_GPS'] ?? ''
    ),
    'date_cloture' => $_POST['date_cloture'],
    'loyer_colocation' => (float)$_POST['loyer']
]);

$modesVie = $_POST['mode_vie'] ?? [];
$regimes = $_POST['regime'] ?? [];

$success = $annonceDAO->modifierAnnonce(
    $annonce,
    (int)$_SESSION['user_id'],
    $modesVie,
    $regimes
);

if ($success) {
    header('Location: ../page_annonce.php?success=updated');
    exit();
}

header(
    'Location: ../formulaire_modifier_annonce.php?id_annonce='
    . $idAnnonce
    . '&error=unauthorized'
);
exit(); 