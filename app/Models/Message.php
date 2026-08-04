<?php

namespace App\Models;

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
