<?php

namespace App\Models;

class ListeFavoris {
    private ?int $id_listeFavoris;
    private string $titre_liste;
    private ?int $id_utilisateur;

    public function __construct(array $data){
        $this->id_listeFavoris = isset($data['id_listeFavoris']) ? (int)$data['id_listeFavoris'] : null;
        $this->titre_liste = $data['titre_liste'] ?? '';
        $this->id_utilisateur = isset($data['id_utilisateur']) ? (int)$data['id_utilisateur'] : null;
    }

    // Getters
    public function getIdListeFavoris(): ?int {
        return $this->id_listeFavoris;
    }

    public function getTitreListe(): string {
        return $this->titre_liste;
    }

    public function getIdUtilisateur(): ?int {
        return $this->id_utilisateur;
    }

    // Setters
    public function setIdListeFavoris(?int $id_listeFavoris): self {
        $this->id_listeFavoris = $id_listeFavoris;
        return $this;
    }

    public function setTitreListe(string $titre_liste): self {
        $this->titre_liste = $titre_liste;
        return $this;
    }

    public function setIdUtilisateur(?int $id_utilisateur): self {
        $this->id_utilisateur = $id_utilisateur;
        return $this;
    }
}
