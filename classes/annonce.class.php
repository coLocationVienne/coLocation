<?php

namespace colocation;

class Annonce
{
    private ?int $id_annonce;
    private string $titre;
    private string $adresse_1;
    private string $adresse_2;
    private string $adresse_3;
    private string $adresse_4;
    private string $ville;
    private string $code_postal;
    private float $loyer_location_chez_habitant;
    private string $description;
    private float $surface_logement;
    private float $surface_chambres;
    private int $nombre_chambre;
    private string $date_expiration;
    private string $date_publication;
    private string $date_modification;
    private string $carte_coordonnee_GPS;
    private string $date_cloture;
    private float $loyer_colocation;

    public function __construct(array $data)
    {
        $this->id_annonce = $data['id_annonce'] ?? null;
        $this->titre = $data['titre'] ?? '';
        $this->adresse_1 = $data['adresse_1'] ?? '';
        $this->adresse_2 = $data['adresse_2'] ?? '';
        $this->adresse_3 = $data['adresse_3'] ?? '';
        $this->adresse_4 = $data['adresse_4'] ?? '';
        $this->ville = $data['ville'] ?? '';
        $this->code_postal = (string)($data['code_postal'] ?? '');
        $this->loyer_location_chez_habitant = (float)($data['loyer_location_chez_habitant'] ?? 0);
        $this->description = $data['description'] ?? '';
        $this->surface_logement = (float)($data['surface_logement'] ?? 0);
        $this->surface_chambres = (float)($data['surface_chambres'] ?? 0);
        $this->nombre_chambre = (int)($data['nombre_chambre'] ?? 0);
        $this->date_expiration = $data['date_expiration'] ?? '';
        $this->date_publication = $data['date_publication'] ?? date('Y-m-d');
        $this->date_modification = $data['date_modification'] ?? date('Y-m-d');
        $this->carte_coordonnee_GPS = $data['carte_coordonnee_GPS'] ?? '';
        $this->date_cloture = $data['date_cloture'] ?? '';
        $this->loyer_colocation = (float)($data['loyer_colocation'] ?? 0);
    }

    public function getIdAnnonce(): ?int { return $this->id_annonce; }
    public function getTitre(): string { return $this->titre; }
    public function getAdresse1(): string { return $this->adresse_1; }
    public function getAdresse2(): string { return $this->adresse_2; }
    public function getAdresse3(): string { return $this->adresse_3; }
    public function getAdresse4(): string { return $this->adresse_4; }
    public function getVille(): string { return $this->ville; }
    public function getCodePostal(): string { return $this->code_postal; }
    public function getLoyerLocationChezHabitant(): float { return $this->loyer_location_chez_habitant; }
    public function getDescription(): string { return $this->description; }
    public function getSurfaceLogement(): float { return $this->surface_logement; }
    public function getSurfaceChambres(): float { return $this->surface_chambres; }
    public function getNombreChambre(): int { return $this->nombre_chambre; }
    public function getDateExpiration(): string { return $this->date_expiration; }
    public function getDatePublication(): string { return $this->date_publication; }
    public function getDateModification(): string { return $this->date_modification; }
    public function getCarteCoordonneeGPS(): string { return $this->carte_coordonnee_GPS; }
    public function getDateCloture(): string { return $this->date_cloture; }
    public function getLoyerColocation(): float { return $this->loyer_colocation; }
    public function setIdAnnonce(?int $id_annonce): void { $this->id_annonce = $id_annonce; }
}

class AnnonceDAO extends \colocation\DAO
{
    public function __construct()
    {
        parent::__construct('annonce', 'id_annonce');
    }

    protected function hydrate(array $row): Annonce
    {
        return new Annonce($row);
    }

    protected function dehydrate(object $annonce): array
    {
        return [
            'id_annonce' => $annonce->getIdAnnonce(),
            'titre' => $annonce->getTitre(),
            'adresse_1' => $annonce->getAdresse1(),
            'adresse_2' => $annonce->getAdresse2(),
            'adresse_3' => $annonce->getAdresse3(),
            'adresse_4' => $annonce->getAdresse4(),
            'ville' => $annonce->getVille(),
            'code_postal' => $annonce->getCodePostal(),
            'loyer_location_chez_habitant' => $annonce->getLoyerLocationChezHabitant(),
            'description' => $annonce->getDescription(),
            'surface_logement' => $annonce->getSurfaceLogement(),
            'surface_chambres' => $annonce->getSurfaceChambres(),
            'nombre_chambre' => $annonce->getNombreChambre(),
            'date_expiration' => $annonce->getDateExpiration(),
            'date_publication' => $annonce->getDatePublication(),
            'date_modification' => $annonce->getDateModification(),
            'carte_coordonnee_GPS' => $annonce->getCarteCoordonneeGPS(),
            'date_cloture' => $annonce->getDateCloture(),
            'loyer_colocation' => $annonce->getLoyerColocation()
        ];
    }

