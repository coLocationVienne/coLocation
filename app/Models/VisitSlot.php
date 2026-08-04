<?php

namespace App\Models;

class VisitSlot {
    private ?int $id_creneauVisite;
    private string $date_visite;
    private string $heure_debut;
    private string $heure_fin;
    private int $nb_personne_max;
    private ?int $id_annonce;

    public function __construct(array $data = []) {
        $this->id_creneauVisite = $data['id_creneauVisite'] ?? null;
        $this->date_visite = $data['date_visite'] ?? '';
        $this->heure_debut = $data['heure_debut'] ?? '';
        $this->heure_fin = $data['heure_fin'] ?? '';
        $this->nb_personne_max = (int)($data['nb_personne_max'] ?? 0);
        $this->id_annonce = $data['id_annonce'] ?? null;
    }

    // Getters
    public function getId(): ?int { return $this->id_creneauVisite; }
    public function getDateVisite(): string { return $this->date_visite; }
    public function getHeureDebut(): string { return $this->heure_debut; }
    public function getHeureFin(): string { return $this->heure_fin; }
    public function getNbPersonneMax(): int { return $this->nb_personne_max; }
    public function getIdAnnonce(): ?int { return $this->id_annonce; }

    // Setters
    public function setId(?int $id): void { $this->id_creneauVisite = $id; }
    public function setDateVisite(string $date): void { $this->date_visite = $date; }
    public function setHeureDebut(string $time): void { $this->heure_debut = $time; }
    public function setHeureFin(string $time): void { $this->heure_fin = $time; }
    public function setNbPersonneMax(int $nb): void { $this->nb_personne_max = $nb; }
    public function setIdAnnonce(?int $id): void { $this->id_annonce = $id; }
}
