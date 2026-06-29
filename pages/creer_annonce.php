<?php
include("../includes/header.php");

$annoncePubliee = $_SERVER["REQUEST_METHOD"] === "POST";
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

        <form action="" method="POST" class="annonce-form">
            <fieldset>
                <legend>Informations du logement</legend>

                <div class="annonce-form-grid deux-colonnes">
                    <div class="annonce-field">
                        <label for="titre">Titre de l'annonce</label>
                        <input type="text" id="titre" name="titre" placeholder="Chambre lumineuse proche du centre" required>
                    </div>

                    <div class="annonce-field">
                        <label for="ville">Ville ou quartier</label>
                        <input type="text" id="ville" name="ville" placeholder="Vienne centre" required>
                    </div>
                </div>

                <div class="annonce-form-grid trois-colonnes">
                    <div class="annonce-field">
                        <label for="loyer">Loyer mensuel</label>
                        <input type="number" id="loyer" name="loyer" min="0" placeholder="420" required>
                    </div>

                    <div class="annonce-field">
                        <label for="surface">Surface en m²</label>
                        <input type="number" id="surface" name="surface" min="1" placeholder="18" required>
                    </div>

                    <div class="annonce-field">
                        <label for="disponibilite">Disponible à partir du</label>
                        <input type="date" id="disponibilite" name="disponibilite" required>
                    </div>
                </div>

                <div class="annonce-field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="5" placeholder="Décrivez la chambre, les espaces communs, les transports, l'ambiance..." required></textarea>
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
                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="animaux"><span>Animaux acceptés</span></label>
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
                        <label for="photo">Lien d'une photo</label>
                        <input type="url" id="photo" name="photo" placeholder="https://...">
                    </div>
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