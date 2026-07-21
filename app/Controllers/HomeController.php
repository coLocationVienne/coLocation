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
}
