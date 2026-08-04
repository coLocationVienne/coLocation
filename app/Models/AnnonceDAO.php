<?php

namespace App\Models;

class AnnonceDAO extends \App\Models\DAO {
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
            'adresse_1' => $annonce->getAdresse1(),
            'adresse_2' => $annonce->getAdresse2(),
            'adresse_3' => $annonce->getAdresse3(),
            'adresse_4' => $annonce->getAdresse4(),
            'ville' => $annonce->getVille(),
            'code_postal' => $annonce->getCodePostal(),
            'loyer_location_chez_habitant' => $annonce->getLoyerHabitant(),
            'description' => $annonce->getDescription(),
            'surface_logement' => $annonce->getSurfaceLogement(),
            'surface_chambres' => $annonce->getSurfaceChambres(),
            'nombre_chambre' => $annonce->getNombreChambre(),
            'date_expiration' => $annonce->getDateExpiration(),
            'date_publication' => $annonce->getDatePublication(),
            'date_modification' => $annonce->getDateModification(),
            'carte_coordonnee_GPS' => $annonce->getGps(),
            'date_cloture' => $annonce->getDateCloture(),
            'loyer_colocation' => $annonce->getLoyerColoc()
        ];
    }

    public function getAllWithPhotos(): array {
        $query = "SELECT a.*, p.url as photo_url, au.id_utilisateur as owner_id
                  FROM annonce a 
                  LEFT JOIN (
                      SELECT id_annonce, MIN(id_photo) as first_photo_id
                      FROM annonce_photo
                      GROUP BY id_annonce
                  ) ap_first ON a.id_annonce = ap_first.id_annonce
                  LEFT JOIN photo p ON ap_first.first_photo_id = p.id_photo 
                  LEFT JOIN annonce_utilisateur au ON a.id_annonce = au.id_annonce
                  ORDER BY a.date_publication DESC";
        $stmt = $this->db->query($query);
        $results = $stmt->fetchAll();
        
        $annonces = [];
        foreach ($results as $row) {
            $annonces[] = $this->hydrate($row);
        }
        return $annonces;
    }

    public function getFullById(int $id): ?Annonce {
        $query = "SELECT a.*, p.url as photo_url, au.id_utilisateur as owner_id
                  FROM annonce a 
                  LEFT JOIN (
                      SELECT id_annonce, MIN(id_photo) as first_photo_id
                      FROM annonce_photo
                      WHERE id_annonce = :id
                      GROUP BY id_annonce
                  ) ap_first ON a.id_annonce = ap_first.id_annonce
                  LEFT JOIN photo p ON ap_first.first_photo_id = p.id_photo 
                  LEFT JOIN annonce_utilisateur au ON a.id_annonce = au.id_annonce
                  WHERE a.id_annonce = :id
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function getAllAnouncesByUserId(int $userId): array {
        $query = "SELECT a.*, p.url as photo_url, au.id_utilisateur as owner_id
                  FROM annonce a 
                  INNER JOIN annonce_utilisateur au ON a.id_annonce = au.id_annonce
                  LEFT JOIN (
                      SELECT id_annonce, MIN(id_photo) as first_photo_id
                      FROM annonce_photo
                      GROUP BY id_annonce
                  ) ap_first ON a.id_annonce = ap_first.id_annonce
                  LEFT JOIN photo p ON ap_first.first_photo_id = p.id_photo 
                  WHERE au.id_utilisateur = :user_id
                  ORDER BY a.date_publication DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        $results = $stmt->fetchAll();
        
        $annonces = [];
        foreach ($results as $row) {
            $annonces[] = $this->hydrate($row);
        }
        return $annonces;
    }

    public function getPhotos(int $idAnnonce): array {
        $query = "SELECT p.* FROM photo p 
                  JOIN annonce_photo ap ON p.id_photo = ap.id_photo 
                  WHERE ap.id_annonce = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $idAnnonce]);
        return $stmt->fetchAll();
    }

    public function addPhoto(int $idAnnonce, string $url): bool {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("INSERT INTO photo (url) VALUES (?)");
            $stmt->execute([$url]);
            $idPhoto = $this->db->lastInsertId();
            
            $stmtLink = $this->db->prepare("INSERT INTO annonce_photo (id_annonce, id_photo) VALUES (?, ?)");
            $stmtLink->execute([$idAnnonce, $idPhoto]);
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function deletePhoto(int $idPhoto): bool {
        try {
            $this->db->beginTransaction();
            $this->db->prepare("DELETE FROM annonce_photo WHERE id_photo = ?")->execute([$idPhoto]);
            $this->db->prepare("DELETE FROM photo WHERE id_photo = ?")->execute([$idPhoto]);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function ajouterAnnonce(Annonce $annonce, int $userId, array $modesVie, array $regimes): int {
        try {
            $this->db->beginTransaction();
            $this->save($annonce);
            $idAnnonce = $this->db->lastInsertId();
            $stmtUser = $this->db->prepare("INSERT INTO annonce_utilisateur (id_annonce, id_utilisateur) VALUES (?, ?)");
            $stmtUser->execute([$idAnnonce, $userId]);
            
            if (!empty($modesVie)) {
                $this->ajouterModesVie($idAnnonce, $modesVie);
            }

            if (!empty($regimes)) {
                $this->ajouterRegimes($idAnnonce, $regimes);
            }

            $this->db->commit();
            return (int)$idAnnonce;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Error in ajouterAnnonce: " . $e->getMessage());
            return 0;
        }
    }

    public function modifierAnnonce(Annonce $annonce, int $userId, array $modesVie, array $regimes): bool {
        try {
            $this->db->beginTransaction();
            if (!$this->appartientAUtilisateur((int)$annonce->getId(), $userId)) {
                $this->db->rollBack();
                return false;
            }
            $this->update($annonce);
            $this->db->prepare("DELETE FROM annonce_mode_vie WHERE id_annonce = ?")->execute([$annonce->getId()]);
            if (!empty($modesVie)) {
                $this->ajouterModesVie($annonce->getId(), $modesVie);
            }
            $this->db->prepare("DELETE FROM annonce_regime_alimentaire WHERE id_annonce = ?")->execute([$annonce->getId()]);
            if (!empty($regimes)) {
                $this->ajouterRegimes($annonce->getId(), $regimes);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Error in modifierAnnonce: " . $e->getMessage());
            return false;
        }
    }

    public function supprimerAnnonce(int $idAnnonce, int $idUtilisateur): bool {
        if (!$this->appartientAUtilisateur($idAnnonce, $idUtilisateur)) {
            return false;
        }
        $this->db->beginTransaction();
        try {
            $tables = ['annonce_mode_vie', 'annonce_regime_alimentaire', 'annonce_photo', 'annonce_utilisateur', 'annonce_avis', 'envoi_message'];
            foreach ($tables as $table) {
                $stmt = $this->db->prepare("DELETE FROM $table WHERE id_annonce = :id_annonce");
                $stmt->execute([':id_annonce' => $idAnnonce]);
            }
            $this->delete($idAnnonce);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Error in supprimerAnnonce: " . $e->getMessage());
            return false;
        }
    }

    public function appartientAUtilisateur(int $idAnnonce, int $idUtilisateur): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM annonce_utilisateur WHERE id_annonce = :id_annonce AND id_utilisateur = :id_utilisateur");
        $stmt->execute([':id_annonce' => $idAnnonce, ':id_utilisateur' => $idUtilisateur]);
        return (int)$stmt->fetchColumn() > 0;
    }

    private function ajouterModesVie(int $idAnnonce, array $modesVie): void {
        $stmt = $this->db->prepare("INSERT INTO annonce_mode_vie (id_annonce, id_mode_vie) VALUES (:id_annonce, :id_mode_vie)");
        foreach (array_unique($modesVie) as $idModeVie) {
            $idModeVie = (int)$idModeVie;
            if ($idModeVie > 0) {
                $stmt->execute([':id_annonce' => $idAnnonce, ':id_mode_vie' => $idModeVie]);
            }
        }
    }

    public function avoirModeVie(int $idAnnonce) {
        $modeStmt = $this->db->prepare("SELECT id_mode_vie FROM annonce_mode_vie WHERE id_annonce = :id_annonce");
        $modeStmt->execute([':id_annonce' => $idAnnonce]);
        return $modeStmt->fetchAll();
    }

    private function ajouterRegimes(int $idAnnonce, array $regimes): void {
        $stmt = $this->db->prepare("INSERT INTO annonce_regime_alimentaire (id_annonce, id_regime_alimentaire) VALUES (:id_annonce, :id_regime)");
        foreach (array_unique($regimes) as $idRegime) {
            $idRegime = (int)$idRegime;
            if ($idRegime > 0) {
                $stmt->execute([':id_annonce' => $idAnnonce, ':id_regime' => $idRegime]);
            }
        }
    }

    public function avoirRegime(int $idAnnonce) {
        $regimeStmt = $this->db->prepare("SELECT id_regime_alimentaire FROM annonce_regime_alimentaire WHERE id_annonce = :id_annonce");
        $regimeStmt->execute([':id_annonce' => $idAnnonce]);
        return $regimeStmt->fetchAll();
    }
}
