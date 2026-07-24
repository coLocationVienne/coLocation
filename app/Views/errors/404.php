<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
use App\Core\Config;
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="container mt-5 pt-5 text-center" style="min-height: 60vh;">
    <div class="py-5">
        <h1 class="display-1 fw-bold text-primary">404</h1>
        <h2 class="mb-4">Page non trouvée</h2>
        <p class="lead text-muted mb-5">
            <?php echo isset($message) ? htmlspecialchars($message) : "La page que vous recherchez n'existe pas ou a été déplacée."; ?>
            <?php if (isset($url)): ?><br><small class="text-muted">(URL: <?php echo htmlspecialchars($url); ?>)</small><?php endif; ?>
        </p>
        <a href="<?php echo Config::url('home'); ?>" class="btn btn-primary btn-lg px-5">Retour à l'accueil</a>
    </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
