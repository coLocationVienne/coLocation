<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}
if(!isset($_SESSION['user_id'])){
    header("Location: connexion.php?error=error_connexion");
    exit();
}

include("../includes/header.php");

$message = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
$errorMessages = [
    'missing_fields' => 'Veuillez remplir tous les champs obligatoires.',
    'invalid_email' => 'Veuillez saisir une adresse email valide.',
    'server_error' => 'Une erreur est survenue pendant la publication de l’annonce.',
];
?>

<main class="creer-annonce-page">
    <section class="creer-annonce-hero">
        <div>
            <p class="annonce-kicker">Nouvelle annonce</p>
            <h1>Créer et publier une annonce</h1>
            <p>
                Ajoutez les informations importantes sur le logement, l'ambiance de la colocation,
                les modes de vie et les régimes alimentaires acceptés.
            </p>
        </div>

        <div class="annonce-conseil">
            <strong>Conseil</strong>
            <span>Plus votre annonce est précise, plus les futurs colocataires peuvent savoir si l'ambiance leur correspond.</span>
        </div>
    </section>

    <section class="annonce-form-section">
        <?php if ($message === '1'): ?>
            <div class="annonce-message" role="status">
                Votre annonce a bien été publiée.
            </div>
        <?php endif; ?>

        <?php if (!empty($error) && isset($errorMessages[$error])): ?>
            <div class="annonce-message" role="alert">
                <?php echo $errorMessages[$error]; ?>
            </div>
        <?php endif; ?>

        <form action="be/ajouter_annonce.php" method="POST" class="annonce-form">
            <fieldset>
                <legend>Informations du logement</legend>

                <div class="annonce-form-grid deux-colonnes">
                    <div class="annonce-field">
                        <label for="titre">Titre de l'annonce</label>
                        <input type="text" id="titre" name="titre" placeholder="Chambre lumineuse proche du centre" required>
                    </div>

                    <div class="annonce-field">
                        <label for="rue_nom">Numéro et nom de rue</label>
                        <input type="text" id="rue_nom" name="rue_nom" placeholder="60 rue Joyce Avenue" required>
                    </div>
                </div>

                <div class="annonce-form-grid trois-colonnes">
                    <div class="annonce-field">
                        <label for="appartement">Numéro appartement</label>
                        <input type="text" id="appartement" name="appartement" placeholder="Appartement 5">
                    </div>

                    <div class="annonce-field">
                        <label for="batiment">Numéro bâtiment</label>
                        <input type="text" id="batiment" name="batiment" placeholder="Bâtiment 3">
                    </div>

                    <div class="annonce-field">
                        <label for="codePostal">Code postal</label>
                        <input type="number" id="codePostal" name="codePostal" placeholder="86100" required>
                    </div>
                </div>

                <div class="annonce-field">
                    <label for="infocomplementaire">Information complémentaire</label>
                    <input type="text" id="infocomplementaire" name="infocomplementaire" placeholder="Étage, résidence, accès...">
                </div>

                <div class="annonce-field">
                    <label for="ville">Ville</label>
                    <select id="ville" name="ville" required>
                        <option value="" selected disabled hidden>Choisissez une ville</option>
                        <option value="Châtellerault">Châtellerault</option>
                        <option value="Buxerolles">Buxerolles</option>
                        <option value="Jaunay-Marigny">Jaunay-Marigny</option>
                        <option value="Chauvigny">Chauvigny</option>
                        <option value="Luchapt">Luchapt</option>
                        <option value="Naintré">Naintré</option>
                        <option value="Adriers">Adriers</option>
                        <option value="Charroux">Charroux</option>
                        <option value="Monts-sur-Guesnes">Monts-sur-Guesnes</option>
                        <option value="Château-Larcher">Château-Larcher</option>
                        <option value="Lusignan">Lusignan</option>
                        <option value="Chasseneuil-du-Poitou">Chasseneuil-du-Poitou</option>
                        <option value="Lussac-les-Châteaux">Lussac-les-Châteaux</option>
                        <option value="Arçay">Arçay</option>
                        <option value="Anché">Anché</option>
                        <option value="Amberre">Amberre</option>
                    </select>
                </div>

                <div class="annonce-form-grid trois-colonnes">
                    <div class="annonce-field">
                        <label for="loyer">Loyer mensuel</label>
                        <input type="number" id="loyer" name="loyer" min="0" placeholder="420" required>
                    </div>

                    <div class="annonce-field">
                        <label for="surface_logement">Surface du logement en m²</label>
                        <input type="number" id="surface_logement" name="surface_logement" min="1" placeholder="80" required>
                    </div>

                    <div class="annonce-field">
                        <label for="surface_chambres">Surface des chambres en m²</label>
                        <input type="number" id="surface_chambres" name="surface_chambres" min="1" placeholder="18" required>
                    </div>
                </div>

                <div class="annonce-form-grid trois-colonnes">
                    <div class="annonce-field">
                        <label for="nombre_chambre">Nombre de chambres</label>
                        <input type="number" id="nombre_chambre" name="nombre_chambre" min="1" placeholder="1" required>
                    </div>

                    <div class="annonce-field">
                        <label for="disponibilite">Disponible à partir du</label>
                        <input type="date" id="disponibilite" name="disponibilite" required>
                    </div>

                    <div class="annonce-field">
                        <label for="date_expiration">Date expiration</label>
                        <input type="date" id="date_expiration" name="date_expiration" required>
                    </div>
                </div>

                <div class="annonce-field">
                    <label for="contact">Email de contact</label>
                    <input type="email" id="contact" name="contact" placeholder="email@exemple.com" required>
                </div>

                <div class="annonce-field">
                    <label for="descriptions">Description</label>
                    <textarea id="descriptions" name="descriptions" rows="4" placeholder="Décrivez la chambre, les espaces communs, les transports, l'ambiance..." required></textarea>
                </div>
            </fieldset>

            <fieldset class="form-cache">
                <legend>Modes de vie</legend>

                <div class="annonce-choice-grid">
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="calme"><span>Calme</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="social"><span>Social</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="teletravail"><span>Télétravail accepté</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="soirees"><span>Soirées occasionnelles</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="menage"><span>Ménage partagé</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="fumeur"><span>Fumeur</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="non_fumeur"><span>Non-fumeur</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="sportif"><span>Sportif</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="fetard"><span>Fêtard</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="gamer"><span>Gamer</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="ecolo"><span>Écolo</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="casanier"><span>Casanier</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="animaux"><span>Animaux acceptés</span></label>
                </div>
            </fieldset>

            <fieldset class="form-cache">
                <legend>Régime alimentaire</legend>

                <div class="annonce-choice-grid">
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="omnivore"><span>Omnivore</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="vegetarien"><span>Végétarien</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="vegan"><span>Végan</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="sans_gluten"><span>Sans gluten</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="halal"><span>Halal</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="casher"><span>Casher</span></label>
                </div>
            </fieldset>

            <fieldset class="form-cache">
                <legend>Garant</legend>

                <div class="annonce-choice-grid">
                    <label class="annonce-choice"><input type="radio" name="garant" value="oui"><span>Garant demandé</span></label>
                    <label class="annonce-choice"><input type="radio" name="garant" value="non"><span>Pas de garant demandé</span></label>
                </div>
            </fieldset>

            <div class="annonce-actions">
                <button type="reset" class="annonce-btn-secondaire">Effacer</button>
                <button type="submit" class="annonce-btn-principal">Publier l'annonce</button>
            </div>
        </form>
    </section>
</main>

<?php
include("../includes/footer.php");
?>
