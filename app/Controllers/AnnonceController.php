<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Token;

class AnnonceController extends Controller {
    public function show($id) {
        global $annonceDAO, $commentDAO;
        
        $id = (int)$id;
        $annonce = $annonceDAO->getFullById($id);
        
        if (!$annonce) {
            $this->render('errors/404', ['message' => 'Annonce non trouvée.']);
            return;
        }
        
        $comments = $commentDAO->getByAnnonceId($id);
        
        $this->render('annonce/show', [
            'annonce' => $annonce,
            'comments' => $comments,
            'id' => $id
        ]);
    }

    public function addComment() {
        global $commentDAO;
        
        if (empty($_SESSION['isLoggedin'])) {
            header("Location: /coLocation/auth/login");
            exit();
        }

        if (!Token::check($_POST['token'] ?? '')) {
            $id_annonce = (int)($_POST['id_annonce'] ?? 0);
            header("Location: " . \App\Core\Config::url('annonce/show') . "?id=$id_annonce&error=invalid_token");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_annonce = (int)($_POST['id_annonce'] ?? 0);
            $id_owner = (int)($_POST['id_owner'] ?? 0);
            $note = (float)($_POST['note'] ?? 0);
            $content = trim($_POST['commentaire'] ?? '');

            if ($id_annonce > 0 && !empty($content)) {
                $commentData = [
                    'note' => $note,
                    'date_' => date('Y-m-d'),
                    'commentaire' => $content,
                    'id_annonce' => $id_annonce,
                    'id_utilisateur' => $_SESSION['user_id'], // author
                    'id_utilisateur_1' => $id_owner // owner
                ];

                $comment = new \App\Models\Comment($commentData);
                if ($commentDAO->save($comment)) {
                    header("Location: " . \App\Core\Config::url('annonce/show') . "?id=$id_annonce&success=comment_added");
                    exit();
                }
            }
        }
        
        $id_annonce = (int)($_POST['id_annonce'] ?? 0);
        header("Location: " . \App\Core\Config::url('annonce/show') . "?id=$id_annonce&error=comment_failed");
        exit();
    }

    public function create() {
        if (empty($_SESSION['isLoggedin'])) {
            header("Location: " . \App\Core\Config::url('auth/login'));
            exit();
        }
        $this->render('annonce/create');
    }

