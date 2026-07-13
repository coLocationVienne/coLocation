<?php

require_once "../../init.php";

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    /** @var \colocation\UserDAO $userDAO */
    $users = $userDAO->findBy(['email' => $email]);
    $user = !empty($users) ? $users[0] : null;

    if ($user && $user->verifierMotDePasse($password)) {
        $_SESSION['user_id'] = $user->getIdUtilisateur();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_prenom'] = $user->getPrenom();
        $_SESSION['user_nom'] = $user->getNom();
        $_SESSION['isLoggedin'] = true;
        $_SESSION['user_role'] = $user->getIdRole();

        header("Location: ../../index.php");
        exit();
    } else {
        header("Location: ../connexion.php?error=invalid_credentials");
        exit();
    }
} else {
    header("Location: ../connexion.php?error=missing_fields");
    exit();
}
