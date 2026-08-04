<?php

namespace App\Controllers;

use App\Core\Config;
use App\Core\Controller;
use App\Core\Token;
use App\Core\MailService;
use App\Models\VisitSlot;
use App\Models\Visit;

class VisitController extends Controller {
    
    public function dashboard() {
        global $visitDAO;
        
        if (empty($_SESSION['isLoggedin'])) {
            header("Location: " . Config::url('auth/login'));
            exit();
        }

        // Lazy Reminders: Send reminders for visits happening tomorrow
        $pendingReminders = $visitDAO->getPendingReminders();
        foreach ($pendingReminders as $rem) {
            if (MailService::sendVisitReminder($rem)) {
                $visitDAO->markReminderSent((int)$rem['id_visite']);
            }
        }

        $userId = (int)$_SESSION['user_id'];
        $visitsAsTenant = $visitDAO->getByTenant($userId);
        $visitsAsOwner = $visitDAO->getByOwner($userId);

        $this->render('visit/dashboard', [
            'visitsAsTenant' => $visitsAsTenant,
            'visitsAsOwner' => $visitsAsOwner
        ]);
    }

    public function addSlot() {
        global $visitSlotDAO, $annonceDAO;
        
        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . Config::url('home'));
            exit();
        }

        if (!Token::check($_POST['token'] ?? '')) {
            header("Location: " . Config::url('pages/page_annonce.php?error=invalid_token'));
            exit();
        }

        $annonceId = (int)$_POST['id_annonce'];
        
        // Check ownership
        if (!$annonceDAO->appartientAUtilisateur($annonceId, (int)$_SESSION['user_id'])) {
            header("Location: " . Config::url('pages/page_annonce.php?error=unauthorized'));
            exit();
        }

        $slotData = [
            'date_visite' => $_POST['date_visite'],
            'heure_debut' => $_POST['heure_debut'],
            'heure_fin' => $_POST['heure_fin'],
            'nb_personne_max' => (int)$_POST['nb_personne_max'],
            'id_annonce' => $annonceId
        ];

        $slot = new VisitSlot($slotData);
        if ($visitSlotDAO->save($slot)) {
            header("Location: " . Config::url('pages/page_annonce.php?status=slot_added'));
        } else {
            header("Location: " . Config::url('pages/page_annonce.php?error=add_slot_failed'));
        }
        exit();
    }

    public function requestVisit() {
        global $visitDAO, $visitSlotDAO;

        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . Config::url('home'));
            exit();
        }

        $slotId = (int)$_POST['id_creneauVisite'];
        $message = htmlspecialchars($_POST['message'] ?? '');
        $userId = (int)$_SESSION['user_id'];

        $visit = new Visit([
            'id_creneauVisite' => $slotId,
            'id_utilisateur' => $userId,
            'message' => $message,
            'statut' => 'en_attente'
        ]);

        if ($visitDAO->save($visit)) {
            header("Location: " . Config::url('visit/dashboard?status=request_sent'));
        } else {
            header("Location: " . Config::url('home?error=request_failed'));
        }
        exit();
    }

    public function respondToVisit() {
        global $visitDAO, $annonceDAO;

        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . Config::url('home'));
            exit();
        }

        $visitId = (int)$_POST['id_visite'];
        $action = $_POST['action']; // 'confirme' or 'refuse'
        
        $visitDetails = $visitDAO->getFullDetails($visitId);
        if (!$visitDetails || $visitDetails['owner_id'] !== (int)$_SESSION['user_id']) {
            header("Location: " . Config::url('visit/dashboard?error=unauthorized'));
            exit();
        }

        $visit = $visitDAO->getById($visitId);
        $visit->setStatut($action);

        if ($visitDAO->update($visit)) {
            if ($action === 'confirme') {
                // Send confirmation email
                MailService::sendVisitConfirmation($visitDetails);
            }
            header("Location: " . Config::url('visit/dashboard?status=visit_' . $action));
        } else {
            header("Location: " . Config::url('visit/dashboard?error=update_failed'));
        }
        exit();
    }

    public function cancelVisit() {
        global $visitDAO;

        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . Config::url('home'));
            exit();
        }

        $visitId = (int)$_POST['id_visite'];
        $visitDetails = $visitDAO->getFullDetails($visitId);
        $userId = (int)$_SESSION['user_id'];

        if (!$visitDetails || ($visitDetails['id_utilisateur'] !== $userId && $visitDetails['owner_id'] !== $userId)) {
            header("Location: " . Config::url('visit/dashboard?error=unauthorized'));
            exit();
        }

        // Check 24h limit
        $visitDateTime = strtotime($visitDetails['date_visite'] . ' ' . $visitDetails['heure_debut']);
        if ($visitDateTime - time() < 24 * 3600) {
            header("Location: " . Config::url('visit/dashboard?error=cancellation_too_late'));
            exit();
        }

        $visit = $visitDAO->getById($visitId);
        $visit->setStatut('annule');
        $visit->setDateAnnulation(date('Y-m-d H:i:s'));

        if ($visitDAO->update($visit)) {
            header("Location: " . Config::url('visit/dashboard?status=visit_canceled'));
        } else {
            header("Location: " . Config::url('visit/dashboard?error=cancel_failed'));
        }
        exit();
    }
}
