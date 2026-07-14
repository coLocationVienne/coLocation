<?php

require_once "../../init.php";

if (empty($_SESSION['isLoggedin']) || empty($_POST['commentaire']) || empty($_POST['id_annonce'])) {
    $id = $_POST['id_annonce'] ?? 0;
    header("Location: ../voir_annonce.php?id=$id&error=missing_data");
    exit();
}

$id_annonce = (int)$_POST['id_annonce'];
$id_owner = (int)$_POST['id_owner'];
$id_user = (int)$_SESSION['user_id'];
$commentaire = trim(htmlspecialchars($_POST['commentaire']));
$note = isset($_POST['note']) ? (float)$_POST['note'] : 5.0;

if ($note > 5.0) $note = 5.0;
if ($note < 0) $note = 0;

try {
    $db = $commentDAO->getDb();
    
    if ($id_owner <= 0) {
        $stmtOwner = $db->prepare("SELECT id_utilisateur FROM annonce_utilisateur WHERE id_annonce = :id LIMIT 1");
        $stmtOwner->execute(['id' => $id_annonce]);
        $id_owner = (int)$stmtOwner->fetchColumn();
    }

    $query = "INSERT INTO annonce_avis (note, date_, commentaire, id_annonce, id_utilisateur, id_utilisateur_1) 
              VALUES (:note, :date_, :commentaire, :id_annonce, :id_user, :id_owner)";
    
    $stmt = $db->prepare($query);
    $success = $stmt->execute([
        'note' => $note,
        'date_' => date('Y-m-d'),
        'commentaire' => $commentaire,
        'id_annonce' => $id_annonce,
        'id_user' => $id_user,
        'id_owner' => $id_owner
    ]);
    
    if ($success) {
        header("Location: ../voir_annonce.php?id=$id_annonce&success=comment_added");
    } else {
        header("Location: ../voir_annonce.php?id=$id_annonce&error=db_error");
    }
} catch (Exception $e) {
    error_log("Comment Error: " . $e->getMessage());
    header("Location: ../voir_annonce.php?id=$id_annonce&error=exception&msg=" . urlencode($e->getMessage()));
}
exit();
