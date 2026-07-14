<?php
require_once "../includes/header.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/** @var \colocation\AnnonceDAO $annonceDAO */
$annonce = $annonceDAO->getFullById($id);

if (!$annonce) {
    echo "<div class='container mt-5 pt-5'><div class='alert alert-danger'>Annonce non trouvée.</div></div>";
    require_once "../includes/footer.php";
    exit();
}

/** @var \colocation\CommentDAO $commentDAO */
$comments = $commentDAO->getByAnnonceId($id);
$photo = $annonce->getPhoto() ? (strpos($annonce->getPhoto(), 'http') === 0 ? $annonce->getPhoto() : "../" . $annonce->getPhoto()) : "https://via.placeholder.com/800x400?text=Pas+de+photo";
?>

<div class="container mt-5 pt-5">
    <!-- Alert Notifications -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> Votre commentaire a été publié avec succès !
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> 
            Une erreur est survenue lors de la publication : <?php echo htmlspecialchars($_GET['error']); ?>
            <?php if (isset($_GET['msg'])) echo "<br><small>" . htmlspecialchars($_GET['msg']) . "</small>"; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="card mb-4 shadow-sm">
                <img src="<?php echo $photo; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($annonce->getTitre()); ?>" style="height: 400px; object-fit: cover;">
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

                    <?php if (!empty($_SESSION['isLoggedin'])): ?>
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6 class="mb-3">Laisser un avis</h6>
                            <form action="be/add_comment.php" method="POST">
                                <input type="hidden" name="id_annonce" value="<?php echo $id; ?>">
                                <input type="hidden" name="id_owner" value="<?php echo $annonce->getOwnerId(); ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Votre note :</label>
                                    <select name="note" class="form-select form-select-sm" style="width: auto;">
                                        <option value="5.0">5 étoiles (Excellent)</option>
                                        <option value="4.0">4 étoiles (Très bien)</option>
                                        <option value="3.0">3 étoiles (Bien)</option>
                                        <option value="2.0">2 étoiles (Moyen)</option>
                                        <option value="1.0">1 étoile (Mauvais)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <textarea name="commentaire" class="form-control border-0" rows="3" placeholder="Partagez votre expérience..." required></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">Publier l'avis</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 100px; border-top: 4px solid #0d6efd;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Intéressé ?</h5>
                    <p class="text-muted small mb-4">Envoyez un message au propriétaire pour poser vos questions ou organiser une visite.</p>
                    
                    <?php if (!empty($_SESSION['isLoggedin'])): ?>
                        <a href="messages.php?annonce_id=<?php echo $id; ?>&with_user=<?php echo $annonce->getOwnerId(); ?>" class="btn btn-primary w-100 py-2 mb-3 shadow-sm">
                            <i class="fas fa-paper-plane me-2"></i> 
                            <?php echo ($_SESSION['user_id'] == $annonce->getOwnerId()) ? "M'envoyer un message (Test)" : "Contacter le propriétaire"; ?>
                        </a>
                    <?php else: ?>
                        <a href="connexion.php?redirect=voir_annonce.php?id=<?php echo $id; ?>" class="btn btn-primary w-100 py-2 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                        </a>
                        <div class="alert alert-info py-2 px-3 small mb-3">
                            <i class="fas fa-info-circle me-1"></i> Vous devez être connecté pour contacter le propriétaire.
                        </div>
                    <?php endif; ?>
                    
                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="small text-muted">Annonce vérifiée par l'équipe</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-muted me-2"></i>
                            <span class="small text-muted">Réponse sous 24h en moyenne</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .last-child-border-0:last-child { border-bottom: 0 !important; }
</style>

<?php require_once "../includes/footer.php"; ?>
