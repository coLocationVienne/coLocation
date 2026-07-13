<?php

namespace colocation;

class Comment {
    private ?int $id_avis;
    private float $note;
    private string $date_;
    private string $commentaire;
    private int $id_annonce;
    private int $id_utilisateur; // Author
    private int $id_utilisateur_1; // Target (Owner)
    private ?string $author_name;

    public function __construct(array $data) {
        $this->id_avis = $data['id_avis'] ?? null;
        $this->note = (float)($data['note'] ?? 0);
        $this->date_ = $data['date_'] ?? date('Y-m-d');
        $this->commentaire = $data['commentaire'] ?? '';
        $this->id_annonce = (int)$data['id_annonce'];
        $this->id_utilisateur = (int)$data['id_utilisateur'];
        $this->id_utilisateur_1 = (int)$data['id_utilisateur_1'];
        $this->author_name = $data['author_name'] ?? null;
    }

    // Getters
    public function getId(): ?int { return $this->id_avis; }
    public function getNote(): float { return $this->note; }
    public function getDate(): string { return $this->date_; }
    public function getCommentaire(): string { return $this->commentaire; }
    public function getAuthorName(): string { return $this->author_name ?? 'Utilisateur'; }
    public function getIdAnnonce(): int { return $this->id_annonce; }
    public function getIdUtilisateur(): int { return $this->id_utilisateur; }
    public function getIdUtilisateur1(): int { return $this->id_utilisateur_1; }
}

class CommentDAO extends \colocation\DAO {
    public function __construct() {
        parent::__construct('annonce_avis', 'id_avis');
    }

    protected function hydrate(array $row): Comment {
        return new Comment($row);
    }

    protected function dehydrate(object $comment): array {
        /** @var Comment $comment */
        return [
            'id_avis' => $comment->getId(),
            'note' => $comment->getNote(),
            'date_' => $comment->getDate(),
            'commentaire' => $comment->getCommentaire(),
            'id_annonce' => $comment->getIdAnnonce(),
            'id_utilisateur' => $comment->getIdUtilisateur(),
            'id_utilisateur_1' => $comment->getIdUtilisateur1()
        ];
    }

    public function getByAnnonceId(int $annonceId): array {
        $query = "SELECT a.*, CONCAT(u.prenom, ' ', u.nom) as author_name 
                  FROM annonce_avis a 
                  JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur 
                  WHERE a.id_annonce = :id 
                  ORDER BY a.date_ DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $annonceId]);
        $results = $stmt->fetchAll();
        
        $comments = [];
        foreach ($results as $row) {
            $comments[] = $this->hydrate($row);
        }
        return $comments;
    }
}
