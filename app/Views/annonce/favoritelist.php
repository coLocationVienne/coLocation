<?php 
use App\Core\Config;
use App\Core\Token;

include __DIR__ . "/../partials/header.php"; 
?>

<div class="container mt-5 pt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mes Listes de Favoris</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createNewListModal">
            <i class="fas fa-plus me-2"></i>Créer une liste</button>
    </div>

    <?php if (empty($listes)): ?>
        <div class="card shadow-sm border-0 py-5">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-folder-open fa-4x text-light"></i>
                </div>
                <h5>Vous n'avez pas encore de liste.</h5>
                <p class="text-muted">Commencez par créer votre premier dossier pour organiser vos coups de cœur.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($listes as $liste): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                        <div class="card-body text-center py-4">
                            <div class="mb-3 text-primary">
                                <i class="fas fa-folder fa-3x"></i>
                            </div>
                            <h5 class="card-title text-truncate"><?php echo htmlspecialchars($liste->getTitreListe()); ?></h5>
                            <p class="small text-muted mb-4">Créée le <?php echo date('d/m/Y', strtotime($liste->getDateCreation())); ?></p>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary btn-sm" 
                                        onclick="viewAnnonces(<?php echo $liste->getIdListeFavoris(); ?>, '<?php echo addslashes(htmlspecialchars($liste->getTitreListe())); ?>')">
                                    Voir les annonces
                                </button>

                                <!-- SUPPRESSION LISTE -->
                                <form action="<?php echo Config::url('annonce/delete?id=' . $liste->getIdListeFavoris()); ?>" 
                                      method="POST" 
                                      onsubmit="return confirm('Supprimer cette liste ?');">
                                    <?php echo Token::field(); ?>
                                    <button type="submit" class="btn btn-link btn-sm text-danger text-decoration-none">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal: Create New List -->
<div class="modal fade" id="createNewListModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle liste</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- FORMULAIRE DE CREATION DE LISTE -->
            <form action="<?php echo Config::url('annonce/addAnnonce'); ?>" method="POST">

                <div class="modal-body">
                    <?php echo Token::field(); ?>
                    <div class="mb-3">
                        <label for="titre_liste" class="form-label">Nom de la liste</label>
                        <input type="text" name="new_liste_titre" id="titre_liste" class="form-control" placeholder="Ex: Proche de l'école, Avec jardin..." required>
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

<!-- Modal: View Annonces in List -->
<div class="modal fade" id="viewAnnoncesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listTitleDisplay">Annonces dans la liste</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <div id="annoncesContainer" class="row g-3"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .transition { transition: all 0.3s ease; }
</style>

<script>
function viewAnnonces(idListe, titre) {
    document.getElementById('listTitleDisplay').innerText = "Annonces : " + titre;
    const container = document.getElementById('annoncesContainer');
    container.innerHTML = '<div class="text-center py-5 w-100"><div class="spinner-border text-primary"></div></div>';

    const modal = new bootstrap.Modal(document.getElementById('viewAnnoncesModal'));
    modal.show();

    fetch("<?php echo Config::url('annonce/getAnnoncesJson'); ?>?id=" + idListe)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.annonces.length > 0) {
                container.innerHTML = '';
                data.annonces.forEach(annonce => {
                    const photo = annonce.photo 
                        ? '<?php echo Config::url(""); ?>' + annonce.photo 
                        : 'https://via.placeholder.com/300x200?text=Pas+de+photo';

                    container.innerHTML += `
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                <img src="${photo}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-1 text-truncate">${annonce.titre}</h6>
                                    <p class="text-primary fw-bold mb-2 small">${annonce.loyer} € / mois</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?php echo Config::url("annonce/show"); ?>?id=${annonce.id}" class="btn btn-sm btn-primary">Voir</a>
                                        <button onclick="removeAnnonce(${idListe}, ${annonce.id})" class="btn btn-sm btn-outline-danger border-0">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                });
            } else {
                container.innerHTML = '<div class="text-center py-5 w-100"><p class="text-muted">Aucune annonce dans cette liste.</p></div>';
            }
        });
}

function removeAnnonce(idListe, idAnnonce) {
    if (!confirm('Retirer cette annonce de la liste ?')) return;

    const formData = new FormData();
    formData.append('id_liste', idListe);
    formData.append('id_annonce', idAnnonce);
    formData.append('token', '<?php echo Token::generate(); ?>');

    fetch("<?php echo Config::url('annonce/removeAnnonceAjax'); ?>", { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                viewAnnonces(idListe, document.getElementById('listTitleDisplay').innerText.replace('Annonces : ', ''));
            }
        });
}
</script>

<?php include __DIR__ . "/../partials/footer.php"; ?>
