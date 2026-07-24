<?php

namespace App\Controllers;

use App\Core\Config;
use App\Core\Controller;

class AuthController extends Controller {
    public function showLogin() {
        if (!empty($_SESSION['isLoggedin'])) {
            header("Location: /coLocation/home");
            exit();
        }
        
        $this->render('auth/login', [
            'loginError' => $_GET['error'] ?? '',
            'registered' => $_GET['registered'] ?? '',
            'redirect' => $_GET['redirect'] ?? ''
        ]);
    }

    public function login() {
        global $userDAO;
        
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $redirect = !empty($_POST['redirect']) ? $_POST['redirect'] : "/coLocation/home";

            $users = $userDAO->findBy(['email' => $email]);
            $user = !empty($users) ? $users[0] : null;

            if ($user && $user->verifierMotDePasse($password)) {
                $_SESSION['user_id'] = $user->getIdUtilisateur();
                $_SESSION['user_email'] = $user->getEmail();
                $_SESSION['user_prenom'] = $user->getPrenom();
                $_SESSION['user_nom'] = $user->getNom();
                $_SESSION['isLoggedin'] = true;
                $_SESSION['user_role'] = $user->getIdRole();
               
                header("Location: " . Config::url($redirect));
                exit();
            } else {
                $redirectParam = !empty($_POST['redirect']) ? "&redirect=" . urlencode($_POST['redirect']) : "";
                header("Location: /coLocation/auth/login?error=invalid_credentials" . $redirectParam);
                exit();
            }
        } else {
            header("Location: /coLocation/auth/login?error=missing_fields");
            exit();
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /coLocation/home");
        exit();
    }

    public function updatePassword() {
        global $userDAO;
        
        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /coLocation/auth/login");
            exit();
        }

        $userId = (int)($_POST['user_id'] ?? 0);
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['new_password_confirm'] ?? '';

        if ($userId !== (int)$_SESSION['user_id']) {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=unauthorized");
            exit();
        }

        $user = $userDAO->getById($userId);
        if (!$user || !$user->verifierMotDePasse($currentPassword)) {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=invalid_current_password&passwordModal=1");
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=password_mismatch&passwordModal=1");
            exit();
        }

        if (strlen($newPassword) < 6) {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=password_too_short&passwordModal=1");
            exit();
        }

