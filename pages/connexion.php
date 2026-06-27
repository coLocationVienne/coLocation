<?php
include("../includes/header.php");
?>
<section class="login-page">
    <div class="login-card">
        <h1>Bienvenu</h1>
        <p class="login-subtitle">Connectez vous sur votre compte.</p>

        <form action="login_process.php" method="POST" class="login-form">
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
<?php 
include("../includes/footer.php");
?>
