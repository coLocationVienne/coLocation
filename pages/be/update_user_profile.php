<?php
session_start();

if (empty($_SESSION['isLoggedin']) || empty($_SESSION['user_id'])) {
    header('Location: ../profile_utilisateur.php?error=login_required');
    exit();
}

require_once('../../includes/dbConnection.php');
$dbConn = getDbConnection();

if (!$dbConn) {
    header('Location: ../profile_utilisateur.php?error=database_error');
    exit();
}

$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

if ($userId !== (int)$_SESSION['user_id']) {
    header('Location: ../profile_utilisateur.php?error=unauthorized');
    exit();
}

$situation = isset($_POST['situation']) ? trim($_POST['situation']) : null;
$garant = isset($_POST['garant']) ? (int)$_POST['garant'] : 0;
$salary = isset($_POST['salary']) && $_POST['salary'] !== '' ? (float)$_POST['salary'] : null;
$revenuFiscal = isset($_POST['revenu_fiscal']) && $_POST['revenu_fiscal'] !== '' ? (float)$_POST['revenu_fiscal'] : null;
$dateNaissance = isset($_POST['date_naissance']) && $_POST['date_naissance'] !== '' ? $_POST['date_naissance'] : null;

$updates = [];
$params = [':user_id' => $userId];

if ($situation !== null && $situation !== '') {
    $updates[] = "situation_professionnel = :situation";
    $params[':situation'] = $situation;
}

$updates[] = "garant = :garant";
$params[':garant'] = $garant;

if ($salary !== null) {
    $updates[] = "salaire_mensuel_net = :salary";
    $params[':salary'] = $salary;
}

if ($revenuFiscal !== null) {
    $updates[] = "revenu_fiscal = :revenu_fiscal";
    $params[':revenu_fiscal'] = $revenuFiscal;
}

if ($dateNaissance !== null) {
    $updates[] = "date_naissance = :date_naissance";
    $params[':date_naissance'] = $dateNaissance;
}

if (empty($updates)) {
    header('Location: ../profile_utilisateur.php?error=no_changes');
    exit();
}

$query = "UPDATE utilisateur SET " . implode(', ', $updates) . " WHERE id_utilisateur = :user_id";
$stmt = $dbConn->prepare($query);

try {
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    
    header('Location: ../profile_utilisateur.php?status=profile_updated');
    exit();
} catch (PDOException $e) {
    error_log("Error updating user profile: " . $e->getMessage());
    header('Location: ../profile_utilisateur.php?error=update_failed');
    exit();
}
?>