    public function ajouterAnnonce(Annonce $annonce, int $idUtilisateur, array $modesVie = [], array $regimes = []): bool
    {
        $this->db->beginTransaction();

        try {
            $this->save($annonce);

            $idAnnonce = (int)$this->db->lastInsertId();

            $stmt = $this->db->prepare("
                INSERT INTO annonce_utilisateur (id_utilisateur, id_annonce)
                VALUES (:id_utilisateur, :id_annonce)
            ");

            $stmt->execute([
                ':id_utilisateur' => $idUtilisateur,
                ':id_annonce' => $idAnnonce
            ]);

            $this->ajouterModesVie($idAnnonce, $modesVie);
            $this->ajouterRegimes($idAnnonce, $regimes);

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function modifierAnnonce(Annonce $annonce, int $idUtilisateur, array $modesVie = [], array $regimes = []): bool
    {
        $idAnnonce = $annonce->getIdAnnonce();

        if (!$idAnnonce || !$this->appartientAUtilisateur($idAnnonce, $idUtilisateur)) {
            return false;
        }

        $this->db->beginTransaction();

        try {
            $this->update($annonce);

            $stmt = $this->db->prepare("DELETE FROM annonce_mode_vie WHERE id_annonce = :id_annonce");
            $stmt->execute([':id_annonce' => $idAnnonce]);

            $stmt = $this->db->prepare("DELETE FROM annonce_regime_alimentaire WHERE id_annonce = :id_annonce");
            $stmt->execute([':id_annonce' => $idAnnonce]);

            $this->ajouterModesVie($idAnnonce, $modesVie);
            $this->ajouterRegimes($idAnnonce, $regimes);

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function supprimerAnnonce(int $idAnnonce, int $idUtilisateur): bool
    {
        if (!$this->appartientAUtilisateur($idAnnonce, $idUtilisateur)) {
            return false;
        }

        $this->db->beginTransaction();

        try {
            $tables = [
                'annonce_mode_vie',
                'annonce_regime_alimentaire',
                'annonce_photo',
                'annonce_utilisateur'
            ];

            foreach ($tables as $table) {
                $stmt = $this->db->prepare("DELETE FROM $table WHERE id_annonce = :id_annonce");
                $stmt->execute([':id_annonce' => $idAnnonce]);
            }

            $this->delete($idAnnonce);

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function appartientAUtilisateur(int $idAnnonce, int $idUtilisateur): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM annonce_utilisateur
            WHERE id_annonce = :id_annonce
            AND id_utilisateur = :id_utilisateur
        ");

        $stmt->execute([
            ':id_annonce' => $idAnnonce,
            ':id_utilisateur' => $idUtilisateur
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }

    private function ajouterModesVie(int $idAnnonce, array $modesVie): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO annonce_mode_vie (id_annonce, id_mode_vie)
            VALUES (:id_annonce, :id_mode_vie)
        ");

        foreach (array_unique($modesVie) as $idModeVie) {
            $idModeVie = (int)$idModeVie;

            if ($idModeVie > 0) {
                $stmt->execute([
                    ':id_annonce' => $idAnnonce,
                    ':id_mode_vie' => $idModeVie
                ]);
            }
        }
    }

    private function ajouterRegimes(int $idAnnonce, array $regimes): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO annonce_regime_alimentaire (id_annonce, id_regime_alimentaire)
            VALUES (:id_annonce, :id_regime_alimentaire)
        ");

        foreach (array_unique($regimes) as $idRegime) {
            $idRegime = (int)$idRegime;

            if ($idRegime > 0) {
                $stmt->execute([
                    ':id_annonce' => $idAnnonce,
                    ':id_regime_alimentaire' => $idRegime
                ]);
            }
        }
    }
    
}