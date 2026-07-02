<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../creer_annonce.php');
    exit();
}


$requiredFields = [
    'titre',
    'rue_nom',
    'ville',
    'codePostal',
    'loyer',
    'surface_logement',
    'surface_chambres',
    'disponibilite',
    'date_expiration',
    'descriptions',
    'contact'
];

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        header('Location: ../creer_annonce.php?error=missing_fields');
        exit();
    }
}

$titre = trim(htmlspecialchars($_POST['titre']));
$adresse1 = trim(htmlspecialchars($_POST['rue_nom']));
$adresse2 = trim(htmlspecialchars($_POST['appartement'] ?? ''));
$adresse3 = trim(htmlspecialchars($_POST['batiment'] ?? ''));
$adresse4 = trim(htmlspecialchars($_POST['infocomplementaire'] ?? ''));
$ville = trim(htmlspecialchars($_POST['ville']));
$codePostal = (int) $_POST['codePostal'];
$loyer = (float) $_POST['loyer'];
$description = trim(htmlspecialchars($_POST['descriptions']));
$surfaceLogement = (float) $_POST['surface_logement'];
$surfaceChambres = (float) $_POST['surface_chambres'];
$dateExpiration = $_POST['date_expiration'];
$datePublication = date('Y-m-d');
$dateModification = date('Y-m-d');
$dateCloture = $dateExpiration;
$contact = trim($_POST['contact']);
$loyerColocation = $loyer;
$nombreChambre = !empty($_POST['nombre_chambre']) ? (int) $_POST['nombre_chambre'] : 1;
$carteCoordonneeGps = trim(htmlspecialchars($_POST['carte_coordonnee_GPS'] ?? ''));
$modesVie = $_POST['mode_vie'] ?? [];



if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../creer_annonce.php?error=invalid_email');
    exit();
}

try {
    require('../../includes/dbConnection.php');
    $dbConn = getDbConnection();

    if (!$dbConn) {
        header('Location: ../creer_annonce.php?error=server_error');
        exit();
    }

    $stmt = $dbConn->prepare("
        INSERT INTO annonce (
            titre,
            adresse_1,
            adresse_2,
            adresse_3,
            adresse_4,
            ville,
            code_postal,
            loyer_location_chez_habitant,
            description,
            surface_logement,
            surface_chambres,
            nombre_chambre,
            date_expiration,
            date_publication,
            date_modification,
            carte_coordonnee_GPS,
            date_cloture,
            loyer_colocation
           
        ) VALUES (
            :titre,
            :adresse_1,
            :adresse_2,
            :adresse_3,
            :adresse_4,
            :ville,
            :code_postal,
            :loyer_location_chez_habitant,
            :description,
            :surface_logement,
            :surface_chambres,
            :nombre_chambre,
            :date_expiration,
            :date_publication,
            :date_modification,
            :carte_coordonnee_GPS,
            :date_cloture,
            :loyer_colocation
           
        )
    ");

    $stmt->execute([
        ':titre' => $titre,
        ':adresse_1' => $adresse1,
        ':adresse_2' => $adresse2,
        ':adresse_3' => $adresse3,
        ':adresse_4' => $adresse4,
        ':ville' => $ville,
        ':code_postal' => $codePostal,
        ':loyer_location_chez_habitant' => $loyer,
        ':description' => $description,
        ':surface_logement' => $surfaceLogement,
        ':surface_chambres' => $surfaceChambres,
        ':nombre_chambre' => $nombreChambre,
        ':date_expiration' => $dateExpiration,
        ':date_publication' => $datePublication,
        ':date_modification' => $dateModification,
        ':carte_coordonnee_GPS' => $carteCoordonneeGps,
        ':date_cloture' => $dateCloture,
        ':loyer_colocation' => $loyerColocation,
    ]);

    session_start();
    if (!empty($_SESSION['user_id'])) {
        $idAnnonce = $dbConn->lastInsertId();
        $linkStmt = $dbConn->prepare("
            INSERT INTO annonce_utilisateur (id_utilisateur, id_annonce)
            VALUES (:id_utilisateur, :id_annonce)
        ");
        $linkStmt->execute([
            ':id_utilisateur' => $_SESSION['user_id'],
            ':id_annonce' => $idAnnonce
        ]);
    }

    header('Location: ../creer_annonce.php?success=1');
    exit();
} catch (PDOException $e) {
    header('Location: ../creer_annonce.php?error=server_error');
    exit();
}
