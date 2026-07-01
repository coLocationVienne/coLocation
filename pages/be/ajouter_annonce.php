<?php 

if($_SERVER['$_REQUEST_METHOD'] !== 'POST'){
    header('Location: ../creer_annonce.php');
    exit();
}

$myFields= ["titre","ville","loyer","surface","disponibilite","description","contact","message"]
foreach ($myFields as $fields) {
    if(empty($_POST[$fields])){
        header("location: ../creer_annonce.php?error=missing_fields");
        exit();
    }
}


$titre=trim(htmlspecialchars($_POST["titre"]));
$adresse_1=trim(htmlspecialchars($_POST["rue_nom"]));
$adresse_2=trim(htmlspecialchars($_POST["appartement"]));
$adresse_3=trim(htmlspecialchars($_POST["batiment"]));
$adresse_4=trim(htmlspecialchars($_POST["infocomplementaire"]));
$ville=trim(htmlspecialchars($_POST["ville"]));
$code_postal=$_POST["codePostal"];
$loyer= $_POST["loyer"] !== '' ? (float) $_POST["loyer"] : 0;
$description=trim(htmlspecialchars($_POST["descriptions"]));
$surface_logement=$_POST["surface_logement"];
$surface_chambres=$_POST["surface_chambres"];
$disponibilité=$_POST["disponibilite"];
$contact=trim(htmlspecialchars($_POST["contact"]));
$date_expiration=trim(htmlspecialchars($_POST["date_expiration"]));
$date_modification=$_POST["date_modification"]; --on reprend ici
$carte_coordonnee_GPS=$_POST["carte_coordonnee_GPS"];
$mode_vie=array($_POST["mode_vie[]"]);
$regime=array($_POST["regime[]"])
$message=trim(htmlspecialchars($_POST["message"]));

//ici on fait les test pour vour si mes donee sont bien correcte

if(!filter_var($contact, FILTER_VALIDATE_EMAIL)){
    header("Location: ../creer_annonce.php?error=invalid_email");
    exit();
}




















try {
    require("../../includes/dbConnection.php");
    $dbconn= getDbConnection();

    if(!$dbconn){
        header("location: ../creer_annonce.php?error=server_error");
        exit();
    }

 
    
$mesAjouts = $dbconn->prepare(" INSERT INTO annonce(
titre,
adresse_1,
adresse_2,
adresse_3,
adresse_4,
ville,
code_postale,
loyer_location_chez_habitant,
description,
surface_logement,
surface_chambres,
nombres_chambres, --ici a faire en html
date_expiration,
date_publication,
date_modification,
carte_coordonnee_GPS,
date_cloture,
loyer_colocation,
est_fumeur,
a_enfant,
a_animaux

) VALUES (
 
:titre,
:adresse_1,
:adresse_2,
:adresse_3,
:adresse_4,
:ville,
:code_postale,
:loyer_location_chez_habitant,
:description,
:surface_logement,
:surface_chambres,
:nombres_chambres,
:date_expiration,
:date_publication,
:date_modification,
:carte_coordonnee_GPS,
:date_cloture,
:loyer_colocation,
:est_fumeur,
:a_enfant,
:a_animaux

  )" 
  
);

$mesAjouts->bindParam(':titre', $titre);
$mesAjouts->bindParam(':adresse_1', $adresse_1);
$mesAjouts->bindParam(':adresse_2', $adresse_2);
$mesAjouts->bindParam(':adresse_3', $adresse_3);
$mesAjouts->bindParam(':adresse_4', $adresse_4);
$mesAjouts->bindParam(':ville', $ville);
$mesAjouts->bindParam(':code_postale', $code_postal);
$mesAjouts->bindParam(':loyer_location_chez_habitant', $loyer);
$mesAjouts->bindParam(':description', $description);
$mesAjouts->bindParam(':surface_logement', $surface_logement);
$mesAjouts->bindParam(':surface_chambres',$surface_chambres);
$mesAjouts->bindParam(':disponibilité', $disponibilité);
$mesAjouts->bindParam(':contact', $contact);
$mesAjouts->bindParam(':date_expiration', $date_expiration);
$mesAjouts->bindParam(':date_modification', $date_modification);
$mesAjouts->bindParam(':carte_coordonnee_GPS', $carte_coordonnee_GPS);
$mesAjouts->bindParam(':message', $message);
$mesAjouts->bindParam(':date_expiration', $date_expiration);
$mesAjouts->bindParam(':date_expiration', $date_expiration);
$mesAjouts->bindParam(':date_expiration', $date_expiration);

} catch (Exception $e) {
    //throw $th;
}




?>