<?php 
use App\Core\Config;
use App\Core\Token;

include __DIR__ . "/../partials/header.php"; 
?>

<div class="container mt-5 pt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mes Listes de Favoris</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createListeModal">
            <i class="fas fa-plus me-2"></i> Nouvelle Liste
        </button>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                if ($_GET['success'] === 'liste_created') echo "La liste a été créée avec succès.";
                if ($_GET['success'] === 'liste_deleted') echo "La liste a été supprimée.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
                if ($_GET['error'] === 'invalid_token') echo "Jeton de sécurité invalide.";
                if ($_GET['error'] === 'creation_failed') echo "Échec de la création de la liste.";
                if ($_GET['error'] === 'delete_failed') echo "Échec de la suppression de la liste.";
                if ($_GET['error'] === 'empty_name') echo "Le nom de la liste ne peut pas être vide.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php if (empty($listes)): ?>
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-heart fa-4x text-light"></i>
                </div>
                <h3>Vous n'avez pas encore de listes de favoris</h3>
                <p class="text-muted">Créez votre première liste pour organiser vos annonces préférées.</p>
            </div>
        <?php else: ?>
            <?php foreach ($listes as $liste): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($liste->getTitreListe()); ?></h5>
                            <p class="card-text text-muted small">Organisez vos coups de cœur ici.</p>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center pb-3">
                            <a href="<?php echo Config::url('favoris/showListe') . '?id=' . $liste->getIdListeFavoris(); ?>" class="btn btn-outline-primary btn-sm">
                                Voir la liste
                            </a>
                            <form action="<?php echo Config::url('favoris/delete') . '?id=' . $liste->getIdListeFavoris(); ?>" method="POST" onsubmit="return confirm('Supprimer cette liste ?');">
                                <?php echo Token::field(); ?>
                                <button type="submit" class="btn btn-link text-danger p-0">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Create Liste Modal -->
<div class="modal fade" id="createListeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Créer une nouvelle liste</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo Config::url('favoris/create'); ?>" method="POST">
                <div class="modal-body">
                    <?php echo Token::field(); ?>
                    <div class="mb-3">
                        <label for="titre_liste" class="form-label">Nom de la liste</label>
                        <input type="text" name="titre_liste" id="titre_liste" class="form-control" placeholder="Ex: Proche de l'école, Avec balcon..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../partials/footer.php"; ?>
