<?php
require_once '../../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user_id'])) {
    header('Location: ../page_annonce.php');
    exit();
}

$idPhoto = (int)$_POST['id_photo'];
$idAnnonce = (int)$_POST['id_annonce'];
$annonceDAO = new \colocation\AnnonceDAO();

// Verify ownership of the announcement linked to this photo
if (!$annonceDAO->appartientAUtilisateur($idAnnonce, (int)$_SESSION['user_id'])) {
    header('Location: ../page_annonce.php?error=unauthorized');
    exit();
}

// Get photo URL to delete the file
$stmt = $annonceDAO->getDb()->prepare("SELECT url FROM photo WHERE id_photo = ?");
$stmt->execute([$idPhoto]);
$url = $stmt->fetchColumn();

if ($annonceDAO->deletePhoto($idPhoto)) {
    // Delete physical file from root
    $filePath = __DIR__ . '/../../' . $url;
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    header('Location: ../modifier_photos.php?id_annonce=' . $idAnnonce . '&success=deleted');
    exit();
}

header('Location: ../modifier_photos.php?id_annonce=' . $idAnnonce . '&error=delete_failed');
exit();
