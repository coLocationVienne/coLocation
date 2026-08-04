<?php

namespace App\Models;

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
