<?php

require_once "../../init.php";

if (empty($_SESSION['isLoggedin'])) {
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

/** @var \colocation\UserDAO $userDAO */
$user = $userDAO->getById((int)$userId);

if (!$user || !$user->verifierMotDePasse($currentPassword)) {
     header("Location: ../profile_utilisateur.php?error=invalid_current_password&passwordModal=1");
     exit();
}

$user->setMotDePasse(password_hash($newPassword, PASSWORD_DEFAULT));

if ($userDAO->update($user)) {
    header("Location: ../profile_utilisateur.php?status=password_updated");
    exit();
} else {
    header("Location: ../profile_utilisateur.php?error=update_failed&passwordModal=1");
    exit();
}
