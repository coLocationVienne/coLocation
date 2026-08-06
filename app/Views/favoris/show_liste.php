<?php 
use App\Core\Config;
use App\Core\Token;

include __DIR__ . "/../partials/header.php"; 
?>

<div class="container mt-5 pt-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo Config::url('favoris/index'); ?>">Mes Listes</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($liste->getTitreListe()); ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?php echo htmlspecialchars($liste->getTitreListe()); ?></h1>
        <span class="badge bg-primary rounded-pill"><?php echo count($annonces); ?> annonce(s)</span>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                if ($_GET['success'] === 'annonce_removed_from_list') echo "L'annonce a été retirée de la liste.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
                if ($_GET['error'] === 'invalid_token') echo "Jeton de sécurité invalide.";
                if ($_GET['error'] === 'remove_from_list_failed') echo "Échec du retrait de l'annonce de la liste.";
                if ($_GET['error'] === 'invalid_list_or_annonce') echo "Liste ou annonce invalide.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php if (empty($annonces)): ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Cette liste est vide pour le moment.</p>
                <a href="<?php echo Config::url('/'); ?>" class="btn btn-primary">Parcourir les annonces</a>
            </div>
        <?php else: ?>
            <?php foreach ($annonces as $annonce): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            <img src="https://via.placeholder.com/400x200?text=Aperçu" class="card-img-top" alt="Aperçu" style="height: 200px; object-fit: cover;">
                            <form action="<?php echo Config::url('favoris/removeAnnonce'); ?>" method="POST" class="position-absolute top-0 end-0 m-2">
                                <?php echo Token::field(); ?>
                                <input type="hidden" name="id_annonce" value="<?php echo $annonce->getId(); ?>">
                                <input type="hidden" name="id_liste" value="<?php echo $liste->getIdListeFavoris(); ?>">
                                <button type="submit" class="btn btn-danger btn-sm rounded-circle" title="Retirer de la liste">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-truncate"><?php echo htmlspecialchars($annonce->getTitre()); ?></h5>
                            <p class="card-text text-primary fw-bold mb-1"><?php echo number_format($annonce->getLoyer(), 2); ?> € / mois</p>
                            <p class="card-text text-muted small"><i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($annonce->getVille()); ?></p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <a href="<?php echo Config::url('annonce/show') . '?id=' . $annonce->getId(); ?>" class="btn btn-outline-primary btn-sm w-100">
                                Voir l'annonce
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . "/../partials/footer.php"; ?>
