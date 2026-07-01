<?php $baseURL = "http://localhost/coLocation"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="colocation vienne ">
    <meta name="Author" content="Issintia Said, Rohid SAFI, Lawrence">
    <title>colocation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo $baseURL; ?>/assets/style/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<?php session_start(); ?>
<body>
   <nav class="navbar navbar-expand-lg fixed-top bg-body-tertiary">
     <div class="container-fluid">
      <a class="navbar-brand " href="<?php echo $baseURL; ?>/index.php">Colocation Vienne</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?php echo $baseURL; ?>/index.php">Acceuil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="<?php echo $baseURL; ?>/pages/recherche.php">Rechercher une colocation</a>
        </li>
         <li class="nav-item">
          <a class="nav-link " href="<?php echo $baseURL; ?>/pages/formulaire_inscription.php">Inscription</a>
        </li>
         <li class="nav-item">
          <a class="nav-link " href="<?php echo $baseURL; ?>/pages/connexion.php">Connexion</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="<?php echo $baseURL; ?>/pages/creer_annonce.php">creer et publier des annonces</a>
        </li>
       <li class="nav-item ">
          <a class="nav-link " href="<?php echo $baseURL; ?>/pages/be/deconnexion.php">se deconnecter</a>
        </li>
         <li class="nav-item">
          <a href=""><img class="rounded-circle avatar" src="https://png.pngtree.com/png-vector/20230131/ourmid/pngtree-flat-style-user-profile-icon-on-isolated-background-vector-png-image_49602770.jpg"/></a>
        </li> <!--image a changer -->

      </ul>
    </div>
    </div>
  </nav>