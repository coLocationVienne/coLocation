<?php

require_once "../../init.php";

header('Content-Type: application/json');

if (empty($_SESSION['isLoggedin']) || empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Accès refusé: non connecté']);
    exit();
}

$adminUser = $userDAO->getById((int)$_SESSION['user_id']);

if (!$adminUser || $adminUser->getIdRole() !== 1) {
    echo json_encode(['success' => false, 'message' => 'Accès refusé: privilèges insuffisants']);
    exit();
}

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$role = isset($_GET['role']) ? trim($_GET['role']) : '';

try {
    $result = $userDAO->searchPaginated($search, $role, $page, 10);
    
    echo json_encode(array_merge(['success' => true], $result));
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
