<?php

namespace App\Models;

class UserDAO extends \App\Models\DAO {
    public function __construct() {
        parent::__construct('utilisateur', 'id_utilisateur');
    }

    protected function hydrate(array $row): User {
        return new User($row);
    }

    protected function dehydrate(object $user): array {
        /** @var User $user */
        return [
            'id_utilisateur' => $user->getIdUtilisateur(),
            'prenom' => $user->getPrenom(),
            'nom' => $user->getNom(),
            'email' => $user->getEmail(),
            'mot_de_passe' => $user->getMotDePasse(),
            'situation_professionnel' => $user->getSituationProfessionnel(),
            'garant' => $user->isGarant() ? 1 : 0,
            'retraite' => $user->getRetraite(),
            'caisse_allocation_familial' => $user->getCaisseAllocationFamilial(),
            'date_naissance' => $user->getDateNaissance(),
            'photo_profil' => $user->getPhotoProfil(),
            'salaire_mensuel_net' => $user->getSalaireMensuelNet(),
            'revenu_fiscal' => $user->getRevenuFiscal(),
            'id_role' => $user->getIdRole(),
            'type_compte' => $user->getTypeCompte()
        ];
    }

    public function searchPaginated(string $search = '', string $role = '', int $page = 1, int $limit = 10): array {
        $offset = ($page - 1) * $limit;
        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search)";
            $params[':search'] = "%$search%";
        }

        if (!empty($role)) {
            $where[] = "LOWER(r.role) = LOWER(:role)";
            $params[':role'] = $role;
        }

        $whereClause = implode(" AND ", $where);

        $countQuery = "SELECT COUNT(*) FROM utilisateur u LEFT JOIN role r ON u.id_role = r.id_role WHERE $whereClause";
        $stmtCount = $this->db->prepare($countQuery);
        $stmtCount->execute($params);
        $total = (int)$stmtCount->fetchColumn();

        $query = "SELECT u.*, r.role as role_name 
                  FROM utilisateur u 
                  LEFT JOIN role r ON u.id_role = r.id_role 
                  WHERE $whereClause 
                  ORDER BY u.id_utilisateur DESC 
                  LIMIT $limit OFFSET $offset";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $users = [];
        foreach ($rows as $row) {
            $row['role'] = $row['role_name']; 
            $users[] = $row; 
        }

        return [
            'users' => $users,
            'total' => $total,
            'totalPages' => ceil($total / $limit),
            'currentPage' => $page
        ];
    }
}
