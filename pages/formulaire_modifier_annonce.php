<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../includes/dbConnection.php";

if (empty($_SESSION['user_id'])) {
    header("Location: connexion.phperror=erreur_connexion");
    exit();
}

if (empty($_GET['id_annonce'])) {
    header("Location: page_annonce.php");
    exit();
}

$dbConn = getDbConnection();
$idAnnonce = (int) $_GET['id_annonce'];
$userId = (int) $_SESSION['user_id'];
$error = $_GET['error'] ?? '';

$stmt = $dbConn->prepare("SELECT

a.*,
        (
            SELECT p.url
            FROM annonce_photo ap
            INNER JOIN photo p ON p.id_photo = ap.id_photo
            WHERE ap.id_annonce = a.id_annonce
            ORDER BY p.id_photo ASC
            LIMIT 1
        ) AS image_url
    FROM annonce a
    INNER JOIN annonce_utilisateur au ON au.id_annonce = a.id_annonce
    WHERE a.id_annonce = :id_annonce
    AND au.id_utilisateur = :id_utilisateur
");
$stmt->execute([
    ':id_annonce' => $idAnnonce,
    ':id_utilisateur' => $userId
]);
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$annonce) {
    header("Location: page_annonce.phperror=unauthorized");
    exit();
}

$modeStmt = $dbConn->prepare("SELECT id_mode_vie FROM annonce_mode_vie WHERE id_annonce = :id_annonce");
$modeStmt->execute([':id_annonce' => $idAnnonce]);
$selectedModes = array_map('intval', $modeStmt->fetchAll(PDO::FETCH_COLUMN));

$regimeStmt = $dbConn->prepare("SELECT id_regime_alimentaire FROM annonce_regime_alimentaire WHERE id_annonce = :id_annonce");
$regimeStmt->execute([':id_annonce' => $idAnnonce]);
$selectedRegimes = array_map('intval', $regimeStmt->fetchAll(PDO::FETCH_COLUMN));

include("../includes/header.php");

$errorMessages = [
    'missing_fields' => 'Veuillez remplir tous les champs obligatoires.',
    'server_error' => 'Une erreur est survenue pendant la modification de l annonce.',
    'invalid_image' => 'Veuillez saisir une adresse dâ€™image valide.'
];
?>

