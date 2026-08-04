<?php

namespace App\Models;

class Annonce {
    private ?int $id_annonce;
    private string $titre;
    private string $adresse_1;
    private ?string $adresse_2;
    private ?string $adresse_3;
    private ?string $adresse_4;
    private string $ville;
    private int $code_postal;
    private float $loyer_location_chez_habitant;
    private string $description;
    private float $surface_logement;
    private float $surface_chambres;
    private int $nombre_chambre;
    private ?string $date_expiration;
    private string $date_publication;
    private string $date_modification;
    private ?string $carte_coordonnee_GPS;
    private ?string $date_cloture;
    private float $loyer_colocation;
    
    // Extra properties for JOIN results
    private ?string $photo_url;
    private ?int $owner_id;

    public function __construct(array $data) {
        $this->id_annonce = isset($data['id_annonce']) ? (int)$data['id_annonce'] : null;
        $this->titre = $data['titre'] ?? '';
        $this->adresse_1 = $data['adresse_1'] ?? '';
        $this->adresse_2 = $data['adresse_2'] ?? null;
        $this->adresse_3 = $data['adresse_3'] ?? null;
        $this->adresse_4 = $data['adresse_4'] ?? null;
        $this->ville = $data['ville'] ?? '';
        $this->code_postal = (int)($data['code_postal'] ?? 0);
        $this->loyer_location_chez_habitant = (float)($data['loyer_location_chez_habitant'] ?? 0);
        $this->description = $data['description'] ?? '';
        $this->surface_logement = (float)($data['surface_logement'] ?? 0);
        $this->surface_chambres = (float)($data['surface_chambres'] ?? 0);
        $this->nombre_chambre = (int)($data['nombre_chambre'] ?? 0);
        $this->date_expiration = $data['date_expiration'] ?? null;
        $this->date_publication = $data['date_publication'] ?? date('Y-m-d');
        $this->date_modification = $data['date_modification'] ?? date('Y-m-d');
        $this->carte_coordonnee_GPS = $data['carte_coordonnee_GPS'] ?? null;
        $this->date_cloture = $data['date_cloture'] ?? null;
        $this->loyer_colocation = (float)($data['loyer_colocation'] ?? 0);
        
        // Joined data
        $this->photo_url = $data['photo_url'] ?? null;
        $this->owner_id = isset($data['owner_id']) ? (int)$data['owner_id'] : null;
    }

    // Getters
    public function getId(): ?int { return $this->id_annonce; }
    public function getTitre(): string { return $this->titre; }
    public function getAdresse1(): string { return $this->adresse_1; }
    public function getAdresse2(): ?string { return $this->adresse_2; }
    public function getAdresse3(): ?string { return $this->adresse_3; }
    public function getAdresse4(): ?string { return $this->adresse_4; }
    public function getVille(): string { return $this->ville; }
    public function getCodePostal(): int { return $this->code_postal; }
    public function getLoyerHabitant(): float { return $this->loyer_location_chez_habitant; }
    public function getLoyerColoc(): float { return $this->loyer_colocation; }
    public function getLoyer(): float { 
        return $this->loyer_colocation > 0 ? $this->loyer_colocation : $this->loyer_location_chez_habitant; 
    }
    public function getDescription(): string { return $this->description; }
    public function getSurfaceLogement(): float { return $this->surface_logement; }
    public function getSurfaceChambre(): float { return $this->surface_chambres; }
    public function getSurfaceChambres(): float { return $this->surface_chambres; }
    public function getNombreChambre(): int { return $this->nombre_chambre; }
    public function getDateExpiration(): ?string { return $this->date_expiration; }
    public function getDatePublication(): string { return $this->date_publication; }
    public function getDateModification(): string { return $this->date_modification; }
    public function getGps(): ?string { return $this->carte_coordonnee_GPS; }
    public function getDateCloture(): ?string { return $this->date_cloture; }
    
    // Joined Getters
    public function getPhoto(): ?string { return $this->photo_url; }
    public function getOwnerId(): ?int { return $this->owner_id; }

    // Setters
    public function setTitre(string $titre): void { $this->titre = $titre; }
    public function setId(int $id): void { $this->id_annonce = $id; }
}
