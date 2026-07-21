<?php include __DIR__ . "/../partials/header.php"; ?>

<div class="container mt-5 pt-5 text-center">
    <div class="py-5">
        <h1 class="display-1 fw-bold text-primary">404</h1>
        <h2 class="mb-4">Page non trouvée</h2>
        <p class="lead text-muted mb-5">
            <?php echo isset($message) ? htmlspecialchars($message) : "La page que vous recherchez n'existe pas ou a été déplacée."; ?>
        </p>
        <a href="/coLocation/home" class="btn btn-primary btn-lg px-5">Retour à l'accueil</a>
    </div>
</div>

<?php include __DIR__ . "/../partials/footer.php"; ?>
