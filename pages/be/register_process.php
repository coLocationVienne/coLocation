<?php

require_once "../../init.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../register.php");
    exit();
}

$requiredFields = ['prenom', 'nom', 'email', 'password', 'password_confirm', 'date_naissance', 'situation_professionnel'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        header("Location: ../register.php?error=missing_fields");
        exit();
    }
}

$email = trim(htmlspecialchars($_POST['email']));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../register.php?error=invalid_email");
    exit();
}

if ($_POST['password'] !== $_POST['password_confirm']) {
    header("Location: ../register.php?error=password_mismatch");
    exit();
}

/** @var \colocation\UserDAO $userDAO */
$existing = $userDAO->findBy(['email' => $email]);
if (!empty($existing)) {
    header("Location: ../register.php?error=email_exists");
    exit();
}

// Create User Object
$userData = [
    'prenom' => trim(htmlspecialchars($_POST['prenom'])),
    'nom' => trim(htmlspecialchars($_POST['nom'])),
    'email' => $email,
    'mot_de_passe' => password_hash($_POST['password'], PASSWORD_DEFAULT),
    'date_naissance' => $_POST['date_naissance'],
    'situation_professionnel' => htmlspecialchars($_POST['situation_professionnel']),
    'garant' => isset($_POST['garant']) ? 1 : 0,
    'salaire_mensuel_net' => $_POST['salaire_mensuel_net'] !== '' ? (float)$_POST['salaire_mensuel_net'] : 0,
    'revenu_fiscal' => htmlspecialchars($_POST['revenu_fiscal'] ?? '0'),
    'id_role' => 3 // Default Locataire
];

$newUser = new \colocation\User($userData);

if ($userDAO->save($newUser)) {
    header("Location: ../connexion.php?registered=1");
    exit();
} else {
    header("Location: ../register.php?error=server_error");
    exit();
}
