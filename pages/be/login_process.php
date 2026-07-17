<?php

require_once "../../init.php";

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $redirect = !empty($_POST['redirect']) ? $_POST['redirect'] : "../../index.php";
    
    // Safety check: if redirect contains "voir_annonce.php", ensure it points to the correct pages/ folder
    // Since this script is in be/, a relative redirect like "voir_annonce.php" would fail.
    // We should make it relative to the project root or the current script.
    if (strpos($redirect, 'voir_annonce.php') !== false && strpos($redirect, '../') === false && strpos($redirect, 'http') === false) {
        $redirect = "../" . $redirect;
    }

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

        header("Location: " . $redirect);
        exit();
    } else {
        $redirectParam = !empty($_POST['redirect']) ? "&redirect=" . urlencode($_POST['redirect']) : "";
        header("Location: ../connexion.php?error=invalid_credentials" . $redirectParam);
        exit();
    }
} else {
    header("Location: ../connexion.php?error=missing_fields");
    exit();
}
