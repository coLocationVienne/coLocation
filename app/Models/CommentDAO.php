<?php

namespace App\Models;

class CommentDAO extends \App\Models\DAO {
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
            'note' => (float)$comment->getNote(),
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
