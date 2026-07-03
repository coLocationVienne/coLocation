<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header("Location: ../connexion.php?error=erreur_connexion");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../page_annonce.php");
    exit();
}

$requiredFields = [
    "id_annonce",
    "titre",
    "rue_nom",
    "ville",
    "codePostal",
    "loyer",
    "surface_logement",
    "surface_chambres",
    "nombre_chambre",
    "date_expiration",
    "date_cloture",
    "descriptions"
];

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        header("Location: ../formulaire_modifier_annonce.php?id_annonce=" . ($_POST["id_annonce"] ?? "") . "&error=missing_fields");
        exit();
    }
}

$idAnnonce = (int) $_POST["id_annonce"];
$userId = (int) $_SESSION['user_id'];
$titre = trim(htmlspecialchars($_POST["titre"]));
$adresse1 = trim(htmlspecialchars($_POST["rue_nom"]));
$adresse2 = trim(htmlspecialchars($_POST["appartement"] ?? ""));
$adresse3 = trim(htmlspecialchars($_POST["batiment"] ?? ""));
$adresse4 = trim(htmlspecialchars($_POST["infocomplementaire"] ?? ""));
$ville = trim(htmlspecialchars($_POST["ville"]));
$codePostal = (int) $_POST["codePostal"];
$loyer = (float) $_POST["loyer"];
$surfaceLogement = (float) $_POST["surface_logement"];
$surfaceChambres = (float) $_POST["surface_chambres"];
$nombreChambre = (int) $_POST["nombre_chambre"];
$dateExpiration = $_POST["date_expiration"];
$dateCloture = $_POST["date_cloture"];
$description = trim(htmlspecialchars($_POST["descriptions"]));
$dateModification = date("Y-m-d");
$carteCoordonneeGps = trim(htmlspecialchars($_POST["carte_coordonnee_GPS"] ?? ""));
$imageUrl = trim($_POST["image_url"] ?? "");
$modesVie = array_map('intval', $_POST['mode_vie'] ?? []);
$regimes = array_map('intval', $_POST['regime'] ?? []);

if ($imageUrl !== "" && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
    header("Location: ../formulaire_modifier_annonce.php?id_annonce=" . $idAnnonce . "&error=invalid_image");
    exit();
}

try {
    require("../../includes/dbConnection.php");
    $dbConn = getDbConnection();

    if (!$dbConn) {
        header("Location: ../formulaire_modifier_annonce.php?id_annonce=" . $idAnnonce . "&error=server_error");
        exit();
    }

    $check = $dbConn->prepare("
        SELECT 1
        FROM annonce_utilisateur
        WHERE id_annonce = :id_annonce
        AND id_utilisateur = :id_utilisateur
    ");
    $check->execute([
        ":id_annonce" => $idAnnonce,
        ":id_utilisateur" => $userId
    ]);

    if (!$check->fetch()) {
        header("Location: ../page_annonce.php?error=unauthorized");
        exit();
    }

    $stmt = $dbConn->prepare("
        UPDATE annonce
        SET
            titre = :titre,
            adresse_1 = :adresse_1,
            adresse_2 = :adresse_2,
            adresse_3 = :adresse_3,
            adresse_4 = :adresse_4,
            ville = :ville,
            code_postal = :code_postal,
            loyer_location_chez_habitant = :loyer_location_chez_habitant,
            description = :description,
            surface_logement = :surface_logement,
            surface_chambres = :surface_chambres,
            nombre_chambre = :nombre_chambre,
            date_expiration = :date_expiration,
            date_modification = :date_modification,
            carte_coordonnee_GPS = :carte_coordonnee_GPS,
            date_cloture = :date_cloture,
            loyer_colocation = :loyer_colocation
        WHERE id_annonce = :id_annonce
    ");

    $stmt->execute([
        ":titre" => $titre,
        ":adresse_1" => $adresse1,
        ":adresse_2" => $adresse2,
        ":adresse_3" => $adresse3,
        ":adresse_4" => $adresse4,
        ":ville" => $ville,
        ":code_postal" => $codePostal,
        ":loyer_location_chez_habitant" => $loyer,
        ":description" => $description,
        ":surface_logement" => $surfaceLogement,
        ":surface_chambres" => $surfaceChambres,
        ":nombre_chambre" => $nombreChambre,
        ":date_expiration" => $dateExpiration,
        ":date_modification" => $dateModification,
        ":carte_coordonnee_GPS" => $carteCoordonneeGps,
        ":date_cloture" => $dateCloture,
        ":loyer_colocation" => $loyer,
        ":id_annonce" => $idAnnonce
    ]);

    $dbConn->prepare("DELETE FROM annonce_mode_vie WHERE id_annonce = :id_annonce")->execute([
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

    $dbConn->prepare("DELETE FROM annonce_regime_alimentaire WHERE id_annonce = :id_annonce")->execute([
        ':id_annonce' => $idAnnonce
    ]);

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

    if ($imageUrl !== "") {
        $photoStmt = $dbConn->prepare("
            SELECT p.id_photo
            FROM photo p
            INNER JOIN annonce_photo ap ON ap.id_photo = p.id_photo
            WHERE ap.id_annonce = :id_annonce
            ORDER BY p.id_photo ASC
            LIMIT 1
        ");
        $photoStmt->execute([":id_annonce" => $idAnnonce]);
        $photo = $photoStmt->fetch(PDO::FETCH_ASSOC);

        if ($photo) {
            $updatePhoto = $dbConn->prepare("UPDATE photo SET url = :url WHERE id_photo = :id_photo");
            $updatePhoto->execute([
                ":url" => $imageUrl,
                ":id_photo" => $photo["id_photo"]
            ]);
        } else {
            $insertPhoto = $dbConn->prepare("INSERT INTO photo (url) VALUES (:url)");
            $insertPhoto->execute([":url" => $imageUrl]);

            $idPhoto = $dbConn->lastInsertId();
            $linkPhoto = $dbConn->prepare("
                INSERT INTO annonce_photo (id_annonce, id_photo)
                VALUES (:id_annonce, :id_photo)
            ");
            $linkPhoto->execute([
                ":id_annonce" => $idAnnonce,
                ":id_photo" => $idPhoto
            ]);
        }
    }

    header("Location: ../page_annonce.php?success=updated");
    exit();
} catch (PDOException $e) {
    header("Location: ../formulaire_modifier_annonce.php?id_annonce=" . $idAnnonce . "&error=server_error");
    exit();
}

?>