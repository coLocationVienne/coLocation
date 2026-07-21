<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Core\Config;

// Ensure global variables are accessible
global $userDAO, $messageDAO;

function getUserAvatar($user_id, $userDAO) {
    if (!$userDAO || !is_int($user_id) || $user_id <= 0) {
        return [
            'src' => '',
            'initials' => 'U',
            'has_photo' => false
        ];
    }
    
    try {
        $user = $userDAO->getById($user_id);
        
        if ($user && !empty($user->getPhotoProfil())) {
            $photo_path = Config::url("pages/be/" . $user->getPhotoProfil());
            $server_path = $_SERVER['DOCUMENT_ROOT'] . Config::url("pages/be/" . $user->getPhotoProfil());
            if (file_exists($server_path)) {
                return [
                    'src' => $photo_path,
                    'initials' => '',
                    'has_photo' => true
                ];
            }
        }
        
        $initials = '';
        if ($user) {
            if (!empty($user->getPrenom()) && !empty($user->getNom())) {
                $initials = strtoupper(substr($user->getPrenom(), 0, 1) . substr($user->getNom(), 0, 1));
            } elseif (!empty($user->getPrenom())) {
                $initials = strtoupper(substr($user->getPrenom(), 0, 2));
            } else {
                $initials = 'U';
            }
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
        error_log("Avatar fetch error: " . $e->getMessage());
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
    <link rel="stylesheet" href="<?php echo Config::asset('style/style.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <nav class="navbar navbar-expand-lg fixed-top bg-body-tertiary">
     <div class="container-fluid">
      <a class="navbar-brand" href="<?php echo Config::url('home'); ?>">Colocation Vienne</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?php echo Config::url('home'); ?>">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/coLocation/pages/recherche.php">Rechercher une colocation</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo Config::url('annonce/create'); ?>">Créer et publier des annonces</a>
          </li>
        </ul>
        
        <ul class="navbar-nav">
          <?php if (empty($_SESSION['isLoggedin'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo Config::url('auth/register'); ?>">
                <i class="fas fa-user-plus"></i> Inscription
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo Config::url('auth/login'); ?>">
                <i class="fas fa-sign-in-alt"></i> Connexion
              </a>
            </li>
          <?php else: ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php if ($avatarData && $avatarData['has_photo']): ?>
                  <img src="<?php echo htmlspecialchars($avatarData['src']); ?>" alt="Profile" style="width: 32px; height: 32px; border-radius: 50%;">
                <?php else: ?>
                  <span class="badge bg-primary rounded-circle"><?php echo htmlspecialchars($avatarData['initials'] ?? 'U'); ?></span>
                <?php endif; ?>
                <?php echo htmlspecialchars($_SESSION['user_prenom'] ?? 'Mon compte'); ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="/coLocation/pages/profile_utilisateur.php">Mon profil</a></li>
                <li><a class="dropdown-item" href="/coLocation/pages/page_annonce.php">Mes annonces</a></li>
                <li>
                  <a class="dropdown-item d-flex justify-content-between align-items-center" href="/coLocation/pages/messages.php">
                    Messages
                    <span class="badge bg-danger rounded-pill"><?php echo $messageDAO ? $messageDAO->countUnread($_SESSION['user_id']) : 0; ?></span>
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="<?php echo Config::url('auth/logout'); ?>">Déconnexion</a></li>
              </ul>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  

