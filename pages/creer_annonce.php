<?php
include("../includes/header.php");

require("../includes/dbConnection.php");
$dbConn = getDbConnection();
$annoncePubliee = $_SERVER["REQUEST_METHOD"] === "POST";

$registererror = $_GET['error'] ?? '';
$registerErromessages = [
    'missing_fields' => 'Veuillez remplir tous les champs obligatoires.',
    'password_mismatch' => 'Les mots de passe ne correspondent pas.',
    'email_exists' => 'Un compte existe deja avec cette adresse email.',
    'invalid_email' => 'Veuillez saisir une adresse email valide.',
    'server_error' => 'Une erreur est survenue pendant la creation du compte.',
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
        <?php if ($annoncePubliee): ?>
            <div class="annonce-message" role="status">
                Votre annonce est prête à être publiée.
            </div>
        <?php endif; ?>

        <form action="ajouter_annonce.php" method="POST" class="annonce-form">
            <fieldset>
                <legend>Informations du logement</legend>

                <div class="annonce-form-grid deux-colonnes">

                    <div class="annonce-field">
                        <label for="titre">Titre de l'annonce</label>
                        <input type="text" id="titre" name="titre" placeholder="Chambre lumineuse proche du centre" required>
                    </div>

                    <div class="annonce-field">
                        <label for="rue_nom">N° de rue et nom de rue</label>
                        <input type="text" id="rue_nom" name="rue_nom" placeholder="60 rue Joyce Avenue" required>
                    </div>
                </div>

                <div class="annonce-field">
                        <label for="appartement">n° appartement</label>
                        <input type="text" id="appartement" name="appartement" placeholder=" app 5" >
                    </div>
                </div>

                <div class="annonce-field">
                        <label for="batiment">n° batiment</label>
                        <input type="text" id="batiment" name="batiment" placeholder="Vienne bat n°3">
                    </div>
                </div>

                <div class="annonce-field">
                        <label for="infocomplementaire">information complementaire</label>
                        <input type="text" id="infocomplementaire" name="infocomplementaire" placeholder="a savoir...">
                    </div>
                </div>
                            <div class="mb-4">
                                <label for="situation_pro" class="form-label">Ville</label>
                                <select id="situation_pro" name="situation_pro" class="form-select" required>
                                    <option value="" selected disabled hidden>Choisissez votre situation...</option>
                                    <option value="Châtellerault">Châtellerault </option>
                                    <option value="Buxerolles">Buxerolles</option>
                                    <option value="Jaunay-Marign">Jaunay-Marigny</option>
                                    <option value="Chauvigny">Chauvigny</option>
                                    <option value="Luchapt ">Luchapt </option>
                                    <option value="Naintré ">Naintré </option>
                                    <option value="Adriers">Adriers</option>
                                    <option value="Charroux">Charroux</option>
                                    <option value="Monts-sur-Guesnes">Monts-sur-Guesnes</option>
                                    <option value="Château-Larcher">Château-Larcher</option>
                                    <option value="Lusignan">Lusignan</option>
                                    <option value="Chasseneuil-du-Poitou">Chasseneuil-du-Poitou</option>
                                    <option value="Lussac-les-Châteaux">Lussac-les-Châteaux</option>
                                    <option value="Arçay ">Arçay </option>
                                    <option value="Anché">Anché</option>
                                    <option value="Amberre ">Amberre </option>
                                </select>
                            </div>

                <div class="annonce-field">
                        <label for="codePostal">Code Postal</label>
                        <input type="number" id="codePostal" name="codePostal" placeholder="ex:86100" required>
                    </div>
                </div>

                <div class="annonce-form-grid trois-colonnes">
                    <div class="annonce-field">
                        <label for="loyer">Loyer mensuel</label>
                        <input type="number" id="loyer" name="loyer" min="0" placeholder="420" required>
                    </div>

                    <div class="annonce-field">
                        <label for="surface">Surface en logement en m²</label>
                        <input type="number" id="surface_logement" name="surface_logement" min="1" placeholder="18" required>
                    </div>

                     <div class="annonce-field">
                        <label for="surface">Surface des chambres en m²</label>
                        <input type="number" id="surface_chambres" name="surface_chambres" min="1" placeholder="18" required>
                    </div>

                    <div class="annonce-field">
                        <label for="disponibilite">Disponible à partir du</label>
                        <input type="date" id="disponibilite" name="disponibilite" required>
                    </div>
                </div>

                <div class="annonce-field">
                        <label for="disponibilite">date expiration</label>
                        <input type="date" id="date_expiration" name="date_expiration" required>
                    </div>

                <div class="annonce-field">
                    <label for="descriptions">Description</label>
                    <textarea id="descriptions" name="descriptions" rows="2" placeholder="Décrivez la chambre, les espaces communs, les transports, l'ambiance..." required></textarea>
                </div>
                     
                



                </div>

            </fieldset>

            <fieldset>
                <legend>Modes de vie</legend>

                <div class="annonce-choice-grid">
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="calme"><span>Calme</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="social"><span>Social</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="teletravail"><span>Télétravail accepté</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="soirees"><span>Soirées occasionnelles</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="menage"><span>Ménage partagé</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="animaux"><span>fumeur</span></label>
                     <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="animaux"><span>non-fumeur</span></label>
                      <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="animaux"><span>sportif</span></label>
                     <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="animaux"><span>fetard</span></label> 
                     <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="animaux"><span>gamer</span></label>
                      <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="animaux"><span>ecolo</span></label>
                       <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="animaux"><span>casanier</span></label>
                </div>
            </fieldset>

            <fieldset>
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

            <fieldset>
                <legend>Publication</legend>

                <div class="annonce-form-grid deux-colonnes">
                    <div class="annonce-field">
                        <label for="contact">Email de contact</label>
                        <input type="email" id="contact" name="contact" placeholder="email@exemple.com" required>
                    </div>

                <div class="annonce-field">
                    <label for="message">Message aux futurs colocataires</label>
                    <textarea id="message" name="message" rows="4" placeholder="Expliquez le profil recherché et les habitudes importantes."></textarea>
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


