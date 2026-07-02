<?php 
session_start();
$user_id= $_SESSION['user_id'];

include("../../includes/dbConnection.php");
$db=getDbConnection();

$myReq= $db->prepare("SELECT a.titre,a.ville,a.description,a.surface_chambres,a.nombre_chambre,a.loyer_colocation
FROM annonce a, annonce_utilisateur au  WHERE au.id_utilisateur = :user_id");
$myReq->bindParam(':user_id', $userId);
$myReq->execute();

$annonce=$myReq->fetch(PDO::FETCH_ASSOC);
var_dump($annonce);




?>