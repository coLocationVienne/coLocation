<?php
include("../includes/header.php");

$registerError = $_GET['error'] ?? '';
$registerErrorMessages = [
    'missing_fields' => 'Veuillez remplir tous les champs obligatoires.',
    'password_mismatch' => 'Les mots de passe ne correspondent pas.',
    'email_exists' => 'Un compte existe deja avec cette adresse email.',
    'invalid_email' => 'Veuillez saisir une adresse email valide.',
    'server_error' => 'Une erreur est survenue pendant la creation du compte.',
];
?>

<section class="login-page register-page">
    <div class="login-card register-card">
        <h1>Créer un compte</h1>
        <p class="login-subtitle">Rejoignez Colocation Vienne et trouvez une colocation qui vous ressemble.</p>

        <form action="be/register_process.php" method="POST" class="login-form">
            <div class="register-grid">
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
                </div>

                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required>
            </div>

            <div class="register-grid">
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Minimum 6 caractères" minlength="6" required>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirmer</label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Répétez le mot de passe" minlength="6" required>
                </div>
            </div>

            <div class="register-grid">
                <div class="form-group">
                    <label for="date_naissance">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" required>
                </div>

                <div class="form-group">
                    <label for="situation_professionnel">Situation professionnelle</label>
                    <select id="situation_professionnel" name="situation_professionnel" required>
                        <option value="" selected disabled>Choisissez...</option>
                        <option value="Étudiant">Étudiant(e)</option>
                        <option value="Salarié">Salarié(e)</option>
                        <option value="Indépendant">Indépendant / Freelance</option>
                        <option value="Recherche emploi">En recherche d'emploi</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
            </div>

            <div class="register-grid">
                <div class="form-group">
                    <label for="salaire_mensuel_net">Salaire mensuel net</label>
                    <input type="number" id="salaire_mensuel_net" name="salaire_mensuel_net" min="0" max="999.99" step="0.01" placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="revenu_fiscal">Revenu fiscal</label>
                    <input type="text" id="revenu_fiscal" name="revenu_fiscal" placeholder="Ex: 15000€">
                </div>
            </div>

            <div class="login-options">
                <label class="remember-me">
                    <input type="checkbox" name="garant" value="1">
                    J'ai un garant
                </label>
            </div>

            <button type="submit" class="login-button">Créer mon compte</button>
        </form>

        <p class="signup-link">
            Vous avez déjà un compte ?
            <a href="connexion.php">Connectez-vous</a>
        </p>
    </div>
</section>

<?php if (isset($registerErrorMessages[$registerError])): ?>
    <script>
        window.addEventListener("load", function () {
            Swal.fire({
                title: "Inscription impossible",
                text: "<?php echo htmlspecialchars($registerErrorMessages[$registerError], ENT_QUOTES); ?>",
                icon: "error",
                confirmButtonText: "Reessayer"
            });
        });
    </script>
<?php endif; ?>

<?php
include("../includes/footer.php");
?>
