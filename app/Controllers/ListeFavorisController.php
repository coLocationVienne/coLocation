<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Token;
use App\Core\Config;
use App\Models\ListeFavoris;

class ListeFavorisController extends Controller {


private $listeFavorisDAO;
private $listeFavorisAnnonceDAO;

public function __construct()
{
    $this->listeFavorisDAO = new ListeFavorisDAO();
    $this->listeFavorisAnnonceDAO = new ListeFavorisAnnonceDAO();
}

  
    public function addList() {
        if (empty($_SESSION["isLoggedin"])) {
            header("Location: " . Config::url("auth/login"));
            exit();
        }

        global $listeFavorisDAO;
        $userId = $_SESSION["user_id"];
        $listes = $listeFavorisDAO->getListesByUserId($userId);

        // Rendu de la vue située dans app/Views/annonce/addList.php
        $this->render("annonce/addList", [
            "listes" => $listes,
            "token" => Token::generate(),
        ]);
    }

    /**
     * Crée une nouvelle liste
     */
    public function create() {
        if (empty($_SESSION["isLoggedin"])) {
            header("Location: " . Config::url("auth/login"));
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!Token::check($_POST["token"] ?? "")) {
                header("Location: " . Config::url("addList/create") . "?error=invalid_token");
                exit();
            }

            global $listeFavorisDAO;
            $titreListe = trim($_POST["titre_liste"] ?? "");
            $userId = $_SESSION["user_id"];

            if (!empty($titreListe)) {
                $liste = new ListeFavoris([
                    "titre_liste" => $titreListe,
                    "id_utilisateur" => $userId,
                ]);
                if ($listeFavorisDAO->save($liste)) {
                    header("Location: " . Config::url("annonce/addList") . "?success=liste_created");
                    exit();
                }
            }
        }
        header("Location: " . Config::url("addList/create"));
        exit();
    }

    /**
     * Supprime une liste
     */
    public function delete($id) {
        if (empty($_SESSION["isLoggedin"])) {
            header("Location: " . Config::url("auth/login"));
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!Token::check($_POST["token"] ?? "")) {
                header("Location: " . Config::url("addList/delete") . "?error=invalid_token");
                exit();
            }

            global $listeFavorisDAO;
            $userId = $_SESSION["user_id"];
            $listeId = (int)$id;

            $liste = $listeFavorisDAO->getListeByIdAndUserId($listeId, $userId);

            if ($liste && $listeFavorisDAO->delete($listeId)) {
                header("Location: " . Config::url("addList/delete") . "?success=liste_deleted");
                exit();
            }
        }
        header("Location: " . Config::url("addList/delete"));
        exit();
    }

    /**
     * API JSON pour la modale dans addList
     */
    public function getAnnoncesJson() {
        header('Content-Type: application/json');
        if (empty($_SESSION["isLoggedin"])) {
            echo json_encode(["success" => false, "error" => "Non connecté"]);
            exit();
        }

        global $listeFavorisAnnonceDAO;
        $idListe = (int)($_GET["id"] ?? 0);
        $userId = $_SESSION["user_id"];

        $annonces = $listeFavorisAnnonceDAO->getAnnoncesByListeId($idListe, $userId);
        $data = [];
        foreach ($annonces as $a) {
            $data[] = [
                "id" => $a->getId(),
                "titre" => $a->getTitre(),
                "loyer" => $a->getLoyer(),
                "photo" => $a->getPhoto()
            ];
        }
        echo json_encode(["success" => true, "annonces" => $data]);
        exit();
    }

    /**
     * API JSON pour retirer une annonce
     */
    public function removeAnnonceAjax() {
        header('Content-Type: application/json');
        if (empty($_SESSION["isLoggedin"]) || !Token::check($_POST["token"] ?? "")) {
            echo json_encode(["success" => false, "error" => "Session ou token invalide"]);
            exit();
        }

        global $listeFavorisAnnonceDAO;
        $idAnnonce = (int)($_POST["id_annonce"] ?? 0);
        $idListe = (int)($_POST["id_liste"] ?? 0);

        if ($listeFavorisAnnonceDAO->removeAnnonceFromListe($idListe, $idAnnonce)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Erreur"]);
        }
        exit();
    }
    
    /**
     * Ajoute une annonce (depuis show.php)
     */
    public function addAnnonce() {
        if (empty($_SESSION["isLoggedin"])) {
            header("Location: " . Config::url("auth/login"));
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!Token::check($_POST["token"] ?? "")) {
                $idAnnonce = (int)($_POST["id_annonce"] ?? 0);
                header("Location: " . Config::url("annonce/show") . "?id=$idAnnonce&error=invalid_token");
                exit();
            }

         
            $idAnnonce = (int)($_POST["id_annonce"] ?? 0);
            $userId = $_SESSION["user_id"];
            
            if (isset($_POST["new_liste_titre"]) && !empty(trim($_POST["new_liste_titre"]))) {
                $titreListe = trim($_POST["new_liste_titre"]);
                $liste = new \App\Models\ListeFavoris([
                    "titre_liste" => $titreListe,
                    "id_utilisateur" => $userId,
                ]);
                
                if ($listeFavorisDAO->save($liste)) {
                    $idListe = $listeFavorisDAO->getDb()->lastInsertId();
                    if ($listeFavorisAnnonceDAO->addAnnonce($idListe, $idAnnonce)) {
                        header("Location: " . Config::url("annonce/show") . "?id=$idAnnonce&success=annonce_added_to_new_list");
                        exit();
                    }
                }
            }

            $idListe = (int)($_POST["id_liste"] ?? 0);
            if ($idListe > 0 && !$listeFavorisAnnonceDAO->isAnnonceInListe($idListe, $idAnnonce)) {
                if ($listeFavorisAnnonceDAO->addAnnonce($idListe, $idAnnonce)) {
                    header("Location: " . Config::url("annonce/show") . "?id=$idAnnonce&success=annonce_added_to_list");
                    exit();
                }
            }
        }
        header("Location: " . Config::url("/"));
        exit();
    }
}
