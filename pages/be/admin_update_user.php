<?php

require_once "../../init.php";

if (empty($_SESSION['isLoggedin']) || empty($_SESSION['user_id'])) {
    header('Location: ../profile_utilisateur.php?error=unauthorized');
    exit();
}

/** @var \colocation\UserDAO $userDAO */
$adminUser = $userDAO->getById((int)$_SESSION['user_id']);

// Check if current user is admin (role id 1)
if (!$adminUser || $adminUser->getIdRole() != 1) {
    header('Location: ../profile_utilisateur.php?error=unauthorized');
    exit();
}

$userIdToUpdate = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
if ($userIdToUpdate <= 0) {
    header('Location: ../profile_utilisateur.php?error=invalid_user');
    exit();
}

$user = $userDAO->getById($userIdToUpdate);
if (!$user) {
    header('Location: ../profile_utilisateur.php?error=invalid_user');
    exit();
}

// Update fields
if (isset($_POST['role'])) $user->setIdRole((int)$_POST['role']);
if (!empty($_POST['situation'])) $user->setSituationProfessionnel(trim($_POST['situation']));
if (isset($_POST['garant'])) $user->setGarant((bool)$_POST['garant']);
if (isset($_POST['salary'])) $user->setSalaireMensuelNet((float)$_POST['salary']);

if (!empty($_POST['new_password'])) {
    if (strlen($_POST['new_password']) < 6) {
        header('Location: ../profile_utilisateur.php?error=password_too_short');
        exit();
    }
    $user->setMotDePasse(password_hash($_POST['new_password'], PASSWORD_DEFAULT));
}

if ($userDAO->update($user)) {
    header('Location: ../profile_utilisateur.php?status=user_updated');
    exit();
} else {
    header('Location: ../profile_utilisateur.php?error=update_failed');
    exit();
}
