<?php

namespace App\Models;

class ListeFavoris {

    private ?int $id_listeFavoris;
    private string $titre_liste;
    private ?int $id_utilisateur;

    public function __construct(array $data){
        $this->id_listeFavoris = isset($data['id_liste']) ? (int)$data['id_liste'] : null;
        $this->titre_liste = $data['titre_liste'] ?? '';
        $this->id_utilisateur = (int)($data['id_utilisateur'] ?? 0);

    }
     

    //getters
        public function getIdListe(): ?int { return $this->id_liste; }
        public function getNomListe(): string { return $this->nom_liste; }
        public function getIdUtilisateur(): int { return $this->id_utilisateur; }



    //setters
    public function setIdListeFavoris(?int $id_listeFavoris): self
    {$this->id_listeFavoris = $id_listeFavoris; 
    return $this;
    }
    public function setTitreListe(string $titre_liste): self
    {$this->titre_liste = $titre_liste;
    return $this;
    }
   
}

