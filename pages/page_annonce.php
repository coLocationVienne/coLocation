<?php
global $annonceDAO;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../init.php';
require_once(__DIR__ . "/../app/Views/partials/header.php");
use App\Core\Config;

if (empty($_SESSION['user_id'])) {
    header("Location: " . Config::url('auth/login') . "?error=erreur_connexion");
    exit();
}

$userId = (int) $_SESSION['user_id'];
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

// Fetch announcements using the DAO
$annonces = $annonceDAO->getAllAnouncesByUserId($userId);

?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px; max-width: 1200px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: #333; margin: 0;">Mes annonces</h1>
            <p class="text-muted mb-0">Gérez vos publications et suivez leur statut.</p>
        </div>
        <a href="<?php echo Config::url('/annonce/create'); ?>" class="btn btn-primary" style="border-radius: 8px; padding: 10px 20px; font-weight: 600;">
            <i class="fas fa-plus me-2"></i> Nouvelle annonce
        </a>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white p-3 mb-4 rounded shadow-sm border d-flex gap-3 align-items-center flex-wrap">
        <div style="flex: 1; min-width: 250px; position: relative;">
            <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #adb5bd;"></i>
            <input type="text" id="manageSearch" class="form-control" placeholder="Rechercher par titre ou ville..." style="padding-left: 35px; border-radius: 8px;">
        </div>
        <div style="width: 200px;">
            <select id="manageBudget" class="form-select" style="border-radius: 8px;">
                <option value="all">Tous les budgets</option>
                <option value="400">Max 400 €</option>
                <option value="500">Max 500 €</option>
                <option value="600">Max 600 €</option>
            </select>
        </div>
        <div class="text-muted ms-auto" id="resultCounter">
            <strong><?php echo count($annonces); ?></strong> annonce(s) au total
        </div>
    </div>

    <!-- Feedback Alerts -->
    <?php if ($success === 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 4px solid #28a745;">
            <i class="fas fa-check-circle me-2"></i> Votre annonce a été mise à jour avec succès.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error === 'unauthorized'): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 4px solid #dc3545;">
            <i class="fas fa-exclamation-triangle me-2"></i> Vous n'êtes pas autorisé à modifier cette annonce.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Announce Grid -->
    <?php if (empty($annonces)): ?>
        <div class="text-center py-5 bg-light rounded-4 border-2 border-dashed">
            <div class="mb-3"><i class="fas fa-home fa-3x text-light-emphasis"></i></div>
            <h4>Aucune annonce pour le moment</h4>
            <p class="text-muted">Commencez par créer votre première annonce de colocation.</p>
            <a href="<?php echo Config::url('/annonce/create'); ?>" class="btn btn-outline-primary mt-2">Créer une annonce</a>
        </div>
    <?php else: ?>
        <div class="row g-4" id="manageGrid">
            <?php foreach ($annonces as $a): ?>
                <?php
                    $photoUrl = $a->getPhoto();
                    $image = !empty($photoUrl)
                        ? (strpos($photoUrl, 'http') === 0 ? $photoUrl : '/coLocation/' . $photoUrl)
                        : 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=400&q=80';
                    
                    $searchText = strtolower($a->getTitre() . ' ' . $a->getVille());
                ?>
                <div class="col-md-6 col-lg-4 manage-card-wrapper" data-search="<?php echo htmlspecialchars($searchText); ?>" data-price="<?php echo $a->getLoyerHabitant(); ?>">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 12px; transition: transform 0.2s;">
                        <div style="position: relative; height: 180px;">
                            <img src="<?php echo htmlspecialchars($image); ?>" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="Aperçu">
                            <div style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.6); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                <?php echo number_format($a->getLoyerHabitant(), 0, ',', ' '); ?> €
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light text-primary border" style="font-weight: 500;">
                                    <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($a->getVille()); ?>
                                </span>
                                <small class="text-muted"><?php echo htmlspecialchars($a->getSurfaceLogement()); ?> 
                                m²</small>
                            </div>
                            <h5 class="card-title text-dark" style="font-weight: 600; font-size: 1.1rem;"><?php echo htmlspecialchars($a->getTitre()); ?></h5>
                            
                            <div class="mt-auto pt-3 border-top d-flex gap-2">
                                <a href="<?php echo Config::url('annonce/edit?id=' . $a->getId()); ?>" class="btn btn-sm btn-light border flex-grow-1" style="font-weight: 500;">
                                    <i class="fas fa-edit me-1 text-primary"></i> Editer
                                </a>
                                <a href="modifier_photos.php?id_annonce=<?php echo $a->getId(); ?>" class="btn btn-sm btn-light border" title="Photos">
                                    <i class="fas fa-camera text-secondary"></i>
                                </a>
                                <a href="<?php echo Config::url("annonce/show?id="). $a->getId(); ?>" class="btn btn-sm btn-light border" title="Voir l'aperçu">
                                    <i class="fas fa-external-link-alt text-info"></i>
                                </a>
                                <form action="/coLocation/annonce/delete" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette annonce ?');" class="d-inline">
                                    <?php echo \App\Core\Token::field(); ?>
                                    <input type="hidden" name="id_annonce" value="<?php echo $a->getId(); ?>">
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div id="noMatchMessage" class="text-center py-5 d-none">
            <i class="fas fa-search fa-2x text-muted mb-2"></i>
            <p class="text-muted">Aucune annonce ne correspond à votre recherche.</p>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('manageSearch');
    const budgetSelect = document.getElementById('manageBudget');
    const cards = document.querySelectorAll('.manage-card-wrapper');
    const counter = document.getElementById('resultCounter');
    const noMatch = document.getElementById('noMatchMessage');

    function performFilter() {
        const query = searchInput.value.toLowerCase().trim();
        const maxPrice = budgetSelect.value === 'all' ? Infinity : Number(budgetSelect.value);
        let visibleCount = 0;

        cards.forEach(card => {
            const matchesSearch = card.dataset.search.includes(query);
            const matchesPrice = Number(card.dataset.price) <= maxPrice;
            const isVisible = matchesSearch && matchesPrice;
            
            card.classList.toggle('d-none', !isVisible);
            if (isVisible) visibleCount++;
        });

        counter.innerHTML = `<strong>${visibleCount}</strong> annonce(s) trouvée(s)`;
        noMatch.classList.toggle('d-none', visibleCount > 0);
    }

    searchInput.addEventListener('input', performFilter);
    budgetSelect.addEventListener('change', performFilter);
});
</script>

<?php require_once(__DIR__ . "/../app/Views/partials/footer.php"); ?>
