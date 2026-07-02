<?php
session_start();
require_once '../../includes/dbConnection.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion.php');
    exit();
}

$conn = getDbConnection();
$user_id = $_SESSION['user_id'];


if (isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1') {
    $stmt = $conn->prepare("SELECT photo_profil FROM UTILISATEUR WHERE id_utilisateur = :user_id");
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && !empty($user['photo_profil'])) {
        $photo_path = $user['photo_profil'];
        
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }
        
        $update_stmt = $conn->prepare("UPDATE UTILISATEUR SET photo_profil = NULL WHERE id_utilisateur = :user_id");
        $update_stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        if ($update_stmt->execute()) {
            header("Location: ../profile_utilisateur.php?status=photo_removed");
            exit();
        } else {
            header("Location: ../profile_utilisateur.php?error=remove_failed");
            exit();
        }
    }
    header('Location: ../profile_utilisateur.php');
    exit();
}

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
    $max_file_size = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $max_file_size) {
        header("Location: ../profile_utilisateur.php?error=file_too_large");
        exit();
    }
    
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $allowed_types)) {
        header("Location: ../profile_utilisateur.php?error=invalid_file_type");
        exit();
    }
    
    $upload_dir = __DIR__ . '/uploads/profile_photos/';
    
    $upload_dir_alt = $_SERVER['DOCUMENT_ROOT'] . '/uploads/profile_photos/';
    $dir_to_use = null;
    if (is_dir($upload_dir) && is_writable($upload_dir)) {
        $dir_to_use = $upload_dir;
    } 

    elseif (is_dir($upload_dir_alt) && is_writable($upload_dir_alt)) {
        $dir_to_use = $upload_dir_alt;
    }
    else {
        if (!is_dir($upload_dir)) {
            if (mkdir($upload_dir, 0777, true)) {
                $dir_to_use = $upload_dir;
            }
        }
        elseif (!is_dir($upload_dir_alt)) {
            if (mkdir($upload_dir_alt, 0777, true)) {
                $dir_to_use = $upload_dir_alt;
            }
        }
    }
    
    if ($dir_to_use === null) {
        $relative_dir = 'uploads/profile_photos/';
        if (!is_dir($relative_dir)) {
            mkdir($relative_dir, 0777, true);
        }
        if (is_dir($relative_dir) && is_writable($relative_dir)) {
            $dir_to_use = $relative_dir;
        }
    }
    
    if ($dir_to_use === null) {
        error_log("Upload directory error. Tried: " . $upload_dir . " and " . $upload_dir_alt);
        header("Location: ../profile_utilisateur.php?error=permission_error&debug=1");
        exit();
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = 'user_' . $user_id . '_' . time() . '.' . $file_extension;
    $destination = $dir_to_use . $new_filename;

    $db_photo_path = 'uploads/profile_photos/' . $new_filename;
    
    $stmt = $conn->prepare("SELECT photo_profil FROM UTILISATEUR WHERE id_utilisateur = :user_id");
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_photo = $user['photo_profil'] ?? null;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $update_stmt = $conn->prepare("UPDATE UTILISATEUR SET photo_profil = :photo WHERE id_utilisateur = :user_id");
        $update_stmt->bindParam(":photo", $db_photo_path, PDO::PARAM_STR);
        $update_stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        
        if ($update_stmt->execute()) {
            if ($current_photo && file_exists($current_photo)) {
                unlink($current_photo);
            }
            header("Location: ../profile_utilisateur.php?status=photo_updated");
            exit();
        } else {
            if (file_exists($destination)) {
                unlink($destination);
            }
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
?>