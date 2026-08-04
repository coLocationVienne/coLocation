<?php 

use App\Core\Config;
use App\Core\Token;

include __DIR__ . "/../partials/header.php"; 
?>

<style>
    .carousel-item img {
        cursor: zoom-in;
    }
    .modal-full-image {
        max-width: 95vw;
        max-height: 85vh;
        object-fit: contain;
    }
    .modal-content.transparent-modal {
        background: transparent;
        border: none;
    }
    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        padding: 15px 20px;
        font-size: 24px;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.3s;
        z-index: 1060;
    }
    .lightbox-nav:hover {
        background: rgba(0,0,0,0.8);
    }
    .lightbox-prev { left: 20px; }
    .lightbox-next { right: 20px; }
    .lightbox-counter {
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        font-weight: 600;
    }
</style>

<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4 shadow-sm">
                <?php if (count($photos) > 1): ?>
                    <!-- Bootstrap Image Carousel -->
                    <div id="annonceCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <?php foreach ($photos as $index => $p): ?>
                                <button type="button" data-bs-target="#annonceCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>" aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $index + 1; ?>"></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="carousel-inner">
                            <?php foreach ($photos as $index => $p): ?>
                                <?php 
                                    $pUrl = $p['url'];
                                    $displayPath = (strpos($pUrl, 'http') === 0 ? $pUrl : Config::url($pUrl));
                                ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="<?php echo $displayPath; ?>" class="d-block w-100" alt="Photo <?php echo $index + 1; ?>" style="height: 400px; object-fit: cover;" onclick="openGallery(<?php echo $index; ?>)">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#annonceCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#annonceCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>
                    </div>
                <?php else: ?>
                    <?php 
                        $mainPhoto = !empty($photos) ? $photos[0]['url'] : null;
                        $displayPhoto = $mainPhoto ? (strpos($mainPhoto, 'http') === 0 ? $mainPhoto : Config::url($mainPhoto)) : "https://via.placeholder.com/800x400?text=Pas+de+photo";
                    ?>
                    <img src="<?php echo $displayPhoto; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($annonce->getTitre()); ?>" style="height: 400px; object-fit: cover; cursor: zoom-in;" onclick="openGallery(0)">
                <?php endif; ?>

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 class="card-title h2"><?php echo htmlspecialchars($annonce->getTitre()); ?></h1>
                            <p class="text-muted"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($annonce->getVille()); ?></p>
                        </div>
                        <div class="text-end">
                            <h3 class="text-primary mb-0"><?php echo number_format($annonce->getLoyer(), 2); ?> €</h3>
                            <small class="text-muted">par mois</small>
                        </div>
                    </div>
                    <hr>
                    <h5 class="mb-3">Description</h5>
                    <p class="card-text text-secondary" style="line-height: 1.6;"><?php echo nl2br(htmlspecialchars($annonce->getDescription())); ?></p>
                    
                    <div class="row mt-4 g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-uppercase small fw-bold text-muted mb-2">Espace & Logement</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><i class="fas fa-ruler-combined text-primary me-2"></i> Surface totale: <strong><?php echo $annonce->getSurfaceLogement(); ?> m²</strong></li>
                                    <li><i class="fas fa-bed text-primary me-2"></i> Chambres: <strong><?php echo $annonce->getNombreChambre(); ?></strong></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-uppercase small fw-bold text-muted mb-2">Informations clés</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><i class="fas fa-calendar-alt text-primary me-2"></i> Publiée le: <strong><?php echo date('d/m/Y', strtotime($annonce->getDatePublication())); ?></strong></li>
                                    <li><i class="fas fa-user-shield text-primary me-2"></i> Annonce vérifiée</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comment Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-star text-warning me-2"></i>Avis et Commentaires</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($comments)): ?>
                        <div class="text-center py-4">
                            <i class="far fa-comment-dots fa-3x text-light mb-2"></i>
                            <p class="text-muted">Soyez le premier à donner votre avis !</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($comments as $c): ?>
                            <div class="mb-4 pb-3 border-bottom last-child-border-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div>
                                        <span class="fw-bold text-primary"><?php echo htmlspecialchars($c->getAuthorName()); ?></span>
                                        <span class="ms-2 text-warning">
                                            <?php 
                                                $stars = round($c->getNote());
                                                for($i=1; $i<=5; $i++) echo $i <= $stars ? '★' : '☆';
                                            ?>
                                        </span>
                                    </div>
                                    <span class="badge bg-light text-muted fw-normal"><?php echo date('d/m/Y', strtotime($c->getDate())); ?></span>
                                </div>
                                <p class="mb-0 text-secondary"><?php echo htmlspecialchars($c->getCommentaire()); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <hr class="my-4">
                    
                    <h6 class="mb-3">Laisser un commentaire</h6>
                    <?php if (!empty($_SESSION['isLoggedin'])): ?>
                        <form action="<?php echo Config::url('comment/add'); ?>" method="POST">
                            <?php echo Token::field(); ?>
                            <input type="hidden" name="id_annonce" value="<?php echo $id; ?>">
                            <input type="hidden" name="id_owner" value="<?php echo $annonce->getOwnerId(); ?>">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Note</label>
                                <select name="note" class="form-select form-select-sm w-auto">
                                    <option value="5">5 ★★★★★ (Excellent)</option>
                                    <option value="4">4 ★★★★☆ (Très bien)</option>
                                    <option value="3" selected>3 ★★★☆☆ (Bien)</option>
                                    <option value="2">2 ★★☆☆☆ (Moyen)</option>
                                    <option value="1">1 ★☆☆☆☆ (Mauvais)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <textarea name="commentaire" class="form-control" rows="3" placeholder="Votre avis sur cette colocation..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-sm">Publier mon avis</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-light border py-2 px-3 small">
                            <i class="fas fa-info-circle me-1"></i> <a href="<?php echo Config::url('auth/login'); ?>">Connectez-vous</a> pour laisser un commentaire.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($_SESSION['isLoggedin'])): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-body text-center">
                        <button type="button" class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#addToListeModal">
                            <i class="fas fa-heart me-2"></i> Ajouter aux favoris
                        </button>
                    </div>
                </div>
            <?php endif; ?>

        </div>

      
        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 100px; border-top: 4px solid #0d6efd;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Intéressé ?</h5>
                    <p class="text-muted small mb-4">Envoyez un message au propriétaire pour poser vos questions ou organiser une visite.</p>
                    
                    <?php if (!empty($_SESSION['isLoggedin'])): ?>
                        <a href="<?php echo Config::url('pages/messages.php?annonce_id=' . $annonce->getId() . '&with_user=' . $annonce->getOwnerId()); ?>" class="btn btn-primary w-100 py-2 mb-3 shadow-sm">
                            <i class="fas fa-paper-plane me-2"></i> 
                            <?php echo ($_SESSION['user_id'] == $annonce->getOwnerId()) ? "M'envoyer un message (Test)" : "Contacter le propriétaire"; ?>
                        </a>
                        
                        <?php if ($_SESSION['user_id'] == $annonce->getOwnerId()): ?>
                            <a href="<?php echo Config::url('pages/page_annonce.php'); ?>" class="btn btn-outline-success w-100 py-2 mb-3">
                                <i class="fas fa-calendar-check me-2"></i> Gérer les visites
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($_SESSION['user_id'] != $annonce->getOwnerId()): ?>
                            <hr id="visite">
                            <h6 class="mb-3">Planifier une visite</h6>
                            <?php if (empty($visitSlots)): ?>
                                <p class="text-muted small">Aucun créneau de visite disponible pour le moment.</p>
                            <?php else: ?>
                                <form action="<?php echo Config::url('visit/request'); ?>" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Choisir un créneau</label>
                                        <select name="id_creneauVisite" class="form-select form-select-sm" required>
                                            <?php foreach ($visitSlots as $slot): ?>
                                                <option value="<?php echo $slot->getId(); ?>">
                                                    <?php echo date('d/m/Y', strtotime($slot->getDateVisite())); ?> 
                                                    (<?php echo $slot->getHeureDebut(); ?> - <?php echo $slot->getHeureFin(); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="message" class="form-control form-control-sm" rows="2" placeholder="Un petit message pour le propriétaire..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary w-100 btn-sm">Demander une visite</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo Config::url('auth/login') . '?redirect=annonce/show?id=' . $id; ?>" class="btn btn-primary w-100 py-2 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                        </a>
                        <div class="alert alert-info py-2 px-3 small mb-3">
                            <i class="fas fa-info-circle me-1"></i> Vous devez être connecté pour contacter le propriétaire.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="imageLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content transparent-modal">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1070;"></button>
                
                <?php if (count($photos) > 1): ?>
                    <button class="lightbox-nav lightbox-prev" onclick="changeImage(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="lightbox-nav lightbox-next" onclick="changeImage(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                <?php endif; ?>

                <img src="" id="lightboxImage" class="modal-full-image img-fluid rounded shadow" alt="Enlarged view">
                
                <div class="lightbox-counter" id="lightboxCounter"></div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPhotoIndex = 0;
const allPhotos = <?php echo json_encode(array_map(function($p) {
    $pUrl = $p['url'];
    return (strpos($pUrl, 'http') === 0 ? $pUrl : Config::url($pUrl));
}, $photos)); ?>;

function openGallery(index) {
    if (allPhotos.length === 0) return;
    currentPhotoIndex = index;
    updateLightbox();
    const lightbox = new bootstrap.Modal(document.getElementById('imageLightbox'));
    lightbox.show();
}

function changeImage(direction) {
    currentPhotoIndex += direction;
    if (currentPhotoIndex >= allPhotos.length) currentPhotoIndex = 0;
    if (currentPhotoIndex < 0) currentPhotoIndex = allPhotos.length - 1;
    updateLightbox();
}

function updateLightbox() {
    const img = document.getElementById('lightboxImage');
    const counter = document.getElementById('lightboxCounter');
    img.src = allPhotos[currentPhotoIndex];
    counter.innerText = (currentPhotoIndex + 1) + " / " + allPhotos.length;
}

// Support keyboard navigation
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('imageLightbox');
    if (modal.classList.contains('show')) {
        if (e.key === 'ArrowLeft') changeImage(-1);
        if (e.key === 'ArrowRight') changeImage(1);
    }
});
</script>

