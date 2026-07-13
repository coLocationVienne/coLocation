<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../init.php';
$pdo = $userDAO;

$baseURL = "http://localhost/coLocation";

function getUserAvatar($user_id, $pdo) {
    if (!is_int($user_id) || $user_id <= 0) {
        return [
            'src' => '',
            'initials' => 'U',
            'has_photo' => false
        ];
    }
    
    try {
        $user = $pdo->getById($user_id);
        
        if ($user && !empty($user->getPhotoProfil())) {
          
            $photo_path = "/coLocation/pages/be/" . $user->getPhotoProfil();
          
            $server_path = $_SERVER['DOCUMENT_ROOT'] . '/coLocation/pages/be/' . $user->getPhotoProfil();
            if (file_exists($server_path)) {
                return [
                    'src' => $photo_path,
                    'initials' => '',
                    'has_photo' => true
                ];
            } else {
                error_log("Photo file not found: " . $server_path);
            }
        }
        
        $initials = '';
        if (!empty($user->getPrenom()) && !empty($user->getNom())) {
            $initials = strtoupper(substr($user->getPrenom(), 0, 1) . substr($user->getNom(), 0, 1));
        } elseif (!empty($user->getPrenom())) {
            $initials = strtoupper(substr($user->getPrenom(), 0, 2));
        } else {
            $initials = 'U';
        }
        
        return [
            'src' => '',
            'initials' => $initials,
            'has_photo' => false
        ];
    } catch (Exception $e) {
        error_log("Error in getUserAvatar: " . $e->getMessage());
        return [
            'src' => '',
            'initials' => 'U',
            'has_photo' => false
        ];
    }
}

$avatarData = null;
if (!empty($_SESSION['isLoggedin']) && isset($_SESSION['user_id'])) {
    try {
        $avatarData = getUserAvatar($_SESSION['user_id'], $userDAO);
    } catch (Exception $e) {
        error_log("Database connection error: " . $e->getMessage());
        $avatarData = [
            'src' => '',
            'initials' => 'U',
            'has_photo' => false
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="colocation vienne">
    <meta name="Author" content="Issintia Said, Rohid SAFI, Lawrence">
    <title>Colocation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo $baseURL; ?>/assets/style/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
   <nav class="navbar navbar-expand-lg fixed-top bg-body-tertiary">
     <div class="container-fluid">
      <a class="navbar-brand" href="<?php echo $baseURL; ?>/index.php">Colocation Vienne</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" aria-current="page" href="<?php echo $baseURL; ?>/index.php">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'recherche.php' ? 'active' : ''; ?>" href="<?php echo $baseURL; ?>/pages/recherche.php">Rechercher une colocation</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'creer_annonce.php' ? 'active' : ''; ?>" href="<?php echo $baseURL; ?>/pages/creer_annonce.php">Créer et publier des annonces</a>
          </li>
        </ul>
        
        <ul class="navbar-nav">
          <?php if (empty($_SESSION['isLoggedin'])): ?>
            <li class="nav-item">
              <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'formulaire_inscription.php' ? 'active' : ''; ?>" href="<?php echo $baseURL; ?>/pages/register.php">
                <i class="fas fa-user-plus"></i> Inscription
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'connexion.php' ? 'active' : ''; ?>" href="<?php echo $baseURL; ?>/pages/connexion.php">
                <i class="fas fa-sign-in-alt"></i> Connexion
              </a>
            </li>
          <?php else: ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar-wrapper">
                  <?php if ($avatarData && $avatarData['has_photo'] && !empty($avatarData['src'])): ?>
                    <img class="avatar-img" src="<?php echo htmlspecialchars($avatarData['src']); ?>" alt="Photo de profil">
                  <?php else: ?>
                    <div class="avatar-circle">
                      <?php echo htmlspecialchars($avatarData['initials'] ?? 'U'); ?>
                    </div>
                  <?php endif; ?>
                  <span class="avatar-name">
                    <?php 
                      if (isset($_SESSION['user_prenom'])) {
                          echo htmlspecialchars(strtoupper($_SESSION['user_prenom']));
                      } else {
                          echo 'Mon compte';
                      }
                    ?>
                  </span>
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li>
                  <div class="dropdown-header">
                    <?php 
                      if (isset($_SESSION['user_prenom']) && isset($_SESSION['user_nom'])) {
                          echo htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']);
                      } else {
                          echo 'Mon compte';
                      }
                    ?>
                    <small>
                      <?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>
                    </small>
                  </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="<?php echo $baseURL; ?>/pages/profile_utilisateur.php">
                    <i class="fas fa-user-circle"></i> Voir mon profil
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="<?php echo $baseURL; ?>/pages/page_annonce.php">
                    <i class="fas fa-list"></i> Mes annonces
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="<?php echo $baseURL; ?>/pages/messages.php">
                    <i class="fas fa-envelope"></i> Messages
                    <span class="badge bg-danger rounded-pill ms-2"><?php echo $messageDAO->countUnread($_SESSION['user_id']); ?></span>
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item text-danger" href="<?php echo $baseURL; ?>/pages/be/deconnexion.php">
                    <i class="fas fa-sign-out-alt"></i> Se déconnecter
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-YU4/8h5MpfwPJjZzVb5vKpP2IY5l2PzQLNSs5X5nE1d2pFJ6pPfLkGpVz8B+5FI" crossorigin="anonymous"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(event) {
            var dropdown = document.querySelector('.dropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                var dropdownMenu = dropdown.querySelector('.dropdown-menu');
                if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                    bootstrap.Dropdown.getInstance(dropdown.querySelector('.dropdown-toggle')).hide();
                }
            }
        });
    });
  </script>
</body>
</html>