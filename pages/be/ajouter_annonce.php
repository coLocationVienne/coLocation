<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../creer_annonce.php');
    exit();
}

if (empty($_SESSION['user_id'])) {
    header('Location: ../connexion.php?error=erreur_connexion');
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
    'contact',
    'nombre_chambre'
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
$nombreChambre = (int) $_POST['nombre_chambre'];
$carteCoordonneeGps = trim(htmlspecialchars($_POST['carte_coordonnee_GPS'] ?? ''));
$modesVie = array_map('intval', $_POST['mode_vie'] ?? []);
$regimes = array_map('intval', $_POST['regime'] ?? []);
$garant = ($_POST['garant'] ?? '') === 'oui' ? 1 : 0;

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

    $dbConn->beginTransaction();

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

    $idAnnonce = (int) $dbConn->lastInsertId();

    $linkStmt = $dbConn->prepare("
        INSERT INTO annonce_utilisateur (id_utilisateur, id_annonce)
        VALUES (:id_utilisateur, :id_annonce)
    ");
    $linkStmt->execute([
        ':id_utilisateur' => $_SESSION['user_id'],
        ':id_annonce' => $idAnnonce
    ]);

    if (!empty($modesVie)) {
        $modeStmt = $dbConn->prepare("
            INSERT INTO annonce_mode_vie (id_annonce, id_mode_vie)
            VALUES (:id_annonce, :id_mode_vie)
        ");

        foreach (array_unique($modesVie) as $idModeVie) {
            if ($idModeVie > 0) {
                $modeStmt->execute([
                    ':id_annonce' => $idAnnonce,
                    ':id_mode_vie' => $idModeVie
                ]);
            }
        }
    }

    if (!empty($regimes)) {
        $regimeStmt = $dbConn->prepare("
            INSERT INTO annonce_regime_alimentaire (id_annonce, id_regime_alimentaire)
            VALUES (:id_annonce, :id_regime_alimentaire)
        ");

        foreach (array_unique($regimes) as $idRegime) {
            if ($idRegime > 0) {
                $regimeStmt->execute([
                    ':id_annonce' => $idAnnonce,
                    ':id_regime_alimentaire' => $idRegime
                ]);
            }
        }
    }

    if (isset($_POST['garant'])) {
        $garantStmt = $dbConn->prepare("
            UPDATE utilisateur
            SET garant = :garant
            WHERE id_utilisateur = :id_utilisateur
        ");
        $garantStmt->execute([
            ':garant' => $garant,
            ':id_utilisateur' => $_SESSION['user_id']
        ]);
    }

    $dbConn->commit();

    header('Location: ../creer_annonce.php?success=1');
    exit();
} catch (PDOException $e) {
    if (isset($dbConn) && $dbConn->inTransaction()) {
        $dbConn->rollBack();
    }

    header('Location: ../creer_annonce.php?error=server_error');
    exit();
}

?>