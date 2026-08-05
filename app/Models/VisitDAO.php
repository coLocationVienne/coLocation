<?php

namespace App\Models;

class VisitDAO extends DAO {
    public function __construct() {
        parent::__construct('visite', 'id_visite');
    }

    protected function hydrate(array $row): Visit {
        return new Visit($row);
    }

    protected function dehydrate(object $visit): array {
        /** @var Visit $visit */
        return [
            'id_visite' => $visit->getId(),
            'id_creneauVisite' => $visit->getIdCreneau(),
            'id_utilisateur' => $visit->getIdUtilisateur(),
            'statut' => $visit->getStatut(),
            'date_demande' => $visit->getDateDemande(),
            'message' => $visit->getMessage(),
            'date_annulation' => $visit->getDateAnnulation()
        ];
    }

    public function getByTenant(int $userId): array {
        $query = "SELECT v.*, c.date_visite, c.heure_debut, c.heure_fin, a.titre as annonce_titre, a.id_annonce
                  FROM visite v
                  JOIN creneau_visite c ON v.id_creneauVisite = c.id_creneauVisite
                  JOIN annonce a ON c.id_annonce = a.id_annonce
                  WHERE v.id_utilisateur = :userId
                  ORDER BY c.date_visite DESC, c.heure_debut DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll();
    }

    public function getByOwner(int $ownerId): array {
        $query = "SELECT v.*, c.date_visite, c.heure_debut, c.heure_fin, a.titre as annonce_titre, a.id_annonce, u.prenom as tenant_prenom, u.nom as tenant_nom
                  FROM visite v
                  JOIN creneau_visite c ON v.id_creneauVisite = c.id_creneauVisite
                  JOIN annonce a ON c.id_annonce = a.id_annonce
                  JOIN annonce_utilisateur au ON a.id_annonce = au.id_annonce
                  JOIN utilisateur u ON v.id_utilisateur = u.id_utilisateur
                  WHERE au.id_utilisateur = :ownerId
                  ORDER BY c.date_visite DESC, c.heure_debut DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['ownerId' => $ownerId]);
        return $stmt->fetchAll();
    }

    public function getFullDetails(int $visitId): ?array {
        $query = "SELECT v.*, c.date_visite, c.heure_debut, c.heure_fin, a.titre as annonce_titre, a.id_annonce, a.ville,
                         u.prenom as tenant_prenom, u.nom as tenant_nom, u.email as tenant_email,
                         owner.prenom as owner_prenom, owner.nom as owner_nom, owner.email as owner_email,
                         owner.id_utilisateur as owner_id
                  FROM visite v
                  JOIN creneau_visite c ON v.id_creneauVisite = c.id_creneauVisite
                  JOIN annonce a ON c.id_annonce = a.id_annonce
                  JOIN annonce_utilisateur au ON a.id_annonce = au.id_annonce
                  JOIN utilisateur u ON v.id_utilisateur = u.id_utilisateur
                  JOIN utilisateur owner ON au.id_utilisateur = owner.id_utilisateur
                  WHERE v.id_visite = :visitId";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['visitId' => $visitId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function getPendingReminders(): array {
        $query = "SELECT v.*, c.date_visite, c.heure_debut, c.heure_fin, a.titre as annonce_titre, a.id_annonce, a.ville,
                         u.prenom as tenant_prenom, u.nom as tenant_nom, u.email as tenant_email,
                         owner.prenom as owner_prenom, owner.nom as owner_nom, owner.email as owner_email
                  FROM visite v
                  JOIN creneau_visite c ON v.id_creneauVisite = c.id_creneauVisite
                  JOIN annonce a ON c.id_annonce = a.id_annonce
                  JOIN annonce_utilisateur au ON a.id_annonce = au.id_annonce
                  JOIN utilisateur u ON v.id_utilisateur = u.id_utilisateur
                  JOIN utilisateur owner ON au.id_utilisateur = owner.id_utilisateur
                  WHERE v.statut = 'confirme' 
                  AND v.rappel_envoye = 0
                  AND c.date_visite = DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function markReminderSent(int $visitId): bool {
        $query = "UPDATE visite SET rappel_envoye = 1 WHERE id_visite = :visitId";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['visitId' => $visitId]);
    }
}
