<?php

namespace App\Models;

use App\Models\DAO;
use App\Models\Annonce;

class ListeFavorisAnnonceDAO extends DAO {
    public function __construct() {
        parent::__construct('favoris', 'id_favoris');
    }

    protected function hydrate(array $row): object {
        // This DAO mainly handles the relationship, so we return a generic object or handle it in specific methods
        return (object)$row;
    }

    protected function dehydrate(object $entity): array {
        return (array)$entity;
    }

    public function getAnnoncesByListeId(int $listeId, int $userId): array {
        $query = "SELECT a.*, p.url as photo_url FROM annonce a 
                  JOIN favoris f ON a.id_annonce = f.id_annonce 
                  JOIN liste_favoris lf ON f.id_listeFavoris = lf.id_listeFavoris 
                  LEFT JOIN (
                      SELECT id_annonce, MIN(id_photo) as first_photo_id
                      FROM annonce_photo
                      GROUP BY id_annonce
                  ) ap_first ON a.id_annonce = ap_first.id_annonce
                  LEFT JOIN photo p ON ap_first.first_photo_id = p.id_photo
                  WHERE lf.id_listeFavoris = :liste_id AND lf.id_utilisateur = :user_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':liste_id', $listeId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();

        $annonces = [];
        foreach ($results as $row) {
            $annonces[] = new Annonce($row);
        }
        return $annonces;
    }

    public function isAnnonceInListe(int $listeId, int $annonceId): bool {
        $query = "SELECT COUNT(*) FROM favoris WHERE id_listeFavoris = :liste_id AND id_annonce = :annonce_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':liste_id', $listeId, \PDO::PARAM_INT);
        $stmt->bindParam(':annonce_id', $annonceId, \PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn() > 0;
    }

    public function addAnnonceToListe(int $listeId, int $annonceId): bool {
        $query = "INSERT INTO favoris (id_listeFavoris, id_annonce) VALUES (:liste_id, :annonce_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':liste_id', $listeId, \PDO::PARAM_INT);
        $stmt->bindParam(':annonce_id', $annonceId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function removeAnnonceFromListe(int $listeId, int $annonceId): bool {
        $query = "DELETE FROM favoris WHERE id_listeFavoris = :liste_id AND id_annonce = :annonce_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':liste_id', $listeId, \PDO::PARAM_INT);
        $stmt->bindParam(':annonce_id', $annonceId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
