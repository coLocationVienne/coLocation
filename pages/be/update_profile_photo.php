<?php

require_once "../../init.php";

if (empty($_SESSION['isLoggedin']) || empty($_SESSION['user_id'])) {
    header('Location: ../connexion.php');
    exit();
}

/** @var \colocation\UserDAO $userDAO */
$userId = (int)$_SESSION['user_id'];
$user = $userDAO->getById($userId);

if (!$user) {
    header('Location: ../profile_utilisateur.php?error=invalid_user');
    exit();
}

// Handle Photo Removal
if (isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1') {
    $current_photo = $user->getPhotoProfil();
    if (!empty($current_photo) && file_exists($current_photo)) {
        unlink($current_photo);
    }
    
    $user->setPhotoProfil('');
    if ($userDAO->update($user)) {
        header("Location: ../profile_utilisateur.php?status=photo_removed");
        exit();
    } else {
        header("Location: ../profile_utilisateur.php?error=remove_failed");
        exit();
    }
}

// Handle Photo Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    $file = $_FILES['profile_photo'];
    
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        header("Location: ../profile_utilisateur.php?error=no_file_selected");
        exit();
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        header("Location: ../profile_utilisateur.php?error=upload_error");
        exit();
    }
    
    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        header("Location: ../profile_utilisateur.php?error=file_too_large");
        exit();
    }
    
    $upload_dir = 'uploads/profile_photos/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = 'user_' . $userId . '_' . time() . '.' . $file_extension;
    $destination = $upload_dir . $new_filename;
    
    $current_photo = $user->getPhotoProfil();
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $user->setPhotoProfil($destination);
        if ($userDAO->update($user)) {
            if ($current_photo && file_exists($current_photo)) {
                unlink($current_photo);
            }
            header("Location: ../profile_utilisateur.php?status=photo_updated");
            exit();
        } else {
            if (file_exists($destination)) unlink($destination);
            header("Location: ../profile_utilisateur.php?error=db_update_failed");
            exit();
        }
    } else {
        header("Location: ../profile_utilisateur.php?error=move_failed");
        exit();
    }
}

header('Location: ../profile_utilisateur.php');
exit();
