<?php

namespace App\Models;

use App\Models\DAO;
use App\Models\ListeFavoris;

class ListeFavorisDAO extends DAO {
    public function __construct() {
        parent::__construct('liste_favoris', 'id_listeFavoris');
    }

    protected function hydrate(array $row): ListeFavoris {
        return new ListeFavoris($row);
    }

    protected function dehydrate(object $listeFavoris): array {
        /** @var ListeFavoris $listeFavoris */
        return [
            'id_listeFavoris' => $listeFavoris->getIdListeFavoris(),
            'titre_liste' => $listeFavoris->getTitreListe(),
            'id_utilisateur' => $listeFavoris->getIdUtilisateur(),
        ];
    }

    public function getListesByUserId(int $userId): array {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_utilisateur = :id_utilisateur";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_utilisateur', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();

        $listes = [];
        foreach ($results as $row) {
            $listes[] = $this->hydrate($row);
        }
        return $listes;
    }

    public function getListeByIdAndUserId(int $listeId, int $userId): ?ListeFavoris {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_listeFavoris = :id_liste AND id_utilisateur = :id_utilisateur";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_liste', $listeId, \PDO::PARAM_INT);
        $stmt->bindParam(':id_utilisateur', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? $this->hydrate($result) : null;
    }
}
