<?php
use App\Core\Config;
use App\core\Token;
include __DIR__ . "/../partials/header.php";
?>

<main class="creer-annonce-page" style="margin-top: 80px;">
    <section class="creer-annonce-hero container mb-4">
        <div>
            <p class="annonce-kicker">Modifier une annonce</p>
            <div class="d-flex justify-content-between align-items-center">
                <h1>Modifier votre annonce</h1>
                <a href="<?php echo Config::url('pages/modifier_photos.php'); ?>?id_annonce=<?php echo $annonce->getId(); ?>" class="btn btn-primary">
                    <i class="fas fa-camera"></i> Gérer les photos
                </a>
            </div>
            <p>Mettez à jour toutes les informations visibles sur votre annonce.</p>
        </div>
    </section>

    <section class="annonce-form-section container"> <!--il est ici-->
        <form action="<?php echo Config::url('annonce/update'); ?>" method="POST" class="annonce-form card p-4 shadow-sm">
            <input type="hidden" name="id_annonce" value="<?php echo $annonce->getId(); ?>">
            <input type="hidden" name="Token" value="<?php echo htmlspecialchars($_SESSION['token']);?>">
   
            <fieldset class="mb-4">
                <legend class="h5 mb-3 border-bottom pb-2">Informations du logement</legend>

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="titre" class="form-label">Titre de l'annonce</label>
                        <input type="text" id="titre" name="titre" class="form-control" value="<?php echo htmlspecialchars($annonce->getTitre()); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="rue_nom" class="form-label">Numéro et nom de rue</label>
                        <input type="text" id="rue_nom" name="rue_nom" class="form-control" value="<?php echo htmlspecialchars($annonce->getAdresse1()); ?>" required>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4 mb-3">
                        <label for="appartement" class="form-label">Numéro appartement</label>
                        <input type="text" id="appartement" name="appartement" class="form-control" value="<?php echo htmlspecialchars($annonce->getAdresse2() ?? ''); ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="batiment" class="form-label">Numéro de bâtiment</label>
                        <input type="text" id="batiment" name="batiment" class="form-control" value="<?php echo htmlspecialchars($annonce->getAdresse3() ?? ''); ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="codePostal" class="form-label">Code postal</label>
                        <input type="number" id="codePostal" name="codePostal" class="form-control" value="<?php echo htmlspecialchars($annonce->getCodePostal()); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="infocomplementaire" class="form-label">Information complémentaire</label>
                    <input type="text" id="infocomplementaire" name="infocomplementaire" class="form-control" value="<?php echo htmlspecialchars($annonce->getAdresse4() ?? ''); ?>">
                </div>

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="ville" class="form-label">Ville</label>
                        <input type="text" id="ville" name="ville" class="form-control" value="<?php echo htmlspecialchars($annonce->getVille()); ?>" required>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4 mb-3">
                        <label for="loyer" class="form-label">Loyer mensuel</label>
                        <div class="input-group">
                            <input type="number" id="loyer" name="loyer" class="form-control" min="0" step="0.01" value="<?php echo htmlspecialchars($annonce->getLoyerHabitant()); ?>" required>
                            <span class="input-group-text">€</span>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="surface_logement" class="form-label">Surface logement (m²)</label>
                        <input type="number" id="surface_logement" name="surface_logement" class="form-control" min="1" step="0.01" value="<?php echo htmlspecialchars($annonce->getSurfaceLogement()); ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="surface_chambres" class="form-label">Surface chambres (m²)</label>
                        <input type="number" id="surface_chambres" name="surface_chambres" class="form-control" min="1" step="0.01" value="<?php echo htmlspecialchars($annonce->getSurfaceChambres()); ?>" required>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4 mb-3">
                        <label for="nombre_chambre" class="form-label">Nombre de chambres</label>
                        <input type="number" id="nombre_chambre" name="nombre_chambre" class="form-control" min="1" value="<?php echo htmlspecialchars($annonce->getNombreChambre()); ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="date_expiration" class="form-label">Date expiration</label>
                        <input type="date" id="date_expiration" name="date_expiration" class="form-control" value="<?php echo htmlspecialchars($annonce->getDateExpiration() ?? ''); ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="date_cloture" class="form-label">Date clôture</label>
                        <input type="date" id="date_cloture" name="date_cloture" class="form-control" value="<?php echo htmlspecialchars($annonce->getDateCloture() ?? ''); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="carte_coordonnee_GPS" class="form-label">Coordonnée GPS</label>
                    <input type="text" id="carte_coordonnee_GPS" name="carte_coordonnee_GPS" class="form-control" value="<?php echo htmlspecialchars($annonce->getGps() ?? ''); ?>" placeholder="45.764043, 4.835659">
                </div>

                <div class="mb-3">
                    <label for="descriptions" class="form-label">Description</label>
                    <textarea id="descriptions" name="descriptions" class="form-control" rows="5" required><?php echo htmlspecialchars($annonce->getDescription()); ?></textarea>
                </div>
            </fieldset>

            <fieldset class="mb-4">
                <legend class="h5 mb-3 border-bottom pb-2">Modes de vie</legend>
                <div class="row">
                    <?php 
                    $modes = [1 => 'Calme', 2 => 'Festif', 3 => 'Étudiant', 4 => 'Famille', 5 => 'Travailleur', 6 => 'Retraite', 7 => 'Parent solo'];
                    foreach ($modes as $id => $label): ?>
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mode_vie[]" value="<?php echo $id; ?>" id="mode_<?php echo $id; ?>" <?php echo in_array($id, $selectedModes) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="mode_<?php echo $id; ?>"><?php echo $label; ?></label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <fieldset class="mb-4">
                <legend class="h5 mb-3 border-bottom pb-2">Régime alimentaire</legend>
                <div class="row">
                    <?php 
                    $regimes = [1 => 'Omnivore', 2 => 'Végétarien', 3 => 'Végan', 4 => 'Sans gluten', 5 => 'Halal', 6 => 'Casher'];
                    foreach ($regimes as $id => $label): ?>
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="regime[]" value="<?php echo $id; ?>" id="regime_<?php echo $id; ?>" <?php echo in_array($id, $selectedRegimes) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="regime_<?php echo $id; ?>"><?php echo $label; ?></label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo Config::url('pages/page_annonce.php'); ?>" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </div>
        </form>
    </section>
</main>

<?php include __DIR__ . "/../partials/footer.php"; ?>
