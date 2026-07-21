<?php

namespace App\Core;

class Controller {
    protected function render($view, $data = []) {
        // Make global DAOs available to views
        global $userDAO, $messageDAO, $annonceDAO, $commentDAO;
        
        extract($data);
        $viewFile = __DIR__ . "/../Views/" . $view . ".php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View $view not found at: " . $viewFile);
        }
    }
}
