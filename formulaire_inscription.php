<?php 
include("includes/header.php");

?>

<main>

<form action="" method="POST" class="container mt1">
    <h2 class="text-center">Inscription</h2>
    <hr>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" id="prenom" name="prenom" class="form-control" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="date_naissance" class="form-label">Date de naissance</label>
            <input type="date" id="date_naissance" name="date_naissance" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="telephone" class="form-label">Numéro de téléphone</label>
            <input type="tel" id="telephone" name="telephone" class="form-control" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <input type="email" id="email" name="email" class="form-control" required>
    </div>

    <fieldset class="border p-3 mb-3">
        <legend class="w-auto px-2">Adresse postale</legend>
        
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="num_rue" class="form-label">N° de rue</label>
                <input type="text" id="num_rue" name="num_rue" class="form-control" required>
            </div>
            <div class="col-md-9">
                <label for="nom_rue" class="form-label">Nom de la rue</label>
                <input type="text" id="nom_rue" name="nom_rue" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="adresse_comp1" class="form-label">Complément d'adresse 1</label>
            <input type="text" id="adresse_comp1" name="adresse_comp1" class="form-control" placeholder="Appartement, étage, bâtiment...">
        </div>

        <div class="mb-3">
            <label for="adresse_comp2" class="form-label">Complément d'adresse 2 (Optionnel)</label>
            <input type="text" id="adresse_comp2" name="adresse_comp2" class="form-control">
        </div>

        <div class="row">
            <div class="col-md-4">
                <label for="code_postal" class="form-label">Code postal</label>
                <input type="text" id="code_postal" name="code_postal" class="form-control" required>
            </div>
            </div>
    </fieldset>

    <fieldset class="border p-3 mb-4">
        <legend class="w-auto px-2">Régime alimentaire (Plusieurs choix possibles)</legend>
        
        <div class="form-check mb-2">
            <input type="checkbox" id="regime_omni" name="regime" value="omnivore" class="form-check-input">
            <label for="regime_omni" class="form-check-label">Omnivore (Mange de tout)</label>
        </div>
        <div class="form-check mb-2">
            <input type="checkbox" id="regime_vege" name="regime" value="vegetarien" class="form-check-input">
            <label for="regime_vege" class="form-check-label">Végétarien(ne)</label>
        </div>
        <div class="form-check mb-2">
            <input type="checkbox" id="regime_vegan" name="regime" value="vegan" class="form-check-input">
            <label for="regime_vegan" class="form-check-label">Végan(e)</label>
        </div>
        <div class="form-check mb-2">
            <input type="checkbox" id="regime_sans_gluten" name="regime" value="sans_gluten" class="form-check-input">
            <label for="regime_sans_gluten" class="form-check-label">Sans gluten</label>
        </div>
        <div class="form-check">
            <input type="checkbox" id="regime_halal_kosher" name="regime" value="halal_kosher" class="form-check-input">
            <label for="regime_halal_kosher" class="form-check-label">Halal / Cascher</label>
        </div>
    </fieldset>

    <fieldset class="border p-3 mb-4">
        <legend class="w-auto px-2">Mode de vie (Plusieurs choix possibles)</legend>
        
        <div class="form-check mb-2">
            <input type="checkbox" id="hab_fumeur" name="mode_vie" value="fumeur" class="form-check-input">
            <label for="hab_fumeur" class="form-check-label">Fumeur / Fumeuse</label>
        </div>
        <div class="form-check mb-2">
            <input type="checkbox" id="hab_fetard" name="mode_vie" value="fetard" class="form-check-input">
            <label for="hab_fetard" class="form-check-label">Fêtard(e) (Aime recevoir / faire la fête)</label>
        </div>
        <div class="form-check mb-2">
            <input type="checkbox" id="hab_calme" name="mode_vie" value="calme" class="form-check-input">
            <label for="hab_calme" class="form-check-label">Calme / Adepte de la tranquillité</label>
        </div>
        <div class="form-check mb-2">
            <input type="checkbox" id="hab_animaux" name="mode_vie" value="animaux" class="form-check-input">
            <label for="hab_animaux" class="form-check-label">A des animaux de compagnie</label>
        </div>
        <div class="form-check">
            <input type="checkbox" id="hab_teletravail" name="mode_vie" value="teletravail" class="form-check-input">
            <label for="hab_teletravail" class="form-check-label">En télétravail régulier</label>
        </div>
    </fieldset>

    <fieldset>
<legend>situation pro</legend>
<div class="mb-4">
        <label for="situation_pro" class="form-label">Situation professionnelle</label>
        <select id="situation_pro" name="situation_pro" class="form-select" required>
            <option value="" selected disabled hidden>Choisissez votre situation...</option>
            <option value="etudiant">Étudiant(e)</option>
            <option value="salarie">Salarié(e)</option>
            <option value="independant">Indépendant / Freelance</option>
            <option value="recherche_emploi">En recherche d'emploi</option>
            <option value="autre">Autre</option>
        </select>
    </div>

   <div class="mb-4">
        <label class="form-label d-block">Avez-vous un garant ?</label>
        <div class="form-check form-check-inline">
            <input type="radio" id="garant_oui" name="garant" value="oui" class="form-check-input" required>
            <label for="garant_oui" class="form-check-label">Oui</label>
        </div>
        <div class="form-check form-check-inline">
            <input type="radio" id="garant_non" name="garant" value="non" class="form-check-input" required>
            <label for="garant_non" class="form-check-label">Non</label>
        </div>
    </div>
</fieldset>

    <button type="submit" class="btn btn-primary w-100 mt-3">S'inscrire</button>
</form>
</main>

<?php 
include("includes/footer.php");

?>