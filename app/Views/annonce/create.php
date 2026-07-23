<?php
	
	use App\Core\Config;
	use App\core\Token;

	include __DIR__ . "/../partials/header.php";
	
	$error = $_GET['error'] ?? '';
	$errorMessages = [
	    'missing_fields' => 'Veuillez remplir tous les champs obligatoires.',
	    'server_error' => 'Une erreur est survenue pendant la publication de l\'annonce.',
	];
	?>
	
	<main class="creer-annonce-page" style="margin-top: 100px;">
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
	        <?php if (!empty($error) && isset($errorMessages[$error])): ?>
	            <div class="alert alert-danger" role="alert" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
	                <?php echo $errorMessages[$error]; ?>
	            </div>
	        <?php endif; ?>
	
	        <form action="<?php echo Config::url('annonce/create'); ?>" method="POST" class="annonce-form">
				<input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']);?>">
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
	                        <label for="batiment">Numéro de bâtiment</label>
	                        <input type="text" id="batiment" name="batiment" placeholder="Bâtiment 3">
	                    </div>
	
	                    <div class="annonce-field">
	                        <label for="codePostal">Code postal</label>
	                        <input type="number" id="codePostal" name="codePostal" placeholder="86100" required>
	                    </div>
	                </div>
	
	                <div class="annonce-field">
	                    <label for="infocomplementaire">Information complémentaire</label>
	                    <input type="text" id="infocomplementaire" name="infocomplementaire" placeholder="info complémentaire...">
	                </div>
	
	                <div class="annonce-field">
	                    <label for="ville">Ville</label>
	                    <select id="ville" name="ville" required>
	                        <option value="" selected disabled hidden>Choisissez une ville</option>
	                        <option value="Chatellerault">Chatellerault</option>
	                        <option value="Buxerolles">Buxerolles</option>
	                        <option value="Jaunay-Marigny">Jaunay-Marigny</option>
	                        <option value="Chauvigny">Chauvigny</option>
	                        <option value="Luchapt">Luchapt</option>
	                        <option value="Naintré">Naintré</option>
	                        <option value="Adriers">Adriers</option>
	                        <option value="Charroux">Charroux</option>
	                        <option value="Monts-sur-Guesnes">Monts-sur-Guesnes</option>
	                        <option value="Chateau-Larcher">Chateau-Larcher</option>
	                        <option value="Lusignan">Lusignan</option>
	                        <option value="Chasseneuil-du-Poitou">Chasseneuil-du-Poitou</option>
	                        <option value="Lussac-les-Châteaux">Lussac-les-Châteaux</option>
	                        <option value="Arsay">Arsay</option>
	                        <option value="Ancha">Ancha</option>
	                        <option value="Amberre">Amberre</option>
	                    </select>
	                </div>
	
	                <div class="annonce-form-grid trois-colonnes">
	                    <div class="annonce-field">
	                        <label for="loyer">Loyer mensuel (€)</label>
	                        <input type="number" id="loyer" name="loyer" min="0" placeholder="420" required>
	                    </div>
	
	                    <div class="annonce-field">
	                        <label for="surface_logement">Surface du logement (m²)</label>
	                        <input type="number" id="surface_logement" name="surface_logement" min="1" placeholder="80" required>
	                    </div>
	
	                    <div class="annonce-field">
	                        <label for="surface_chambres">Surface des chambres (m²)</label>
	                        <input type="number" id="surface_chambres" name="surface_chambres" min="1" placeholder="18" required>
	                    </div>
	                </div>
	
	                <div class="annonce-form-grid trois-colonnes">
	                    <div class="annonce-field">
	                        <label for="nombre_chambre">Nombre de chambres</label>
	                        <input type="number" id="nombre_chambre" name="nombre_chambre" min="1" placeholder="1" required>
	                    </div>

	                    <div class="annonce-field">
	                        <label for="disponibilite">Disponible à partir du :</label>
	                        <input type="date" id="disponibilite" name="disponibilite" required>
	                    </div>
	
	                    <div class="annonce-field">
	                        <label for="date_expiration">Date d'expiration :</label>
	                        <input type="date" id="date_expiration" name="date_expiration" required>
	                    </div>
	                </div>

	                <div class="annonce-field">
	                    <label for="descriptions">Description</label>
	                    <textarea id="descriptions" name="descriptions" rows="4" placeholder="Décrivez la chambre, les espaces communs, les transports, l'ambiance..." required></textarea>
	                </div>
	            </fieldset>

	            <fieldset class="form-cache annonce-extra-fields">
	                <legend>Modes de vie</legend>
	                <div class="annonce-choice-grid">
	                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="1"><span>Calme</span></label>
	                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="2"><span>Festif</span></label>
	                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="3"><span>Etudiant</span></label>
	                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="4"><span>Famille</span></label>
	                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="5"><span>Travailleur</span></label>
	                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="6"><span>Retraite</span></label>
	                    <label class="annonce-choice"><input type="checkbox" name="mode_vie[]" value="7"><span>Parent solo</span></label>
	                </div>
	            </fieldset>
	
	            <fieldset class="form-cache annonce-extra-fields">
                <legend>Régime alimentaire</legend>
                <div class="annonce-choice-grid">
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="1"><span>Omnivore</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="2"><span>Végétarien</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="3"><span>Vegan</span></label>
                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="4"><span>Sans gluten</span></label>
	                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="5"><span>Halal</span></label>
	                    <label class="annonce-choice"><input type="checkbox" name="regime[]" value="6"><span>Casher</span></label>
	                </div>
	            </fieldset>
	
	            <div class="annonce-actions">
	                <button type="reset" class="annonce-btn-secondaire">Effacer</button>
	                <button type="submit" class="annonce-btn-principal" id="publishAnnonceButton">Publier l'annonce</button>
	            </div>
	        </form>
	    </section>
	</main>
	
	<script>
	    const annonceForm = document.querySelector(".annonce-form");
	    const publishAnnonceButton = document.getElementById("publishAnnonceButton");
	    const extraFields = Array.from(document.querySelectorAll(".annonce-extra-fields"));
	    let extraFieldsAreVisible = false;
	
	    if (annonceForm && publishAnnonceButton) {
	        annonceForm.addEventListener("submit", function (event) {
	            if (!extraFieldsAreVisible) {
	                event.preventDefault();
	
	                if (!annonceForm.reportValidity()) {
	                    return;
	                }
	
	                extraFields.forEach(function (field) {
	                    field.classList.remove("form-cache");
	                });
	
	                extraFieldsAreVisible = true;
	                publishAnnonceButton.textContent = "Valider et publier";
	                publishAnnonceButton.scrollIntoView({ behavior: "smooth", block: "center" });
	            }
	        });
	
	        annonceForm.addEventListener("reset", function () {
	            extraFieldsAreVisible = false;
	            extraFields.forEach(function (field) {
	                field.classList.add("form-cache");
	            });
	            publishAnnonceButton.textContent = "Publier l'annonce";
	        });
	    }
	</script>
	
	<?php include __DIR__ . "/../partials/footer.php"; ?>
	
