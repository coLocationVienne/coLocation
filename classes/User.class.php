<?php 

namespace colocation;

class User {
    private ?int $id_utilisateur;
    private string $prenom;
    private string $nom;
    private string $email;
    private string $mot_de_passe;
    private string $situation_professionnel;
    private bool $garant;
    private float $retraite;
    private float $caisse_allocation_familial;
    private string $date_naissance;
    private string $photo_profil;
    private float $salaire_mensuel_net;
    private float $revenu_fiscal;
    private int $id_role;

    public function __construct(array $data) {
        $this->id_utilisateur = $data['id_utilisateur'] ?? null;
        $this->prenom = $data['prenom'] ?? '';
        $this->nom = $data['nom'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->mot_de_passe = $data['mot_de_passe'] ?? '';
        $this->situation_professionnel = $data['situation_professionnel'] ?? '';
        $this->garant = isset($data['garant']) ? (bool)$data['garant'] : false;
        $this->retraite = (float)($data['retraite'] ?? 0);
        $this->caisse_allocation_familial = (float)($data['caisse_allocation_familial'] ?? 0);
        $this->date_naissance = $data['date_naissance'] ?? '';
        $this->photo_profil = $data['photo_profil'] ?? '';
        $this->salaire_mensuel_net = (float)($data['salaire_mensuel_net'] ?? 0);
        $this->revenu_fiscal = (float)($data['revenu_fiscal'] ?? 0);
        $this->id_role = (int)($data['id_role'] ?? 3);
    }   

    // Getters
    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function getPrenom(): string { return $this->prenom; }
    public function getNom(): string { return $this->nom; }
    public function getEmail(): string { return $this->email; }
    public function getMotDePasse(): string { return $this->mot_de_passe; }
    public function getSituationProfessionnel(): string { return $this->situation_professionnel; }
    public function isGarant(): bool { return $this->garant; }
    public function getRetraite(): float { return $this->retraite; }
    public function getCaisseAllocationFamilial(): float { return $this->caisse_allocation_familial; }
    public function getDateNaissance(): string { return $this->date_naissance; }
    public function getPhotoProfil(): string { return $this->photo_profil; }
    public function getSalaireMensuelNet(): float { return $this->salaire_mensuel_net; }
    public function getRevenuFiscal(): float { return $this->revenu_fiscal; }
    public function getIdRole(): int { return $this->id_role; }

    // Setters
    public function setMotDePasse(string $password): void { $this->mot_de_passe = $password; }
    public function setPhotoProfil(string $photo): void { $this->photo_profil = $photo; }
    public function setIdRole(int $role): void { $this->id_role = $role; }
    public function setPrenom(string $val): void { $this->prenom = $val; }
    public function setNom(string $val): void { $this->nom = $val; }
    public function setEmail(string $val): void { $this->email = $val; }
    public function setSituationProfessionnel(string $val): void { $this->situation_professionnel = $val; }
    public function setGarant(bool $val): void { $this->garant = $val; }
    public function setRetraite(float $val): void { $this->retraite = $val; }
    public function setCaisseAllocationFamilial(float $val): void { $this->caisse_allocation_familial = $val; }
    public function setDateNaissance(string $val): void { $this->date_naissance = $val; }
    public function setSalaireMensuelNet(float $val): void { $this->salaire_mensuel_net = $val; }
    public function setRevenuFiscal(float $val): void { $this->revenu_fiscal = $val; }

    public function verifierMotDePasse(string $mot_de_passe): bool {
        return password_verify($mot_de_passe, $this->mot_de_passe);
    }
}

class UserDAO extends \colocation\DAO {
    public function __construct() {
        parent::__construct('utilisateur', 'id_utilisateur');
    }

    protected function hydrate(array $row): User {
        return new User($row);
    }

    protected function dehydrate(object $user): array {
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
            'id_role' => $user->getIdRole()
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
