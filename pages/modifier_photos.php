<?php
require_once(__DIR__ . "/../init.php");

if (empty($_SESSION['user_id'])) {
    header('Location: /coLocation/auth/login');
    exit();
}

$idAnnonce = (int)($_GET['id_annonce'] ?? 0);
if ($idAnnonce <= 0) {
    header('Location: page_annonce.php');
    exit();
}

use App\Models\AnnonceDAO;
$annonceDAO = new AnnonceDAO();
$annonce = $annonceDAO->getById($idAnnonce);

// Verify ownership
if (!$annonce || !$annonceDAO->appartientAUtilisateur($idAnnonce, (int)$_SESSION['user_id'])) {
    header('Location: page_annonce.php?error=unauthorized');
    exit();
}

$photos = $annonceDAO->getPhotos($idAnnonce);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les photos - <?php echo htmlspecialchars($annonce->getTitre()); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .photo-card {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .photo-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .upload-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <?php require_once(__DIR__ . "/../app/Views/partials/header.php"); ?>

    <div class="container mt-5" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: none; padding: 0; margin-bottom: 20px; display: flex; list-style: none;">
                <li class="breadcrumb-item"><a href="page_annonce.php" style="color: #007bff; text-decoration: none;">Mes Annonces</a></li>
                <li class="breadcrumb-item" style="padding-left: 10px; color: #6c757d;">&nbsp;/&nbsp; Gérer les photos</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 style="margin: 0;">Gérer les photos : <?php echo htmlspecialchars($annonce->getTitre()); ?></h1>
            <a href="/coLocation/annonce/edit?id=<?php echo $idAnnonce; ?>" class="btn btn-outline-secondary" style="border: 1px solid #ccc; padding: 8px 15px; border-radius: 4px; text-decoration: none; color: #333; font-size: 14px;">
                <i class="fas fa-edit"></i> Retour à la modification
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <?php 
                    if ($_GET['success'] == 'annonce_created') echo "<strong>Félicitations !</strong> Votre annonce a été créée. Ajoutez maintenant quelques photos pour attirer plus de colocataires.";
                    else if ($_GET['success'] == 'uploaded') echo "La photo a été ajoutée avec succès.";
                    else if ($_GET['success'] == 'deleted') echo "La photo a été supprimée.";
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                Une erreur est survenue lors de l'opération. Veuillez réessayer.
            </div>
        <?php endif; ?>

        <div class="upload-section">
            <h3 style="margin-top: 0;">Ajouter une nouvelle photo</h3>
            <form action="/coLocation/annonce/upload-photo" method="POST" enctype="multipart/form-data" class="mt-3">
                <input type="hidden" name="id_annonce" value="<?php echo $idAnnonce; ?>">
                <div class="mb-3" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Sélectionnez une image (JPG, PNG)</label>
                    <input type="file" name="photo" class="form-control" accept="image/*" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                </div>
                <button type="submit" class="btn btn-primary" style="background: #007bff; color: white; border: none; padding: 12px 25px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    <i class="fas fa-upload"></i> Télécharger la photo
                </button>
            </form>
        </div>

        <h3>Photos actuelles</h3>
        <div class="photo-grid">
            <?php if (empty($photos)): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px; border: 2px dashed #dee2e6;">
                    <i class="fas fa-images" style="font-size: 48px; color: #adb5bd; margin-bottom: 15px;"></i>
                    <p style="color: #6c757d; margin: 0;">Aucune photo n'a encore été ajoutée pour cette annonce.</p>
                </div>
            <?php else: ?>
                <?php foreach ($photos as $p): ?>
                    <?php 
                        // Determine the correct path
                        $url = $p['url'];
                        $isExternal = (strpos($url, 'http') === 0);
                        $displayPath = $isExternal ? $url : "/coLocation/" . $url;
                    ?>
                    <div class="photo-card">
                        <img src="<?php echo htmlspecialchars($displayPath); ?>" alt="Photo annonce">
                        <form action="/coLocation/annonce/delete-photo" method="POST" onsubmit="return confirm('Supprimer cette photo ?');">
                            <input type="hidden" name="id_photo" value="<?php echo $p['id_photo']; ?>">
                            <input type="hidden" name="id_annonce" value="<?php echo $idAnnonce; ?>">
                            <button type="submit" class="delete-btn" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div style="margin-top: 50px;">
        <?php require_once(__DIR__ . "/../app/Views/partials/footer.php"); ?>
    </div>
</body>
</html>
