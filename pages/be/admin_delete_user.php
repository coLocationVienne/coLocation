<?php

require_once "../../init.php";

if (empty($_SESSION['isLoggedin']) || empty($_SESSION['user_id'])) {
    header('Location: ../profile_utilisateur.php?error=unauthorized');
    exit();
}

/** @var \colocation\UserDAO $userDAO */
$adminUser = $userDAO->getById((int)$_SESSION['user_id']);

if (!$adminUser || $adminUser->getIdRole() != 1) {
    header('Location: ../profile_utilisateur.php?error=unauthorized');
    exit();
}

$userIdToDelete = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

if ($userIdToDelete <= 0) {
    header('Location: ../profile_utilisateur.php?error=invalid_user');
    exit();
}

if ($userIdToDelete === (int)$_SESSION['user_id']) {
    header('Location: ../profile_utilisateur.php?error=cannot_delete_self');
    exit();
}

if ($userDAO->delete($userIdToDelete)) {
    header('Location: ../profile_utilisateur.php?status=user_deleted');
    exit();
} else {
    header('Location: ../profile_utilisateur.php?error=delete_failed');
    exit();
}
