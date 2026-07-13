<?php

require_once "../init.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['isLoggedin']) || empty($_SESSION['user_id'])) {
    header("Location: connexion.php?error=login_required");
    exit();
}

$user = $userDAO->getById((int)$_SESSION['user_id']);

if (!$user) {
    session_destroy();
    header("Location: connexion.php?error=login_required");
    exit();
}

function escapeProfileValue($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function getProfilePhotoSrc($photoPath) {
    if (empty($photoPath)) {
        return '';
    }

    if (preg_match('/^(https?:)?\/\//', $photoPath) || str_starts_with($photoPath, '/')) {
        return $photoPath;
    }

    return 'be/' . ltrim($photoPath, '/');
}
echo $user->getPhotoProfil();
$fullName = trim($user->getPrenom() . ' ' . $user->getNom());
$initials = strtoupper(substr($user->getPrenom(), 0, 1) . substr($user->getNom(), 0, 1));
$birthDate = !empty($user->getDateNaissance()) ? date('d/m/Y', strtotime($user->getDateNaissance())) : 'Non renseignee';
$hasGarant = $user->isGarant() === 1 ? 'Oui' : 'Non';
$salary = number_format((float) $user->getSalaireMensuelNet(), 2, ',', ' ') . ' EUR';
$profilePhotoSrc = getProfilePhotoSrc($user->getPhotoProfil());
$profileStatus = $_GET['status'] ?? '';
$profileError = $_GET['error'] ?? '';

include("be/common.php");
include("../includes/header.php");

// Add this after your existing error/status message handling
$openPasswordModal = isset($_GET['passwordModal']) && $_GET['passwordModal'] === '1' && isset($profileError);

?>
<script>
document.addEventListener('DOMContentLoaded', function() {

    <?php if (isset($openPasswordModal) && $openPasswordModal === true): ?>
        const passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
        passwordModal.show();
        
        setTimeout(function() {
            const currentPasswordInput = document.getElementById('current_password');
            if (currentPasswordInput) {
                currentPasswordInput.focus();
            }
        }, 500);
    <?php endif; ?>
});
    </script>

<main class="profile-page">
    <section class="profile-hero">
        <div class="profile-avatar" aria-hidden="true">
            <?php if ($profilePhotoSrc !== ''): ?>
                <img class="profile-photo-preview" src="<?php echo escapeProfileValue($profilePhotoSrc); ?>" alt="Photo de profil actuelle">
            <?php else: ?>
                <?php echo escapeProfileValue($initials); ?>
            <?php endif; ?>
        </div>
        <div>
            <p class="section-kicker">Espace utilisateur</p>
            <h1><?php echo escapeProfileValue($fullName); ?></h1>
            <p><?php echo escapeProfileValue($user->getEmail()); ?></p>
            <div class="profile-actions">
                <button type="button" class="profile-action-button" data-bs-toggle="modal" data-bs-target="#passwordModal">
                    <i class="fa-solid fa-key"></i>
                    Changer le mot de passe
                </button>
                <button type="button" class="profile-action-button profile-action-button-secondary" data-bs-toggle="modal" data-bs-target="#photoModal">
                    <i class="fa-solid fa-camera"></i>
                    Modifier la photo
                </button>
                <button type="button" class="profile-action-button profile-action-button-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fa-solid fa-user-edit"></i>
                    Modifier mes informations
                </button>
            </div>
        </div>
    </section>

    <section class="profile-content">
        <article class="profile-card profile-summary">
            <h2>Informations du compte</h2>
            <dl class="profile-list">
                <div>
                    <dt>Role</dt>
                    <dd><?php echo $user->getIdRole() ?? 'Utilisateur'; ?></dd>
                </div>
                <div>
                    <dt>Situation professionnelle</dt>
                    <dd><?php echo $user->getSituationProfessionnel(); ?></dd>
                </div>
                <div>
                    <dt>Date de naissance</dt>
                    <dd><?php echo $birthDate; ?></dd>
                </div>
                <div>
                    <dt>Garant</dt>
                    <dd><?php echo $hasGarant; ?></dd>
                </div>
            </dl>
        </article>

        <article class="profile-card">
            <h2>Dossier locataire</h2>
            <dl class="profile-list">
                <div>
                    <dt>Salaire mensuel net</dt>
                    <dd><?php echo escapeProfileValue($salary); ?></dd>
                </div>
                <div>
                    <dt>Revenu fiscal</dt>
                    <dd><?php echo escapeProfileValue($user->getRevenuFiscal() ?: 'Non renseigne'); ?></dd>
                </div>
                
            </dl>
        </article>
    </section>

    <?php include('profile_utilisateur_admin.php'); ?>

</main>

<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content profile-modal">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="passwordModalLabel">Changer le mot de passe</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="be/update_password.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="<?php echo escapeProfileValue($_SESSION['user_id']); ?>">
                    <div class="form-group mb-3">
                        <label for="current_password">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="new_password">Nouveau mot de passe</label>
                        <input type="password" id="new_password" name="new_password" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirm">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="new_password_confirm" name="new_password_confirm" minlength="6" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre a jour</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content profile-modal">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="photoModalLabel">Photo de profil</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="be/update_profile_photo.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="profile-photo-preview">
                        <?php if ($profilePhotoSrc !== ''): ?>
                            <img class="profile-photo-preview" src="<?php echo escapeProfileValue($profilePhotoSrc); ?>" alt="Photo de profil actuelle">
                            
                        <?php else: ?>
                            <span><?php echo escapeProfileValue($initials); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="profile_photo">Ajouter ou remplacer la photo</label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/gif,image/webp">
                    </div>
                </div>
                <div class="modal-footer profile-photo-actions">
                    <?php if ($profilePhotoSrc !== ''): ?>
                        <button type="submit" name="remove_photo" value="1" class="btn btn-outline-danger">Supprimer la photo</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content profile-modal">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="editProfileModalLabel">Modifier mes informations</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="be/update_user_profile.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="<?php echo escapeProfileValue($_SESSION['user_id']); ?>">
                    
                    <div class="form-group mb-3">
                        <label for="edit_situation">Situation professionnelle</label>
                        <select id="edit_situation" name="situation" class="form-control">
                            <option value="">Sélectionnez une situation</option>
                            <option value="Étudiant" <?php echo ($user->getSituationProfessionnel() === 'Étudiant') ? 'selected' : ''; ?>>Étudiant</option>
                            <option value="Salarié" <?php echo ($user->getSituationProfessionnel() === 'Salarié') ? 'selected' : ''; ?>>Salarié</option>
                            <option value="Indépendant" <?php echo ($user->getSituationProfessionnel() === 'Indépendant') ? 'selected' : ''; ?>>Indépendant</option>
                            <option value="Retraité" <?php echo ($user->getSituationProfessionnel() === 'Retraité') ? 'selected' : ''; ?>>Retraité</option>
                            <option value="Sans emploi" <?php echo ($user->getSituationProfessionnel() === 'Sans emploi') ? 'selected' : ''; ?>>Sans emploi</option>
                            <option value="Autre" <?php echo ($user->getSituationProfessionnel() === 'Autre') ? 'selected' : ''; ?>>Autre</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_garant">Garant</label>
                        <select id="edit_garant" name="garant" class="form-control">
                            <option value="0" <?php echo ((int)$user->isGarant() === 0) ? 'selected' : ''; ?>>Non</option>
                            <option value="1" <?php echo ((int)$user->isGarant() === 1) ? 'selected' : ''; ?>>Oui</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_salary">Salaire mensuel net (€)</label>
                        <input type="number" step="0.01" id="edit_salary" name="salary" class="form-control" value="<?php echo escapeProfileValue($user->getSalaireMensuelNet()); ?>" placeholder="0.00">
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_revenu_fiscal">Revenu fiscal (€)</label>
                        <input type="number" step="0.01" id="edit_revenu_fiscal" name="revenu_fiscal" class="form-control" value="<?php echo escapeProfileValue($user->getRevenuFiscal()); ?>" placeholder="0.00">
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_date_naissance">Date de naissance</label>
                        <input type="date" id="edit_date_naissance" name="date_naissance" class="form-control" value="<?php echo !empty($user->getDateNaissance()) ? date('Y-m-d', strtotime($user->getDateNaissance())) : ''; ?>">
                    </div>

                    <hr>
                    <p class="text-muted small">Les champs optionnels peuvent être laissés vides.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (isset($profileStatusMessages[$profileStatus])): ?>
    <script>
        window.addEventListener("load", function () {
            Swal.fire({
                title: "Profil mis a jour",
                text: "<?php echo escapeProfileValue($profileStatusMessages[$profileStatus]); ?>",
                icon: "success",
                confirmButtonText: "OK"
            });
        });
    </script>
<?php endif; ?>

<?php if (isset($profileErrorMessages[$profileError])): ?>
    <script>
        window.addEventListener("load", function () {
            Swal.fire({
                title: "Action impossible",
                text: "<?php echo escapeProfileValue($profileErrorMessages[$profileError]); ?>",
                icon: "error",
                confirmButtonText: "Reessayer"
            });
        });
    </script>
<?php endif; ?>


<script src="../assets/style/js/profile.js"></script>

<?php
include("../includes/footer.php");
?>
