<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['isLoggedin']) || empty($_SESSION['user_id'])) {
    header("Location: connexion.php?error=login_required");
    exit();
}

require("../includes/dbConnection.php");
$dbConn = getDbConnection();
$user = null;

if ($dbConn) {
    $stmt = $dbConn->prepare("
        SELECT
            u.id_utilisateur,
            u.nom,
            u.prenom,
            u.email,
            u.situation_professionnel,
            u.garant,
            u.date_naissance,
            u.photo_profil,
            u.salaire_mensuel_net,
            u.revenu_fiscal,
            r.role
        FROM utilisateur u
        LEFT JOIN role r ON r.id_role = u.id_role
        WHERE u.id_utilisateur = :id_utilisateur
        LIMIT 1
    ");
    $stmt->bindParam(':id_utilisateur', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

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

    return '../' . ltrim($photoPath, '/');
}

$fullName = trim($user['prenom'] . ' ' . $user['nom']);
$initials = strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1));
$birthDate = !empty($user['date_naissance']) ? date('d/m/Y', strtotime($user['date_naissance'])) : 'Non renseignee';
$hasGarant = (int) $user['garant'] === 1 ? 'Oui' : 'Non';
$salary = number_format((float) $user['salaire_mensuel_net'], 2, ',', ' ') . ' EUR';
$profilePhotoSrc = getProfilePhotoSrc($user['photo_profil']);
$profileStatus = $_GET['status'] ?? '';
$profileError = $_GET['error'] ?? '';
$profileStatusMessages = [
    'password_updated' => 'Votre mot de passe a bien ete mis a jour.',
    'photo_updated' => 'Votre photo de profil a bien ete mise a jour.',
    'photo_removed' => 'Votre photo de profil a bien ete supprimee.',
    'password_updated' => 'Votre mot de passe a bien ete mis a jour.',
];
$profileErrorMessages = [
    'missing_fields' => 'Veuillez remplir tous les champs demandes.',
    'wrong_password' => 'Le mot de passe actuel est incorrect.',
    'password_mismatch' => 'Les nouveaux mots de passe ne correspondent pas.',
    'password_too_short' => 'Le nouveau mot de passe doit contenir au moins 6 caracteres.',
    'invalid_photo' => 'Veuillez choisir une image JPG, PNG, GIF ou WebP.',
    'photo_too_large' => 'La photo doit peser moins de 2 Mo.',
    'upload_failed' => 'Impossible d enregistrer la photo. Veuillez reessayer.',
    'server_error' => 'Une erreur est survenue. Veuillez reessayer.',
    'invalid_current_password' => 'Le mot de passe actuel est incorrect.',
];

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
                <img src="<?php echo escapeProfileValue($profilePhotoSrc); ?>" alt="">
            <?php else: ?>
                <?php echo escapeProfileValue($initials); ?>
            <?php endif; ?>
        </div>
        <div>
            <p class="section-kicker">Espace utilisateur</p>
            <h1><?php echo escapeProfileValue($fullName); ?></h1>
            <p><?php echo escapeProfileValue($user['email']); ?></p>
            <div class="profile-actions">
                <button type="button" class="profile-action-button" data-bs-toggle="modal" data-bs-target="#passwordModal">
                    <i class="fa-solid fa-key"></i>
                    Changer le mot de passe
                </button>
                <button type="button" class="profile-action-button profile-action-button-secondary" data-bs-toggle="modal" data-bs-target="#photoModal">
                    <i class="fa-solid fa-camera"></i>
                    Modifier la photo
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
                    <dd><?php echo escapeProfileValue($user['role'] ?? 'Utilisateur'); ?></dd>
                </div>
                <div>
                    <dt>Situation professionnelle</dt>
                    <dd><?php echo escapeProfileValue($user['situation_professionnel']); ?></dd>
                </div>
                <div>
                    <dt>Date de naissance</dt>
                    <dd><?php echo escapeProfileValue($birthDate); ?></dd>
                </div>
                <div>
                    <dt>Garant</dt>
                    <dd><?php echo escapeProfileValue($hasGarant); ?></dd>
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
                    <dd><?php echo escapeProfileValue($user['revenu_fiscal'] ?: 'Non renseigne'); ?></dd>
                </div>
                
            </dl>
        </article>
    </section>
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
                            <img src="<?php echo escapeProfileValue($profilePhotoSrc); ?>" alt="Photo de profil actuelle">
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
