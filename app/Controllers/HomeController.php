<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        global $annonceDAO;
        $annoncesFromDb = $annonceDAO->getAllWithPhotos();
        
        $this->render('home/index', [
            'annoncesFromDb' => $annoncesFromDb
        ]);
    }

    public function notFound($url) {
        $this->render('errors/404', [
            'url' => $url
        ]);
    }
}
