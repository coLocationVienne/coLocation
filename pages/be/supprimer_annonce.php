<?php

require_once '../../init.php';

use colocation\AnnonceDAO;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../page_annonce.php');
    exit();
}

if (empty($_SESSION['user_id'])) {
    header('Location: ../connexion.php?error=erreur_connexion');
    exit();
}

if (empty($_POST['id_annonce'])) {
    header('Location: ../page_annonce.php?error=missing_id');
    exit();
}

$idAnnonce = (int)$_POST['id_annonce'];
$idUtilisateur = (int)$_SESSION['user_id'];

$annonceDAO = new AnnonceDAO();

$success = $annonceDAO->supprimerAnnonce($idAnnonce, $idUtilisateur);

if ($success) {
    header('Location: ../page_annonce.php?success=deleted');
    exit();
}

header('Location: ../page_annonce.php?error=delete_failed');
exit();