<?php

if(isset($_POST['email']) && isset($_POST['password'])) {
    try {
        require("../../includes/dbConnection.php");
        $dbConn = getDbConnection();
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }


    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $dbConn->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $passwordIsValid = $user && (
        password_verify($password, $user['mot_de_passe']) ||
        hash_equals($user['mot_de_passe'], $password)
    );

    if ($passwordIsValid) {
        session_start();
        $_SESSION['user_id'] = $user['id_utilisateur'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['isLoggedin'] = true;
        $_SESSION['user_role'] = $user['id_role']; 

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
