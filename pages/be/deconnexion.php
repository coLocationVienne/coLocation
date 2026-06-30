<?php 
session_start();
try {
    session_destroy();
} catch (Exception $e) {
    echo "il y'a des erreurs ! ". $e->getMessage();
}

header("location : ../../index.php?info=deconnexion");
exit();


?>