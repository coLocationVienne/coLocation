<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/app/Core/Autoloader.php';

use App\Controllers\HomeController;
use App\Controllers\AnnonceController;
use App\Controllers\AuthController;
use App\Controllers\MessageController;
use App\Controllers\VisitController;

// Get the URL from the query string (provided by .htaccess) or fallback to root
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

// 1. Handle legacy .php files in the root or pages directory
if (preg_match('/\.php$/', $url)) {
    $filePath = __DIR__ . '/' . $url;
    if (file_exists($filePath)) {
        require_once $filePath;
        exit;
    }
}

// 2. Handle MVC routes
if ($url === '' || $url === 'home') {
    $controller = new HomeController();
    $controller->index();
} elseif ($url === 'auth/login') {
    $controller = new AuthController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->login();
    } else {
        $controller->showLogin();
    }
} elseif ($url === 'auth/logout') {
    $controller = new AuthController();
    $controller->logout();
} elseif ($url === 'auth/update-password') {
    $controller = new AuthController();
    $controller->updatePassword();
} elseif ($url === 'auth/update-profile') {
    $controller = new AuthController();
    $controller->updateProfile();
} elseif ($url === 'auth/update-photo') {
    $controller = new AuthController();
    $controller->updatePhoto();
} elseif ($url === 'admin/get-users') {
    $controller = new AuthController();
    $controller->adminGetUsers();
} elseif ($url === 'admin/update-user') {
    $controller = new AuthController();
    $controller->adminUpdateUser();
} elseif ($url === 'admin/delete-user') {
    $controller = new AuthController();
    $controller->adminDeleteUser();
} elseif ($url === 'auth/register') {
    $controller = new AuthController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->register();
    } else {
        $controller->showRegister();
    }
} elseif ($url === 'annonce/json') {
    $controller = new AnnonceController();
    $controller->getAnnoncesJson();
} elseif (strpos($url, 'annonce/show') === 0) {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $controller = new AnnonceController();
    $controller->show($id);
} elseif ($url === 'message/send') {
    $controller = new MessageController();
    $controller->send();
} elseif ($url === 'comment/add') {
    $controller = new AnnonceController();
    $controller->addComment();
} elseif (strpos($url, 'annonce/edit') === 0) {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $controller = new AnnonceController();
    $controller->edit($id);
} elseif ($url === 'annonce/create' || $url === 'create') {
    $controller = new AnnonceController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->store();
    } else {
        $controller->create();
    }
} elseif ($url === 'annonce/update') {
    $controller = new AnnonceController();
    $controller->update();
} elseif ($url === 'annonce/delete') {
    $controller = new AnnonceController();
    $controller->delete();
} elseif ($url === 'annonce/upload-photo') {
    $controller = new AnnonceController();
    $controller->uploadPhoto();
} elseif ($url === 'annonce/delete-photo') {
    $controller = new AnnonceController();
    $controller->deletePhoto();
} elseif (file_exists(__DIR__ . '/pages/' . $url . '.php')) {
    require_once __DIR__ . '/pages/' . $url . '.php';
    exit;
} elseif (file_exists(__DIR__ . '/pages/' . $url)) {
    require_once __DIR__ . '/pages/' . $url;
    exit;
    } elseif ($url === 'visit/dashboard') {
        $controller = new VisitController();
        $controller->dashboard();
    } elseif ($url === 'visit/slot/add') {
        $controller = new VisitController();
        $controller->addSlot();
    } elseif ($url === 'visit/request') {
        $controller = new VisitController();
        $controller->requestVisit();
    } elseif ($url === 'visit/respond') {
        $controller = new VisitController();
        $controller->respondToVisit();
    } elseif ($url === 'visit/cancel') {
        $controller = new VisitController();
        $controller->cancelVisit();
    } else {
        header("HTTP/1.0 404 Not Found");
        $controller = new HomeController();
        $controller->notFound($url);
    }