<main class="creer-annonce-page">
    <section class="creer-annonce-hero">
        <div>
            <p class="annonce-kicker">Modifier une annonce</p>
            <h1>Modifier votre annonce</h1>
            <p>
                Mettez a  jour toutes les informations visibles sur votre annonce.
            </p>
        </div>

        <div class="annonce-conseil">
            <strong>Conseil</strong>
            <span>Une annonce a  jour evite les messages inutiles et donne plus confiance aux colocataires.</span>
        </div>
    </section>

    <section class="annonce-form-section">
        <?php if (!empty($error) && isset($errorMessages[$error])): ?>
            <div class="annonce-message" role="alert">
                <?php echo $errorMessages[$error]; ?>
            </div>
        <?php endif; ?>

        <form action="be/modifier_annonce.php" method="POST" class="annonce-form">
            <input type="hidden" name="id_annonce" value="<?php echo (int) $annonce['id_annonce']; ?>">

            <fieldset>
                <legend>Informations du logement</legend>

                <div class="annonce-form-grid deux-colonnes">
                    <div class="annonce-field">
                        <label for="titre">Titre de l'annonce</label>
                        <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($annonce['titre']); ?>" required>
                    </div>

                    <div class="annonce-field">
                        <label for="rue_nom">Numero et nom de rue</label>
                        <input type="text" id="rue_nom" name="rue_nom" value="<?php echo htmlspecialchars($annonce['adresse_1']); ?>" required>
                    </div>
                </div>

                <div class="annonce-form-grid trois-colonnes">
                    <div class="annonce-field">
                        <label for="appartement">Numero appartement</label>
                        <input type="text" id="appartement" name="appartement" value="<?php echo htmlspecialchars($annonce['adresse_2'] ?? ''); ?>">
                    </div>

                    <div class="annonce-field">
                        <label for="batiment">Numero de batiment</label>
                        <input type="text" id="batiment" name="batiment" value="<?php echo htmlspecialchars($annonce['adresse_3'] ?? ''); ?>">
                    </div>

                    <div class="annonce-field">
                        <label for="codePostal">Code postal</label>
                        <input type="number" id="codePostal" name="codePostal" value="<?php echo htmlspecialchars($annonce['code_postal']); ?>" required>
                    </div>
                </div>

                <div class="annonce-field">
                    <label for="infocomplementaire">Information complaimentaire</label>
                    <input type="text" id="infocomplementaire" name="infocomplementaire" value="<?php echo htmlspecialchars($annonce['adresse_4'] ?? ''); ?>">
                </div>

                <div class="annonce-form-grid deux-colonnes">
                    <div class="annonce-field">
                        <label for="ville">Ville</label>
                        <input type="text" id="ville" name="ville" value="<?php echo htmlspecialchars($annonce['ville']); ?>" required>
                    </div>

                    <div class="annonce-field">
                        <label for="image_url">Image de l'annonce</label>
                        <input type="url" id="image_url" name="image_url" value="<?php echo htmlspecialchars($annonce['image_url'] ?? ''); ?>" placeholder="https://...">
                    </div>
                </div>

                <div class="annonce-form-grid trois-colonnes">
                    <div class="annonce-field">
                        <label for="loyer">Loyer mensuel</label>
                        <input type="number" id="loyer" name="loyer" min="0" step="0.01" value="<?php echo htmlspecialchars($annonce['loyer_location_chez_habitant']); ?>" required>
                    </div>

                    <div class="annonce-field">
                        <label for="surface_logement">Surface du logement en m²</label>
                        <input type="number" id="surface_logement" name="surface_logement" min="1" step="0.01" value="<?php echo htmlspecialchars($annonce['surface_logement']); ?>" required>
                    </div>

                    <div class="annonce-field">
                        <label for="surface_chambres">Surface des chambres en m²</label>
                        <input type="number" id="surface_chambres" name="surface_chambres" min="1" step="0.01" value="<?php echo htmlspecialchars($annonce['surface_chambres']); ?>" required>
                    </div>
                </div>

                <div class="annonce-form-grid trois-colonnes">
                    <div class="annonce-field">
                        <label for="nombre_chambre">Nombre de chambres</label>
                        <input type="number" id="nombre_chambre" name="nombre_chambre" min="1" value="<?php echo htmlspecialchars($annonce['nombre_chambre']); ?>" required>
                    </div>

                    <div class="annonce-field">
                        <label for="date_expiration">Date expiration</label>
                        <input type="date" id="date_expiration" name="date_expiration" value="<?php echo htmlspecialchars($annonce['date_expiration']); ?>" required>
                    </div>

                    <div class="annonce-field">
                        <label for="date_cloture">Date cloture</label>
                        <input type="date" id="date_cloture" name="date_cloture" value="<?php echo htmlspecialchars($annonce['date_cloture']); ?>" required>
                    </div>
                </div>

                <div class="annonce-field">
                    <label for="carte_coordonnee_GPS">Coordonnée GPS</label>
                    <input type="text" id="carte_coordonnee_GPS" name="carte_coordonnee_GPS" value="<?php echo htmlspecialchars($annonce['carte_coordonnee_GPS'] ?? ''); ?>" placeholder="45.764043, 4.835659">
                </div>

                <div class="annonce-field">
                    <label for="descriptions">Description</label>
                    <textarea id="descriptions" name="descriptions" rows="5" required><?php echo htmlspecialchars($annonce['description']); ?></textarea>
                </div>
            </fieldset>

            <fieldset>
                <legend>Modes de vie</legend>

                <div class="annonce-choice-grid">
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="1" <?php echo in_array(1, $selectedModes, true) ? 'checked' : ''; ?>><span>Calme</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="2" <?php echo in_array(2, $selectedModes, true) ? 'checked' : ''; ?>><span>Festif</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="3" <?php echo in_array(3, $selectedModes, true) ? 'checked' : ''; ?>><span>Etudiant</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="4" <?php echo in_array(4, $selectedModes, true) ? 'checked' : ''; ?>><span>Famille</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="5" <?php echo in_array(5, $selectedModes, true) ? 'checked' : ''; ?>><span>Travailleur</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="6" <?php echo in_array(6, $selectedModes, true) ? 'checked' : ''; ?>><span>Retraite</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="7" <?php echo in_array(7, $selectedModes, true) ? 'checked' : ''; ?>><span>Parent solo</span></label>
                </div>
            </fieldset>

            <fieldset>
                <legend>Regime alimentaire</legend>

                <div class="annonce-choice-grid">
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="1" <?php echo in_array(1, $selectedRegimes, true) ? 'checked' : ''; ?>><span>Omnivore</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="2" <?php echo in_array(2, $selectedRegimes, true) ? 'checked' : ''; ?>><span>Vegetarien</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="3" <?php echo in_array(3, $selectedRegimes, true) ? 'checked' : ''; ?>><span>Vegan</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="4" <?php echo in_array(4, $selectedRegimes, true) ? 'checked' : ''; ?>><span>Sans gluten</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="5" <?php echo in_array(5, $selectedRegimes, true) ? 'checked' : ''; ?>><span>Halal</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="6" <?php echo in_array(6, $selectedRegimes, true) ? 'checked' : ''; ?>><span>Casher</span></label>
                </div>
            </fieldset>
            <div class="annonce-actions">
                <a href="page_annonce.php" class="annonce-btn-secondaire">Annuler</a>
                <button type="submit" class="annonce-btn-principal">Enregistrer les modifications</button>
            </div>
        </form>
    </section>
</main>

<?php
include("../includes/footer.php");
?>