    public function store() {
        global $annonceDAO;

        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . \App\Core\Config::url('auth/login'));
            exit();
        }

        if (!Token::check($_POST['token'] ?? '')) {
            header("Location: " . \App\Core\Config::url('annonce/create') . "?error=invalid_token");
            exit();
        }

        $data = [
            'titre' => $_POST['titre'],
            'adresse_1' => $_POST['rue_nom'],
            'adresse_2' => $_POST['appartement'] ?? null,
            'adresse_3' => $_POST['batiment'] ?? null,
            'adresse_4' => $_POST['infocomplementaire'] ?? null,
            'ville' => $_POST['ville'],
            'code_postal' => (int)$_POST['codePostal'],
            'loyer_location_chez_habitant' => (float)$_POST['loyer'],
            'description' => $_POST['descriptions'],
            'surface_logement' => (float)$_POST['surface_logement'],
            'surface_chambres' => (float)$_POST['surface_chambres'],
            'nombre_chambre' => (int)$_POST['nombre_chambre'],
            'date_expiration' => $_POST['date_expiration'],
            'date_publication' => date('Y-m-d'),
            'date_modification' => date('Y-m-d'),
            'carte_coordonnee_GPS' => $_POST['carte_coordonnee_GPS'] ?? null,
            'date_cloture' => $_POST['date_cloture'] ?? '0000-00-00',
            'loyer_colocation' => (float)$_POST['loyer']
        ];

        $annonce = new \App\Models\Annonce($data);
        $modesVie = $_POST['mode_vie'] ?? [];
        $regimes = $_POST['regime'] ?? [];

        $idAnnonce = $annonceDAO->ajouterAnnonce($annonce, $_SESSION['user_id'], $modesVie, $regimes);

        if ($idAnnonce > 0) {
            header("Location: /coLocation/pages/modifier_photos.php?id_annonce=$idAnnonce&success=annonce_created");
        } else {
            header("Location: " . \App\Core\Config::url('annonce/create') . "?error=server_error");
        }
        exit();
    }

    public function edit($id) {
        global $annonceDAO;
        
        if (empty($_SESSION['isLoggedin'])) {
            header("Location: " . \App\Core\Config::url('auth/login'));
            exit();
        }

        $id = (int)$id;
        $annonce = $annonceDAO->getFullById($id);

        if (!$annonce || !$annonceDAO->appartientAUtilisateur($id, $_SESSION['user_id'])) {
            header("Location: " . \App\Core\Config::url('pages/page_annonce.php') . "?error=unauthorized");
            exit();
        }

        $selectedModes = array_column($annonceDAO->avoirModeVie($id), 'id_mode_vie');
        $selectedRegimes = array_column($annonceDAO->avoirRegime($id), 'id_regime_alimentaire');

        $this->render('annonce/edit', [
            'annonce' => $annonce,
            'selectedModes' => $selectedModes,
            'selectedRegimes' => $selectedRegimes
        ]);
    }

    public function update() {
        global $annonceDAO;

        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . \App\Core\Config::url('auth/login'));
            exit();
        }

        if (!Token::check($_POST['token'] ?? '')) {
            header("Location: " . \App\Core\Config::url('pages/page_annonce.php') . "?error=invalid_token");
            exit();
        }

        $id = (int)($_POST['id_annonce'] ?? 0);
        $annonce = $annonceDAO->getById($id);

        if (!$annonce || !$annonceDAO->appartientAUtilisateur($id, $_SESSION['user_id'])) {
            header("Location: " . \App\Core\Config::url('pages/page_annonce.php') . "?error=unauthorized");
            exit();
        }

        // Update properties
        $annonce->setTitre($_POST['titre'] ?? $annonce->getTitre());
        
        $data = [
            'id_annonce' => $id,
            'titre' => $_POST['titre'],
            'adresse_1' => $_POST['rue_nom'],
            'adresse_2' => $_POST['appartement'],
            'adresse_3' => $_POST['batiment'],
            'adresse_4' => $_POST['infocomplementaire'],
            'ville' => $_POST['ville'],
            'code_postal' => (int)$_POST['codePostal'],
            'loyer_location_chez_habitant' => (float)$_POST['loyer'],
            'description' => $_POST['descriptions'],
            'surface_logement' => (float)$_POST['surface_logement'],
            'surface_chambres' => (float)$_POST['surface_chambres'],
            'nombre_chambre' => (int)$_POST['nombre_chambre'],
            'date_expiration' => $_POST['date_expiration'],
            'date_publication' => $annonce->getDatePublication(),
            'date_modification' => date('Y-m-d'),
            'carte_coordonnee_GPS' => $_POST['carte_coordonnee_GPS'],
            'date_cloture' => $_POST['date_cloture'],
            'loyer_colocation' => (float)$_POST['loyer'] 
        ];

        $updatedAnnonce = new \App\Models\Annonce($data);
        $modesVie = $_POST['mode_vie'] ?? [];
        $regimes = $_POST['regime'] ?? [];

        if ($annonceDAO->modifierAnnonce($updatedAnnonce, $_SESSION['user_id'], $modesVie, $regimes)) {
            header("Location: " . \App\Core\Config::url('pages/page_annonce.php') . "?success=updated");
        } else {
            header("Location: " . \App\Core\Config::url('pages/page_annonce.php') . "?error=update_failed");
        }
        exit();
    }

    public function delete() {
        global $annonceDAO;

        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . \App\Core\Config::url('auth/login'));
            exit();
        }

        if (!Token::check($_POST['token'] ?? '')) {
            header("Location: " . \App\Core\Config::url('pages/page_annonce.php') . "?error=invalid_token");
            exit();
        }

        $id = (int)($_POST['id_annonce'] ?? 0);
        
        if ($annonceDAO->supprimerAnnonce($id, $_SESSION['user_id'])) {
            header("Location: " . \App\Core\Config::url('pages/page_annonce.php') . "?success=deleted");
        } else {
            header("Location: " . \App\Core\Config::url('pages/page_annonce.php') . "?error=delete_failed");
        }
        exit();
    }

    public function uploadPhoto() {
        global $annonceDAO;

        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . \App\Core\Config::url('auth/login'));
            exit();
        }

        if (!Token::check($_POST['token'] ?? '')) {
            $idAnnonce = (int)($_POST['id_annonce'] ?? 0);
            header("Location: /coLocation/pages/modifier_photos.php?id_annonce=$idAnnonce&error=invalid_token");
            exit();
        }

        $idAnnonce = (int)($_POST['id_annonce'] ?? 0);
        if (!$annonceDAO->appartientAUtilisateur($idAnnonce, $_SESSION['user_id'])) {
            header("Location: /coLocation/pages/modifier_photos.php?id_annonce=$idAnnonce&error=unauthorized");
            exit();
        }

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname($_SERVER['SCRIPT_FILENAME']) . '/photos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('annonce_') . '.' . $extension;
            $targetPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                $dbPath = 'photos/' . $filename;
                if ($annonceDAO->addPhoto($idAnnonce, $dbPath)) {
                    header("Location: /coLocation/pages/modifier_photos.php?id_annonce=$idAnnonce&success=uploaded");
                    exit();
                }
            }
        }

        header("Location: /coLocation/pages/modifier_photos.php?id_annonce=$idAnnonce&error=upload_failed");
        exit();
    }

    public function deletePhoto() {
        global $annonceDAO;

        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . \App\Core\Config::url('auth/login'));
            exit();
        }

        if (!Token::check($_POST['token'] ?? '')) {
            $idAnnonce = (int)($_POST['id_annonce'] ?? 0);
            header("Location: /coLocation/pages/modifier_photos.php?id_annonce=$idAnnonce&error=invalid_token");
            exit();
        }

        $idAnnonce = (int)($_POST['id_annonce'] ?? 0);
        $idPhoto = (int)($_POST['id_photo'] ?? 0);

        if (!$annonceDAO->appartientAUtilisateur($idAnnonce, $_SESSION['user_id'])) {
            header("Location: /coLocation/pages/modifier_photos.php?id_annonce=$idAnnonce&error=unauthorized");
            exit();
        }

        if ($annonceDAO->deletePhoto($idPhoto)) {
            header("Location: /coLocation/pages/modifier_photos.php?id_annonce=$idAnnonce&success=deleted");
        } else {
            header("Location: /coLocation/pages/modifier_photos.php?id_annonce=$idAnnonce&error=delete_failed");
        }
        exit();
    }

    public function getAnnoncesJson() {
        global $annonceDAO;
        header('Content-Type: application/json');
        
        try {
            $annonces = $annonceDAO->getAllWithPhotos();
            $data = [];
            
            foreach ($annonces as $a) {
                $data[] = [
                    'id' => $a->getId(),
                    'titre' => $a->getTitre(),
                    'ville' => $a->getVille(),
                    'loyer' => $a->getLoyer(),
                    'photo' => $a->getPhoto(),
                    'gps' => $a->getGps()
                ];
            }
            
            echo json_encode(['success' => true, 'annonces' => $data]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