<!-- Add to Liste Modal -->
<div class="modal fade" id="addToListeModal" tabindex="-1" aria-labelledby="addToListeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToListeModalLabel">Ajouter à mes favoris</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo Config::url('favoris/addAnnonce'); ?>" method="POST">
                <div class="modal-body">
                    <?php echo Token::field(); ?>
                    <input type="hidden" name="id_annonce" value="<?php echo $annonce->getId(); ?>">

                    <?php 
                        global $listeFavorisDAO;
                        $mesListes = [];
                        if (!empty($_SESSION['isLoggedin'])) {
                            $mesListes = $listeFavorisDAO->getListesByUserId($_SESSION['user_id']);
                        }
                    ?>

                    <?php if (!empty($mesListes)): ?>
                        <div class="mb-3">
                            <label for="id_liste" class="form-label">Choisir une liste existante :</label>
                            <select name="id_liste" id="id_liste" class="form-select">
                                <?php foreach ($mesListes as $liste): ?>
                                    <option value="<?php echo $liste->getIdListe(); ?>"><?php echo htmlspecialchars($liste->getNomListe()); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="text-center my-3">- OU -</div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="new_liste_nom" class="form-label">Créer une nouvelle liste :</label>
                        <input type="text" name="new_liste_nom" id="new_liste_nom" class="form-control" placeholder="Nom de la nouvelle liste (ex: Colocs avec jardin)">
                    </div>
                    <small class="text-muted">Si vous choisissez une liste existante ET que vous entrez un nom pour une nouvelle liste, la nouvelle liste sera prioritaire.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../partials/footer.php"; ?>
