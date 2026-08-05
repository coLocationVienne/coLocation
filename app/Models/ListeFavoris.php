<?php

namespace App\Models;

class ListeFavoris {
    private ?int $id_liste;
    private string $titre_liste;
    private int $id_utilisateur;
    private string $date_creation;

    public function __construct(array $data) {
        $this->id_liste = isset($data['id_listeFavoris']) ? (int)$data['id_listeFavoris'] : null;
        $this->titre_liste = $data['titre_liste'] ?? '';
        $this->id_utilisateur = (int)($data['id_utilisateur'] ?? 0);
        $this->date_creation = $data['date_creation'] ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getIdListe(): ?int { return $this->id_liste; }
    public function getTitreListe(): string { return $this->titre_liste; }
    public function getIdUtilisateur(): int { return $this->id_utilisateur; }
    public function getDateCreation(): string { return $this->date_creation; }

    // Setters
    public function setTitreListe(string $titre_liste): void { $this->titre_liste = $titre_liste; }
    public function setIdUtilisateur(int $id_utilisateur): void { $this->id_utilisateur = $id_utilisateur; }
}
