<?php

session_start();

require_once '../../init.php';

use colocation\Annonce;
use colocation\AnnonceDAO;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../creer_annonce.php');
    exit();
}

if (empty($_SESSION['user_id'])) {
    header('Location: ../connexion.php?error=erreur_connexion');
    exit();
}

$requiredFields = [
    'titre',
    'rue_nom',
    'ville',
    'codePostal',
    'loyer',
    'surface_logement',
    'surface_chambres',
    'date_expiration',
    'descriptions',
    'nombre_chambre'
];

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        header('Location: ../creer_annonce.php?error=missing_fields');
        exit();
    }
}

$annonce = new Annonce([
    'titre' => trim(htmlspecialchars($_POST['titre'])),
    'adresse_1' => trim(htmlspecialchars($_POST['rue_nom'])),
    'adresse_2' => trim(htmlspecialchars($_POST['appartement'] ?? '')),
    'adresse_3' => trim(htmlspecialchars($_POST['batiment'] ?? '')),
    'adresse_4' => trim(htmlspecialchars($_POST['infocomplementaire'] ?? '')),
    'ville' => trim(htmlspecialchars($_POST['ville'])),
    'code_postal' => $_POST['codePostal'],
    'loyer_location_chez_habitant' => $_POST['loyer'],
    'description' => trim(htmlspecialchars($_POST['descriptions'])),
    'surface_logement' => $_POST['surface_logement'],
    'surface_chambres' => $_POST['surface_chambres'],
    'nombre_chambre' => $_POST['nombre_chambre'],
    'date_expiration' => $_POST['date_expiration'],
    'date_publication' => date('Y-m-d'),
    'date_modification' => date('Y-m-d'),
    'carte_coordonnee_GPS' => trim(htmlspecialchars($_POST['carte_coordonnee_GPS'] ?? '')),
    'date_cloture' => $_POST['date_expiration'],
    'loyer_colocation' => $_POST['loyer']
]);

$modesVie = $_POST['mode_vie'] ?? [];
$regimes = $_POST['regime'] ?? [];

$annonceDAO = new AnnonceDAO();
$success = $annonceDAO->ajouterAnnonce($annonce, (int)$_SESSION['user_id'], $modesVie, $regimes);

if ($success) {
    header('Location: ../creer_annonce.php?success=1');
    exit();
}

header('Location: ../creer_annonce.php?error=server_error');
exit();

?>