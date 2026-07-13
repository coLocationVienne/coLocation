<?php

namespace colocation;

class Annonce {
    private ?int $id_annonce;
    private string $titre;
    private string $adresse_1;
    private string $ville;
    private int $code_postal;
    private float $loyer;
    private string $description;
    private string $gps;
    private ?string $photo_url;

    public function __construct(array $data) {
        $this->id_annonce = $data['id_annonce'] ?? null;
        $this->titre = $data['titre'] ?? '';
        $this->adresse_1 = $data['adresse_1'] ?? '';
        $this->ville = $data['ville'] ?? '';
        $this->code_postal = (int)($data['code_postal'] ?? 0);
        $this->loyer = (float)($data['loyer_location_chez_habitant'] ?? 0);
        $this->description = $data['description'] ?? '';
        $this->gps = $data['carte_coordonnee_GPS'] ?? '';
        $this->photo_url = $data['photo_url'] ?? null;
    }

    // Getters
    public function getId(): ?int { return $this->id_annonce; }
    public function getTitre(): string { return $this->titre; }
    public function getLoyer(): float { return $this->loyer; }
    public function getGps(): string { return $this->gps; }
    public function getPhoto(): ?string { return $this->photo_url; }
    public function getVille(): string { return $this->ville; }
}

class AnnonceDAO extends \colocation\DAO {
    public function __construct() {
        parent::__construct('annonce', 'id_annonce');
    }

    protected function hydrate(array $row): Annonce {
        return new Annonce($row);
    }

    protected function dehydrate(object $annonce): array {
        /** @var Annonce $annonce */
        return [
            'id_annonce' => $annonce->getId(),
            'titre' => $annonce->getTitre(),
            'carte_coordonnee_GPS' => $annonce->getGps()
            // ... add other fields as needed
        ];
    }

    /**
     * Get all announcements with their first photo
     */
    public function getAllWithPhotos(): array {
        $query = "SELECT a.*, p.url as photo_url 
                  FROM annonce a 
                  LEFT JOIN annonce_photo ap ON a.id_annonce = ap.id_annonce 
                  LEFT JOIN photo p ON ap.id_photo = p.id_photo 
                  GROUP BY a.id_annonce";
        $stmt = $this->db->query($query);
        $results = $stmt->fetchAll();
        
        $annonces = [];
        foreach ($results as $row) {
            $annonces[] = $this->hydrate($row);
        }
        return $annonces;
    }
}
