<?php
require_once '../../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user_id'])) {
    header('Location: ../page_annonce.php');
    exit();
}

$idAnnonce = (int)$_POST['id_annonce'];
$annonceDAO = new \colocation\AnnonceDAO();

// Verify ownership
if (!$annonceDAO->appartientAUtilisateur($idAnnonce, (int)$_SESSION['user_id'])) {
    header('Location: ../page_annonce.php?error=unauthorized');
    exit();
}

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    // Move to root photos directory
    $uploadDir = 'photos/annonce' . $idAnnonce . '/';
    $fullDir = __DIR__ . '/../../' . $uploadDir;
    
    if (!is_dir($fullDir)) {
        mkdir($fullDir, 0777, true);
    }

    $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $fileName = uniqid() . '.' . $extension;
    $targetFile = $fullDir . $fileName;
    $dbPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
        if ($annonceDAO->addPhoto($idAnnonce, $dbPath)) {
            header('Location: ../modifier_photos.php?id_annonce=' . $idAnnonce . '&success=uploaded');
            exit();
        }
    }
}

header('Location: ../modifier_photos.php?id_annonce=' . $idAnnonce . '&error=upload_failed');
exit();
