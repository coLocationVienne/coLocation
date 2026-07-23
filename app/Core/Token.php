<?php 

namespace App\Core;

class Token{

    public function CheckExistToken() : bool {

       print_r($_POST['token']);
                     exit;
        if(!isset($_SESSION['token']) || !isset($_POST['token'])){

            header("Location: /coLocation/auth/login?error=login_required");
             exit();
        }// si le token n'existe pas dans la session ou n'existe pas dans le formulaire
    }

    public function matchingToken(){
      if($_SESSION['token'] !== $_POST['token']){
        header("Location: /coLocation/auth/login?error=login_required");
        exit();
      }
    }
}


 





?>