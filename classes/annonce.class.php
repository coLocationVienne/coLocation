<?php 

namespace colocation;

class Annonce {
    private ?int $id_annonce;
    private string $titre;
    private string $adresse_1;
    private string $adresse_2;
    private string $adresse_3;
    private string $adresse_4;
    private string $code_postal;
    private float  $loyer_location_chez_habitant;
    private string $description;
    private float $surface_logement;
    private string $surface_chambres;
    private int $nombre_chambre;
    private float $date_expiration;
    private float $date_publication;
    private float $date_modification;
    private string $carte_coordonnee_GPS;
    private float $date_cloture;
    private float $loyer_colocation;

    public function __construct(array $data){
       $this->id_annonce= $data['id_annonce'] ?? null;
       $this->titre = $data['titre'] ?? '';
       $this->adresse_1 = $data['adresse_1'] ?? '';
       $this->adresse_2 = $data['adresse_2'] ?? '';
       $this->adresse_3 = $data['adresse_3'] ?? '';
       $this->adresse_4 = $data['adresse_4'] ?? '';
       $this->code_postal = (float)($data['code_postal'] ?? 0);
       $this->loyer_location_chez_habitant = (float)($data['loyer_location_chez_habitant'] ?? 0);
       $this->description = $data['description'] ?? '';
       $this->surface_logement = (float)($data['surface_logement'] ?? 0);
       $this->surface_chambres = (float)($data['surface_chambres'] ?? 0);
       $this->nombre_chambre  = (int)($data['nombre_chambres'] ?? 0);
       $this->date_expiration = $data['date_expiration'] ?? '';
       $this->date_publication =$data['date_publication'] ?? '';
       $this->date_modification = $data['date_modification'] ?? '';
       $this->carte_coordonnee_GPS = (string) ($data['carte_coordonnee_GPS'] ?? '');
       $this->date_cloture = $data['date_cloture'] ?? '';
       $this->loyer_colocation = (float) ($data['loyer_colocation'] ?? 0);


    }





    /**
     * Get the value of id_annonce
     */
    public function getIdAnnonce(): ?int
    {
        return $this->id_annonce;
    }

    /**
     * Set the value of id_annonce
     */
    public function setIdAnnonce(?int $id_annonce): self
    {
        $this->id_annonce = $id_annonce;

        return $this;
    }

    /**
     * Get the value of titre
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * Set the value of titre
     */
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get the value of adresse_1
     */
    public function getAdresse1(): string
    {
        return $this->adresse_1;
    }

    /**
     * Set the value of adresse_1
     */
    public function setAdresse1(string $adresse_1): self
    {
        $this->adresse_1 = $adresse_1;

        return $this;
    }

    /**
     * Get the value of adresse_2
     */
    public function getAdresse2(): string
    {
        return $this->adresse_2;
    }

    /**
     * Set the value of adresse_2
     */
    public function setAdresse2(string $adresse_2): self
    {
        $this->adresse_2 = $adresse_2;

        return $this;
    }

    /**
     * Get the value of adresse_3
     */
    public function getAdresse3(): string
    {
        return $this->adresse_3;
    }

    /**
     * Set the value of adresse_3
     */
    public function setAdresse3(string $adresse_3): self
    {
        $this->adresse_3 = $adresse_3;

        return $this;
    }

    /**
     * Get the value of adresse_4
     */
    public function getAdresse4(): string
    {
        return $this->adresse_4;
    }

    /**
     * Set the value of adresse_4
     */
    public function setAdresse4(string $adresse_4): self
    {
        $this->adresse_4 = $adresse_4;

        return $this;
    }

    /**
     * Get the value of code_postal
     */
    public function getCodePostal(): string
    {
        return $this->code_postal;
    }

    /**
     * Set the value of code_postal
     */
    public function setCodePostal(string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    /**
     * Get the value of loyer_location_chez_habitant
     */
    public function getLoyerLocationChezHabitant(): float
    {
        return $this->loyer_location_chez_habitant;
    }

    /**
     * Set the value of loyer_location_chez_habitant
     */
    public function setLoyerLocationChezHabitant(float $loyer_location_chez_habitant): self
    {
        $this->loyer_location_chez_habitant = $loyer_location_chez_habitant;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of surface_logement
     */
    public function getSurfaceLogement(): float
    {
        return $this->surface_logement;
    }

    /**
     * Set the value of surface_logement
     */
    public function setSurfaceLogement(float $surface_logement): self
    {
        $this->surface_logement = $surface_logement;

        return $this;
    }

    /**
     * Get the value of surface_chambres
     */
    public function getSurfaceChambres(): string
    {
        return $this->surface_chambres;
    }

    /**
     * Set the value of surface_chambres
     */
    public function setSurfaceChambres(string $surface_chambres): self
    {
        $this->surface_chambres = $surface_chambres;

        return $this;
    }

    /**
     * Get the value of nombre_chambre
     */
    public function getNombreChambre(): int
    {
        return $this->nombre_chambre;
    }

    /**
     * Set the value of nombre_chambre
     */
    public function setNombreChambre(int $nombre_chambre): self
    {
        $this->nombre_chambre = $nombre_chambre;

        return $this;
    }

    /**
     * Get the value of date_expiration
     */
    public function getDateExpiration(): float
    {
        return $this->date_expiration;
    }

    /**
     * Set the value of date_expiration
     */
    public function setDateExpiration(float $date_expiration): self
    {
        $this->date_expiration = $date_expiration;

        return $this;
    }

    /**
     * Get the value of date_publication
     */
    public function getDatePublication(): float
    {
        return $this->date_publication;
    }

    /**
     * Set the value of date_publication
     */
    public function setDatePublication(float $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    /**
     * Get the value of date_modification
     */
    public function getDateModification(): float
    {
        return $this->date_modification;
    }

    /**
     * Set the value of date_modification
     */
    public function setDateModification(float $date_modification): self
    {
        $this->date_modification = $date_modification;

        return $this;
    }

    /**
     * Get the value of carte_coordonnee_GPS
     */
    public function getCarteCoordonneeGPS(): string
    {
        return $this->carte_coordonnee_GPS;
    }

    /**
     * Set the value of carte_coordonnee_GPS
     */
    public function setCarteCoordonneeGPS(string $carte_coordonnee_GPS): self
    {
        $this->carte_coordonnee_GPS = $carte_coordonnee_GPS;

        return $this;
    }

    /**
     * Get the value of date_cloture
     */
    public function getDateCloture(): float
    {
        return $this->date_cloture;
    }

    /**
     * Set the value of date_cloture
     */
    public function setDateCloture(float $date_cloture): self
    {
        $this->date_cloture = $date_cloture;

        return $this;
    }

    /**
     * Get the value of loyer_colocation
     */
    public function getLoyerColocation(): float
    {
        return $this->loyer_colocation;
    }

    /**
     * Set the value of loyer_colocation
     */
    public function setLoyerColocation(float $loyer_colocation): self
    {
        $this->loyer_colocation = $loyer_colocation;

        return $this;
    }
}

class AnnonceDAO extends \colocation\DAO {
public function __construct(){
    parent:: __construct('annonce', 'id_annonce');
}

protected 



}



           
    

?>