<?php

namespace App\Models;

class Visit {
    private ?int $id_visite;
    private int $id_creneauVisite;
    private int $id_utilisateur;
    private string $statut;
    private string $date_demande;
    private ?string $message;
    private ?string $date_annulation;

    public function __construct(array $data = []) {
        $this->id_visite = $data['id_visite'] ?? null;
        $this->id_creneauVisite = (int)($data['id_creneauVisite'] ?? 0);
        $this->id_utilisateur = (int)($data['id_utilisateur'] ?? 0);
        $this->statut = $data['statut'] ?? 'en_attente';
        $this->date_demande = $data['date_demande'] ?? date('Y-m-d H:i:s');
        $this->message = $data['message'] ?? null;
        $this->date_annulation = $data['date_annulation'] ?? null;
    }

    // Getters
    public function getId(): ?int { return $this->id_visite; }
    public function getIdCreneau(): int { return $this->id_creneauVisite; }
    public function getIdUtilisateur(): int { return $this->id_utilisateur; }
    public function getStatut(): string { return $this->statut; }
    public function getDateDemande(): string { return $this->date_demande; }
    public function getMessage(): ?string { return $this->message; }
    public function getDateAnnulation(): ?string { return $this->date_annulation; }

    // Setters
    public function setId(?int $id): void { $this->id_visite = $id; }
    public function setIdCreneau(int $id): void { $this->id_creneauVisite = $id; }
    public function setIdUtilisateur(int $id): void { $this->id_utilisateur = $id; }
    public function setStatut(string $statut): void { $this->statut = $statut; }
    public function setDateDemande(string $date): void { $this->date_demande = $date; }
    public function setMessage(?string $msg): void { $this->message = $msg; }
    public function setDateAnnulation(?string $date): void { $this->date_annulation = $date; }

    public function isConfirmed(): bool { return $this->statut === 'confirme'; }
    public function isPending(): bool { return $this->statut === 'en_attente'; }
    public function isRefused(): bool { return $this->statut === 'refuse'; }
    public function isCanceled(): bool { return $this->statut === 'annule'; }
}