        $user->setMotDePasse(password_hash($newPassword, PASSWORD_DEFAULT));
        if ($userDAO->update($user)) {
            header("Location: /coLocation/pages/profile_utilisateur.php?status=password_updated");
        } else {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=update_failed");
        }
        exit();
    }

    public function updatePhoto() {
        global $userDAO;
        
        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /coLocation/auth/login");
            exit();
        }

        $userId = (int)$_SESSION['user_id'];
        $user = $userDAO->getById($userId);

        if (!$user) {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=user_not_found");
            exit();
        }

        if (isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1') {
            $user->setPhotoProfil('');
            $userDAO->update($user);
            header("Location: /coLocation/pages/profile_utilisateur.php?status=photo_removed");
            exit();
        }

        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname($_SERVER['SCRIPT_FILENAME']) . '/pages/be/uploads/profile_photos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $extension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
            $targetPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetPath)) {
                $dbPath = 'uploads/profile_photos/' . $filename;
                $user->setPhotoProfil($dbPath);
                if ($userDAO->update($user)) {
                    header("Location: /coLocation/pages/profile_utilisateur.php?status=photo_updated");
                    exit();
                }
            }
        }

        header("Location: /coLocation/pages/profile_utilisateur.php?error=upload_failed");
        exit();
    }

    public function updateProfile() {
        global $userDAO;
        
        if (empty($_SESSION['isLoggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /coLocation/auth/login");
            exit();
        }

        $userId = (int)($_POST['user_id'] ?? 0);
        if ($userId !== (int)$_SESSION['user_id']) {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=unauthorized");
            exit();
        }

        $user = $userDAO->getById($userId);
        if (!$user) {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=user_not_found");
            exit();
        }

        $user->setSituationProfessionnel($_POST['situation'] ?? $user->getSituationProfessionnel());
        $user->setGarant(isset($_POST['garant']) && $_POST['garant'] == '1');
        $user->setSalaireMensuelNet((float)($_POST['salary'] ?? $user->getSalaireMensuelNet()));
        $user->setRevenuFiscal((float)($_POST['revenu_fiscal'] ?? $user->getRevenuFiscal()));
        $user->setDateNaissance($_POST['date_naissance'] ?? $user->getDateNaissance());
        $user->setTypeCompte($_POST['type_compte'] ?? $user->getTypeCompte());

        if ($userDAO->update($user)) {
            header("Location: /coLocation/pages/profile_utilisateur.php?status=profile_updated");
        } else {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=update_failed");
        }
        exit();
    }

    public function adminGetUsers() {
        global $userDAO;
        header('Content-Type: application/json');

        if (empty($_SESSION['isLoggedin']) || (int)$_SESSION['user_role'] !== 1) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit();
        }

        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';

        $result = $userDAO->searchPaginated($search, $role, $page);
        $result['success'] = true;
        echo json_encode($result);
        exit();
    }

    public function adminUpdateUser() {
        global $userDAO;
        
        if (empty($_SESSION['isLoggedin']) || (int)$_SESSION['user_role'] !== 1 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /coLocation/auth/login");
            exit();
        }

        $userId = (int)($_POST['user_id'] ?? 0);
        $user = $userDAO->getById($userId);
        if (!$user) {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=user_not_found");
            exit();
        }

        $user->setIdRole((int)$_POST['role']);
        $user->setSituationProfessionnel($_POST['situation'] ?? $user->getSituationProfessionnel());
        $user->setGarant(isset($_POST['garant']) && $_POST['garant'] == '1');
        $user->setSalaireMensuelNet((float)($_POST['salary'] ?? $user->getSalaireMensuelNet()));
        
        if (!empty($_POST['new_password'])) {
            $user->setMotDePasse(password_hash($_POST['new_password'], PASSWORD_DEFAULT));
        }

        if ($userDAO->update($user)) {
            header("Location: /coLocation/pages/profile_utilisateur.php?status=admin_update_success");
        } else {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=admin_update_failed");
        }
        exit();
    }

    public function adminDeleteUser() {
        global $userDAO;

        if (empty($_SESSION['isLoggedin']) || (int)$_SESSION['user_role'] !== 1 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /coLocation/auth/login");
            exit();
        }

        $userId = (int)($_POST['user_id'] ?? 0);
        if ($userDAO->delete($userId)) {
            header("Location: /coLocation/pages/profile_utilisateur.php?status=admin_delete_success");
        } else {
            header("Location: /coLocation/pages/profile_utilisateur.php?error=admin_delete_failed");
        }
        exit();
    }

    public function showRegister() {
        $this->render('auth/register', [
            'registerError' => $_GET['error'] ?? ''
        ]);
    }

    public function register() {
        global $userDAO;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /coLocation/auth/register");
            exit();
        }
        
        $requiredFields = ['prenom', 'nom', 'email', 'password', 'password_confirm', 'date_naissance', 'situation_professionnel'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                header("Location: /coLocation/auth/register?error=missing_fields");
                exit();
            }
        }
        
        $email = trim(htmlspecialchars($_POST['email']));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: /coLocation/auth/register?error=invalid_email");
            exit();
        }
        
        if ($_POST['password'] !== $_POST['password_confirm']) {
            header("Location: /coLocation/auth/register?error=password_mismatch");
            exit();
        }
        
        $existing = $userDAO->findBy(['email' => $email]);
        if (!empty($existing)) {
            header("Location: /coLocation/auth/register?error=email_exists");
            exit();
        }
        
        $userData = [
            'prenom' => trim(htmlspecialchars($_POST['prenom'])),
            'nom' => trim(htmlspecialchars($_POST['nom'])),
            'email' => $email,
            'mot_de_passe' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'date_naissance' => $_POST['date_naissance'],
            'situation_professionnel' => htmlspecialchars($_POST['situation_professionnel']),
            'garant' => isset($_POST['garant']) ? 1 : 0,
            'salaire_mensuel_net' => $_POST['salaire_mensuel_net'] !== '' ? (float)$_POST['salaire_mensuel_net'] : 0,
            'revenu_fiscal' => htmlspecialchars($_POST['revenu_fiscal'] ?? '0'),
            'id_role' => 3
        ];
        
        $newUser = new \App\Models\User($userData);
        
        if ($userDAO->save($newUser)) {
            header("Location: /coLocation/auth/login?registered=1");
            exit();
        } else {
            header("Location: /coLocation/auth/register?error=server_error");
            exit();
        }
    }
}
