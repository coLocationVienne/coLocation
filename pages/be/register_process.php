<?php

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

$prenom = trim(htmlspecialchars($_POST['prenom']));
$nom = trim(htmlspecialchars($_POST['nom']));
$email = trim(htmlspecialchars($_POST['email']));
$password = $_POST['password'];
$passwordConfirm = $_POST['password_confirm'];
$dateNaissance = $_POST['date_naissance'];
$situationProfessionnel = htmlspecialchars($_POST['situation_professionnel']);
$garant = isset($_POST['garant']) ? 1 : 0;
$salaireMensuelNet = $_POST['salaire_mensuel_net'] !== '' ? (float) $_POST['salaire_mensuel_net'] : 0;
$revenuFiscal = htmlspecialchars($_POST['revenu_fiscal'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../register.php?error=invalid_email");
    exit();
}

if ($password !== $passwordConfirm) {
    header("Location: ../register.php?error=password_mismatch");
    exit();
}

try {
    require("../../includes/dbConnection.php");
    $dbConn = getDbConnection();

    if (!$dbConn) {
        header("Location: ../register.php?error=server_error");
        exit();
    }

    $existingUser = $dbConn->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = :email");
    $existingUser->bindParam(':email', $email);
    $existingUser->execute();

    if ($existingUser->fetch(PDO::FETCH_ASSOC)) {
        header("Location: ../register.php?error=email_exists");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $photoProfil = '';
    $retraite = 0;
    $caisseAllocationFamilial = 0;
    $roleLocataire = 3;

    $stmt = $dbConn->prepare("
        INSERT INTO utilisateur (
            nom,
            email,
            mot_de_passe,
            situation_professionnel,
            garant,
            retraite,
            caisse_allocation_familial,
            date_naissance,
            photo_profil,
            salaire_mensuel_net,
            prenom,
            revenu_fiscal,
            id_role
        ) VALUES (
            :nom,
            :email,
            :mot_de_passe,
            :situation_professionnel,
            :garant,
            :retraite,
            :caisse_allocation_familial,
            :date_naissance,
            :photo_profil,
            :salaire_mensuel_net,
            :prenom,
            :revenu_fiscal,
            :id_role
        )
    ");

    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mot_de_passe', $hashedPassword);
    $stmt->bindParam(':situation_professionnel', $situationProfessionnel);
    $stmt->bindParam(':garant', $garant, PDO::PARAM_INT);
    $stmt->bindParam(':retraite', $retraite);
    $stmt->bindParam(':caisse_allocation_familial', $caisseAllocationFamilial);
    $stmt->bindParam(':date_naissance', $dateNaissance);
    $stmt->bindParam(':photo_profil', $photoProfil);
    $stmt->bindParam(':salaire_mensuel_net', $salaireMensuelNet);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':revenu_fiscal', $revenuFiscal);
    $stmt->bindParam(':id_role', $roleLocataire, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: ../connexion.php?registered=1");
    exit();
} catch (PDOException $e) {
    header("Location: ../register.php?error=server_error");
    exit();
}
