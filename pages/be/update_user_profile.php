<?php

require_once "../../init.php";

if (empty($_SESSION['isLoggedin']) || empty($_SESSION['user_id'])) {
    header('Location: ../profile_utilisateur.php?error=login_required');
    exit();
}

$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

if ($userId !== (int)$_SESSION['user_id']) {
    header('Location: ../profile_utilisateur.php?error=unauthorized');
    exit();
}

/** @var \colocation\UserDAO $userDAO */
$user = $userDAO->getById($userId);

if (!$user) {
    header('Location: ../profile_utilisateur.php?error=invalid_user');
    exit();
}

// Update specific fields from POST
if (isset($_POST['situation'])) $user->setSituationProfessionnel(trim($_POST['situation']));
if (isset($_POST['garant'])) $user->setGarant((bool)$_POST['garant']);
if (isset($_POST['salary'])) $user->setSalaireMensuelNet((float)$_POST['salary']);
if (isset($_POST['revenu_fiscal'])) $user->setRevenuFiscal((float)$_POST['revenu_fiscal']);
if (isset($_POST['date_naissance'])) $user->setDateNaissance($_POST['date_naissance']);
if (isset($_POST['type_compte'])) $user->setTypeCompte($_POST['type_compte']);

if ($userDAO->update($user)) {
    header('Location: ../profile_utilisateur.php?status=profile_updated');
    exit();
} else {
    header('Location: ../profile_utilisateur.php?error=update_failed');
    exit();
}
