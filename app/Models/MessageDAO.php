<?php

namespace App\Models;

class MessageDAO extends \App\Models\DAO {
    public function __construct() {
        parent::__construct('envoi_message', 'id_utilisateur');
    }

    protected function hydrate(array $row): Message {
        return new Message($row);
    }

    protected function dehydrate(object $message): array {
        /** @var Message $message */
        return [
            'id_utilisateur' => $message->getSenderId(),
            'id_utilisateur_1' => $message->getReceiverId(),
            'id_annonce' => $message->getAnnonceId(),
            'date_' => $message->getDate(),
            'contenu' => $message->getContenu()
        ];
    }

    /**
     * Get conversation between two users for a specific announcement
     */
    public function getConversation(int $user1, int $user2, int $annonceId): array {
        $query = "SELECT * FROM envoi_message 
                  WHERE id_annonce = :annonce_id 
                  AND ((id_utilisateur = :u1 AND id_utilisateur_1 = :u2) 
                  OR (id_utilisateur = :u2 AND id_utilisateur_1 = :u1))
                  ORDER BY date_ ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'annonce_id' => $annonceId,
            'u1' => $user1,
            'u2' => $user2
        ]);
        
        $messages = [];
        foreach ($stmt->fetchAll() as $row) {
            $messages[] = $this->hydrate($row);
        }
        return $messages;
    }

    public function getUserConversations(int $userId): array {
        $query = "SELECT m.id_annonce, 
                         ANY_VALUE(m.id_utilisateur) as id_utilisateur, 
                         ANY_VALUE(m.id_utilisateur_1) as id_utilisateur_1, 
                         u.prenom, u.nom, a.titre as annonce_titre,
                         MAX(m.date_) as date_,
                         ANY_VALUE(m.contenu) as contenu
                  FROM envoi_message m
                  JOIN utilisateur u ON (m.id_utilisateur = u.id_utilisateur OR m.id_utilisateur_1 = u.id_utilisateur)
                  JOIN annonce a ON m.id_annonce = a.id_annonce
                  WHERE (m.id_utilisateur = :userId OR m.id_utilisateur_1 = :userId)
                  AND u.id_utilisateur != :userId
                  GROUP BY m.id_annonce, u.id_utilisateur, u.prenom, u.nom, a.titre
                  ORDER BY date_ DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * For now, just returning a count of recent messages as a placeholder
     */
    public function countUnread(int $userId): int {
        $query = "SELECT COUNT(*) FROM envoi_message WHERE id_utilisateur_1 = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['userId' => $userId]);
        return (int)$stmt->fetchColumn();
    }
}
