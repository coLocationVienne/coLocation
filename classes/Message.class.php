<?php

namespace colocation;

class Message {
    private ?int $id_utilisateur; // Sender
    private ?int $id_utilisateur_1; // Receiver
    private ?int $id_annonce;
    private string $date_;
    private string $contenu;

    public function __construct(array $data) {
        $this->id_utilisateur = (int)($data['id_utilisateur'] ?? 0);
        $this->id_utilisateur_1 = (int)($data['id_utilisateur_1'] ?? 0);
        $this->id_annonce = isset($data['id_annonce']) ? (int)$data['id_annonce'] : null;
        $this->date_ = $data['date_'] ?? date('Y-m-d H:i:s');
        $this->contenu = $data['contenu'] ?? '';
    }

    // Getters
    public function getSenderId(): int { return $this->id_utilisateur; }
    public function getReceiverId(): int { return $this->id_utilisateur_1; }
    public function getAnnonceId(): ?int { return $this->id_annonce; }
    public function getDate(): string { return $this->date_; }
    public function getContenu(): string { return $this->contenu; }
}

class MessageDAO extends \colocation\DAO {
    public function __construct() {
        // This table doesn't have a single primary key, so we use a dummy one for now
        // Or we could update the table to have an id_message
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

    /**
     * Get all conversations for a user
     */
    public function getUserConversations(int $userId): array {
        $query = "SELECT m.*, u.prenom, u.nom, a.titre as annonce_titre
                  FROM envoi_message m
                  JOIN utilisateur u ON (m.id_utilisateur = u.id_utilisateur OR m.id_utilisateur_1 = u.id_utilisateur)
                  JOIN annonce a ON m.id_annonce = a.id_annonce
                  WHERE (m.id_utilisateur = :userId OR m.id_utilisateur_1 = :userId)
                  AND u.id_utilisateur != :userId
                  GROUP BY m.id_annonce, u.id_utilisateur
                  ORDER BY m.date_ DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Count unread messages (if we add an is_read column later)
     * For now, just returning a count of recent messages as a placeholder
     */
    public function countUnread(int $userId): int {
        $query = "SELECT COUNT(*) FROM envoi_message WHERE id_utilisateur_1 = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['userId' => $userId]);
        return (int)$stmt->fetchColumn();
    }
}
