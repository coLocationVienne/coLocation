<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Token;
use App\Core\Config;
use App\Models\ListeFavoris;

class ListeFavorisController extends Controller {

    public function index() {
        if (empty($_SESSION["isLoggedin"])) {
            header("Location: " . Config::url("auth/login"));
            exit();
        }

        global $listeFavorisDAO;
        $userId = $_SESSION["user_id"];
        $listes = $listeFavorisDAO->getListesByUserId($userId);

        $this->render("favoris/index", [
            "listes" => $listes,
            "token" => Token::generate(),
        ]);
    }

    public function create() {
        if (empty($_SESSION["isLoggedin"])) {
            header("Location: " . Config::url("auth/login"));
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!Token::check($_POST["token"] ?? "")) {
                header("Location: " . Config::url("favoris/index") . "?error=invalid_token");
                exit();
            }

            global $listeFavorisDAO;
            $nomListe = trim($_POST["titre_liste"] ?? "");
            $userId = $_SESSION["user_id"];

            if (!empty($nomListe)) {
                $liste = new ListeFavoris([
                    "titre_liste" => $nomListe,
                    "id_utilisateur" => $userId,
                ]);
                if ($listeFavorisDAO->save($liste)) {
                    header("Location: " . Config::url("favoris/index") . "?success=liste_created");
                    exit();
                } else {
                    header("Location: " . Config::url("favoris/index") . "?error=creation_failed");
                    exit();
                }
            } else {
                header("Location: " . Config::url("favoris/index") . "?error=empty_name");
                exit();
            }
        }
        header("Location: " . Config::url("favoris/index"));
        exit();
    }

    public function showListe($id) {
        if (empty($_SESSION["isLoggedin"])) {
            header("Location: " . Config::url("auth/login"));
            exit();
        }

        global $listeFavorisDAO, $listeFavorisAnnonceDAO;
        $userId = $_SESSION["user_id"];
        $listeId = (int)$id;

        $liste = $listeFavorisDAO->getListeByIdAndUserId($listeId, $userId);

        if (!$liste) {
            $this->render("errors/404", ["message" => "Liste de favoris non trouvée ou non autorisée."]);
            return;
        }

        $annonces = $listeFavorisAnnonceDAO->getAnnoncesByListeId($listeId, $userId);

        $this->render("favoris/show_liste", [
            "liste" => $liste,
            "annonces" => $annonces,
            "token" => Token::generate(),
        ]);
    }

    public function delete($id) {
        if (empty($_SESSION["isLoggedin"])) {
            header("Location: " . Config::url("auth/login"));
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!Token::check($_POST["token"] ?? "")) {
                header("Location: " . Config::url("favoris/index") . "?error=invalid_token");
                exit();
            }

            global $listeFavorisDAO;
            $userId = $_SESSION["user_id"];
            $listeId = (int)$id;

            $liste = $listeFavorisDAO->getListeByIdAndUserId($listeId, $userId);

            if ($liste && $listeFavorisDAO->delete($listeId)) {
                header("Location: " . Config::url("favoris/index") . "?success=liste_deleted");
                exit();
            } else {
                header("Location: " . Config::url("favoris/index") . "?error=delete_failed");
                exit();
            }
        }
        header("Location: " . Config::url("favoris/index"));
        exit();
    }

    public function addAnnonce() {
        if (empty($_SESSION["isLoggedin"])) {
            header("Location: " . Config::url("auth/login"));
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $idAnnonce = (int)($_POST["id_annonce"] ?? 0);
            
            if (!Token::check($_POST["token"] ?? "")) {
                header("Location: " . Config::url("annonce/show") . "?id=$idAnnonce&error=invalid_token");
                exit();
            }

            global $listeFavorisAnnonceDAO, $listeFavorisDAO;
            $idListe = (int)($_POST["id_liste"] ?? 0);
            $newListeNom = trim($_POST["new_liste_nom"] ?? "");
            $userId = $_SESSION["user_id"];

            if (!empty($newListeNom)) {
                $newListe = new ListeFavoris([
                    "titre_liste" => $newListeNom,
                    "id_utilisateur" => $userId,
                ]);
                if ($listeFavorisDAO->save($newListe)) {
                    $idListe = (int)$listeFavorisDAO->getDb()->lastInsertId();
                } else {
                    header("Location: " . Config::url("annonce/show") . "?id=$idAnnonce&error=new_list_creation_failed");
                    exit();
                }
            }

            $liste = $listeFavorisDAO->getListeByIdAndUserId($idListe, $userId);

            if ($liste && $idAnnonce > 0) {
                if (!$listeFavorisAnnonceDAO->isAnnonceInListe($idListe, $idAnnonce)) {
                    if ($listeFavorisAnnonceDAO->addAnnonceToListe($idListe, $idAnnonce)) {
                        header("Location: " . Config::url("annonce/show") . "?id=$idAnnonce&success=annonce_added_to_list");
                        exit();
                    } else {
                        header("Location: " . Config::url("annonce/show") . "?id=$idAnnonce&error=add_to_list_failed");
                        exit();
                    }
                } else {
                    header("Location: " . Config::url("annonce/show") . "?id=$idAnnonce&info=annonce_already_in_list");
                    exit();
                }
            } else {
                header("Location: " . Config::url("annonce/show") . "?id=$idAnnonce&error=invalid_list_or_annonce");
                exit();
            }
        }
        header("Location: " . Config::url("/"));
        exit();
    }

    public function removeAnnonce() {
        if (empty($_SESSION["isLoggedin"])) {
            header("Location: " . Config::url("auth/login"));
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $idListe = (int)($_POST["id_liste"] ?? 0);
            if (!Token::check($_POST["token"] ?? "")) {
                header("Location: " . Config::url("favoris/showListe") . "?id=$idListe&error=invalid_token");
                exit();
            }

            global $listeFavorisAnnonceDAO, $listeFavorisDAO;
            $idAnnonce = (int)($_POST["id_annonce"] ?? 0);
            $userId = $_SESSION["user_id"];

            $liste = $listeFavorisDAO->getListeByIdAndUserId($idListe, $userId);

            if ($liste && $idAnnonce > 0) {
                if ($listeFavorisAnnonceDAO->removeAnnonceFromListe($idListe, $idAnnonce)) {
                    header("Location: " . Config::url("favoris/showListe") . "?id=$idListe&success=annonce_removed_from_list");
                    exit();
                } else {
                    header("Location: " . Config::url("favoris/showListe") . "?id=$idListe&error=remove_from_list_failed");
                    exit();
                }
            } else {
                header("Location: " . Config::url("favoris/showListe") . "?id=$idListe&error=invalid_list_or_annonce");
                exit();
            }
        }
        header("Location: " . Config::url("/"));
        exit();
    }
}
