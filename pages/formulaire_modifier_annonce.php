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

// $dbConn = getDbConnection();
// $idAnnonce = (int) $_GET['id_annonce'];
// $userId = (int) $_SESSION['user_id'];
// $error = $_GET['error'] ?? '';

require_once '../init.php';

 $idAnnonce = (int) $_GET['id_annonce'];
 $userId = (int) $_SESSION['user_id'];

$annonce = $annonceDAO->getFullById($idAnnonce);


if (!$annonce) {
    header("Location: page_annonce.phperror=unauthorized");
    exit();
}

// $modeStmt = $dbConn->prepare("SELECT id_mode_vie FROM annonce_mode_vie WHERE id_annonce = :id_annonce");
// $modeStmt->execute([':id_annonce' => $idAnnonce]);
 $selectedModes = $annonceDAO->avoirModeVie($idAnnonce);

// $regimeStmt = $dbConn->prepare("SELECT id_regime_alimentaire FROM annonce_regime_alimentaire WHERE id_annonce = :id_annonce");
// $regimeStmt->execute([':id_annonce' => $idAnnonce]);
 $selectedRegimes = $annonceDAO->avoirRegime($idAnnonce);



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
            <div class="d-flex justify-content-between align-items-center">
                <h1>Modifier votre annonce</h1>
                <a href="modifier_photos.php?id_annonce=<?php echo $idAnnonce; ?>" class="btn btn-primary" style="background: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 10px;">
                    <i class="fas fa-camera"></i> Gérer les photos
                </a>
            </div>
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
            <input type="hidden" name="id_annonce" value="<?php echo (int) $annonce->getId(); ?>">
   
            <fieldset>
                <legend>Informations du logement</legend>

                <div class="annonce-form-grid deux-colonnes">
                    <div class="annonce-field">
                        <label for="titre">Titre de l'annonce</label>
                        <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars ($annonce->getTitre()); ?>" required>
                    </div>

                    <div class="annonce-field">
                        <label for="rue_nom">Numero et nom de rue</label>
                        <input type="text" id="rue_nom" name="rue_nom" value="<?php echo htmlspecialchars($annonce->getAdresse1()); ?>" required>
                    </div>
                </div>

                <div class="annonce-form-grid trois-colonnes">
                    <div class="annonce-field">
                        <label for="appartement">Numero appartement</label>
                        <input type="text" id="appartement" name="appartement" value="<?php echo htmlspecialchars($annonce->getAdresse2() ?? ''); ?>">
                    </div>

                    <div class="annonce-field">
                        <label for="batiment">Numero de batiment</label>
                        <input type="text" id="batiment" name="batiment" value="<?php echo htmlspecialchars( $annonce->getAdresse3() ?? ''); ?>">
                    </div>

                    <div class="annonce-field">
                        <label for="codePostal">Code postal</label>
                        <input type="number" id="codePostal" name="codePostal" value="<?php echo htmlspecialchars( $annonce->getCodePostal()); ?>" required>
                    </div>
                </div>

                <div class="annonce-field">
                    <label for="infocomplementaire">Information complémentaire</label>
                    <input type="text" id="infocomplementaire" name="infocomplementaire" value="<?php echo htmlspecialchars($annonce->getAdresse4() ?? ''); ?>">
                </div>

                <div class="annonce-form-grid deux-colonnes">
                    <div class="annonce-field">
                        <label for="ville">Ville</label>
                        <input type="text" id="ville" name="ville" value="<?php echo htmlspecialchars($annonce->getVille()); ?>" required>
                    </div>
                </div>

                <div class="annonce-form-grid trois-colonnes">
                    <div class="annonce-field">
                        <label for="loyer">Loyer mensuel</label>
                        <input type="number" id="loyer" name="loyer" min="0" step="0.01" value="<?php echo htmlspecialchars($annonce->getLoyerHabitant()); ?>" required>
                    </div>

                    <div class="annonce-field">
                        <label for="surface_logement">Surface du logement en m²</label>
                        <input type="number" id="surface_logement" name="surface_logement" min="1" step="0.01" value="<?php echo htmlspecialchars($annonce->getSurfaceLogement()); ?>" required>
                    </div>

                    <div class="annonce-field">
                        <label for="surface_chambres">Surface des chambres en m²</label>
                        <input type="number" id="surface_chambres" name="surface_chambres" min="1" step="0.01" value="<?php echo htmlspecialchars($annonce->getSurfaceChambre()); ?>" required>
                    </div>
                </div>

                <div class="annonce-form-grid trois-colonnes">
                    <div class="annonce-field">
                        <label for="nombre_chambre">Nombre de chambres</label>
                        <input type="number" id="nombre_chambre" name="nombre_chambre" min="1" value="<?php echo htmlspecialchars($annonce->getNombreChambre()); ?>" required>
                    </div>

                    <div class="annonce-field">
                        <label for="date_expiration">Date expiration</label>
                        <input type="date" id="date_expiration" name="date_expiration" value="<?php echo htmlspecialchars($annonce->getDateExpiration() ?? ''); ?>" required>
                    </div>

                    <div class="annonce-field">
                        <label for="date_cloture">Date cloture</label>
                        <input type="date" id="date_cloture" name="date_cloture" value="<?php echo htmlspecialchars($annonce->getDateCloture() ?? ''); ?>" required>
                    </div>
                </div>

                <div class="annonce-field">
                    <label for="carte_coordonnee_GPS">Coordonnée GPS</label>
                    <input type="text" id="carte_coordonnee_GPS" name="carte_coordonnee_GPS" value="<?php echo htmlspecialchars($annonce->getGps() ?? ''); ?>" placeholder="45.764043, 4.835659">
                </div>

                <div class="annonce-field">
                    <label for="descriptions">Description</label>
                    <textarea id="descriptions" name="descriptions" rows="5" required><?php echo htmlspecialchars($annonce->getDescription()); ?></textarea>
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

