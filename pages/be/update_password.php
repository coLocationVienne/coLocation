<?php

if (!empty($_SESSION['isLoggedin'])) {
    header("Location: ../../index.php");
    exit();
}

$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$userId = $_POST['user_id'] ?? '';

if (empty($currentPassword) || empty($newPassword)) {
    header("Location: ../profile_utilisateur.php?error=missing_fields&passwordModal=1");
    exit();
}
require("../../includes/dbConnection.php");
$db = getDbConnection();
$statement = $db->prepare("SELECT * FROM UTILISATEUR WHERE id_utilisateur = :user_id");
$statement->bindParam(':user_id', $userId);

    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);


if (!$user || !password_verify($currentPassword, $user['mot_de_passe'])) {
     header("Location: ../profile_utilisateur.php?error=invalid_current_password&passwordModal=1");
     exit();
 }

$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
$updateStatement = $db->prepare("UPDATE UTILISATEUR SET mot_de_passe = :new_password WHERE id_utilisateur = :user_id");
$updateStatement->bindParam(':new_password', $newPasswordHash);
$updateStatement->bindParam(':user_id', $userId);
try {
    $updateStatement->execute();
    header("Location: ../profile_utilisateur.php?status=password_updated");
    exit();
} catch (PDOException $e) {
    die("Database update failed: " . $e->getMessage());
}


?>