<?php
session_start();

if (empty($_SESSION['isLoggedin']) || empty($_SESSION['user_id'])) {
    header('Location: ../profile_utilisateur.php?error=unauthorized');
    exit();
}

require_once('../../includes/dbConnection.php');
$dbConn = getDbConnection();

$stmt = $dbConn->prepare("
    SELECT r.role 
    FROM utilisateur u 
    LEFT JOIN role r ON r.id_role = u.id_role 
    WHERE u.id_utilisateur = :user_id
");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin || strtolower($admin['role']) !== 'admin') {
    header('Location: ../profile_utilisateur.php?error=unauthorized');
    exit();
}

$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

if ($userId <= 0) {
    header('Location: ../profile_utilisateur.php?error=invalid_user');
    exit();
}

if ($userId === $_SESSION['user_id']) {
    header('Location: ../profile_utilisateur.php?error=cannot_delete_self');
    exit();
}

try {
    $stmt = $dbConn->prepare("DELETE FROM utilisateur WHERE id_utilisateur = :user_id");
    $stmt->execute(['user_id' => $userId]);
    
    header('Location: ../profile_utilisateur.php?status=user_deleted');
} catch (PDOException $e) {
    error_log("Error deleting user: " . $e->getMessage());
    header('Location: ../profile_utilisateur.php?error=delete_failed');
}
?>