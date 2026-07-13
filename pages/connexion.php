<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['isLoggedin'])) {
    header("Location: ../index.php");
    exit();
}

require("../includes/dbConnection.php");
$dbConn = getDbConnection();

$loginError = $_GET['error'] ?? '';
$registered = $_GET['registered'] ?? '';
$redirect = $_GET['redirect'] ?? '';

$loginErrorMessages = [
    'invalid_credentials' => 'Email ou mot de passe incorrect. Veuillez reessayer.',
    'missing_fields' => 'Veuillez remplir votre email et votre mot de passe.',
    'error_connexion' =>'vous devez vous connecter pour creer une annonce',
    'login_required' => 'Veuillez vous connecter pour acceder a votre profil.',
];

include("../includes/header.php");
?>
<section class="login-page">
    <div class="login-card">
        <h1>Bienvenue</h1>
        <p class="login-subtitle">Connectez vous sur votre compte.</p>

        <form action="be/login_process.php" method="POST" class="login-form">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
            
            <div class="form-group">
                <label for="email">Adress email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="you@example.com"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Entrez votre mot de passe"
                    required
                >
            </div>

            <div class="login-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    se souvenir de moi
                </label>
                <a href="forgot-password.php">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="login-button">se connecter</button>
        </form>

        <p class="signup-link">
            vous n'avez pas compte ? 
            <a href="register.php">Créez-un</a>
        </p>
    </div>
</section>

<?php if (isset($loginErrorMessages[$loginError])): ?>
    <script>
        window.addEventListener("load", function () {
            Swal.fire({
                title: "Connexion echouee",
                text: "<?php echo htmlspecialchars($loginErrorMessages[$loginError], ENT_QUOTES); ?>",
                icon: "error",
                confirmButtonText: "Reessayer"
            });
        });
    </script>
<?php endif; ?>

<?php if ($registered === '1'): ?>
    <script>
        window.addEventListener("load", function () {
            Swal.fire({
                title: "Compte cree",
                text: "Votre compte a bien ete cree. Vous pouvez maintenant vous connecter.",
                icon: "success",
                confirmButtonText: "Se connecter"
            });
        });
    </script>
<?php endif; ?>

<?php 
include("../includes/footer.php");
?>
