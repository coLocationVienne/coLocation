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

$userId =       isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$role =         isset($_POST['role']) ? $_POST['role'] : '';
$situation =    isset($_POST['situation']) ? $_POST['situation'] : '';
$garant =       isset($_POST['garant']) ? (int)$_POST['garant'] : 0;
$salary =       isset($_POST['salary']) && $_POST['salary'] !== '' ? (float)$_POST['salary'] : null;
$newPassword =  isset($_POST['new_password']) ? $_POST['new_password'] : '';
$confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';


if ($userId <= 0) {
    header('Location: ../profile_utilisateur.php?error=invalid_user');
    exit();
}



// Build update query
$updates = [];
$params = [':user_id' => $userId];

if ($role !== null) {
    $updates[] = "id_role = :role_id";
    $params[':role_id'] = $role;
}

if (!empty($situation)) {
    $updates[] = "situation_professionnel = :situation";
    $params[':situation'] = $situation;
}

$updates[] = "garant = :garant";
$params[':garant'] = $garant;

if ($salary !== null) {
    $updates[] = "salaire_mensuel_net = :salary";
    $params[':salary'] = $salary;
}

// Update password if provided
if (!empty($newPassword)) {
    if (strlen($newPassword) < 6) {
        header('Location: ../profprofile_utilisateurile.php?error=password_too_short');
        exit();
    }
    
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updates[] = "mot_de_passe = :password";
    $params[':password'] = $hashedPassword;
}

if (empty($updates)) {
    header('Location: ../profile_utilisateur.php?error=no_changes');
    exit();
}

// Update user
$query = "UPDATE utilisateur SET " . implode(', ', $updates) . " WHERE id_utilisateur = :user_id";
$stmt = $dbConn->prepare($query);

try {
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    
    header('Location: ../profile_utilisateur.php?status=user_updated');
} catch (PDOException $e) {
    error_log("Error updating user: " . $e->getMessage());
    header('Location: ../profile_utilisateur.php?error=update_failed');
}
?>