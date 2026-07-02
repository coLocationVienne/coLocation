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
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || strtolower($user['role']) !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Accès refusé']);
    exit();
}


$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$role = isset($_GET['role']) ? $_GET['role'] : '';
$limit = 10;
$offset = ($page - 1) * $limit;

$whereConditions = ["1=1"];
$params = [];

if (!empty($search)) {
    $whereConditions[] = "(u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($role)) {
    $whereConditions[] = "LOWER(r.role) = LOWER(:role)";
    $params[':role'] = $role;
}

$whereClause = implode(' AND ', $whereConditions);

$countQuery = "
    SELECT COUNT(*) as total 
    FROM utilisateur u 
    LEFT JOIN role r ON r.id_role = u.id_role 
    WHERE $whereClause
";
$stmt = $dbConn->prepare($countQuery);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($total / $limit);

// Get users
$query = "
    SELECT 
        u.id_utilisateur,
        u.nom,
        u.prenom,
        u.email,
        u.situation_professionnel,
        u.garant,
        u.date_naissance,
        u.salaire_mensuel_net,
        u.revenu_fiscal,
        r.role
    FROM utilisateur u
    LEFT JOIN role r ON r.id_role = u.id_role
    WHERE $whereClause
    ORDER BY u.id_utilisateur DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $dbConn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'users' => $users,
    'total' => $total,
    'totalPages' => $totalPages,
    'currentPage' => $page,
    'limit' => $limit
]);
?>