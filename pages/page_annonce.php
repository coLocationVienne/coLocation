<?php 
include("../includes/header.php");
$annonces = [
    [
        "title" => "Chambre lumineuse proche du centre",
        "city" => "Vienne centre",
        "price" => 420,
        "surface" => "18 m2",
        "available" => "Disponible maintenant",
        "match" => 96,
        "profile" => "Calme, etudiant, teletravail",
        "tags" => ["2 colocataires", "Balcon", "Fibre"],
        "image" => "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=80"
    ],
    [
        "title" => "Appartement partage esprit creatif",
        "city" => "Estressin",
        "price" => 365,
        "surface" => "14 m2",
        "available" => "A partir du 15 juillet",
        "match" => 89,
        "profile" => "Artistes, sorties, cuisine commune",
        "tags" => ["Atelier", "Cuisine equipee", "Soirees"],
        "image" => "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=80"
    ],
    [
        "title" => "Maison avec jardin et ambiance familiale",
        "city" => "Pont-Eveque",
        "price" => 510,
        "surface" => "22 m2",
        "available" => "Libre en aout",
        "match" => 93,
        "profile" => "Nature, animaux acceptes, repas partages",
        "tags" => ["Jardin", "Parking", "Maison"],
        "image" => "https://images.unsplash.com/photo-1560185007-c5ca9d2c014d?auto=format&fit=crop&w=900&q=80"
    ],
    [
        "title" => "Studio partage premium pres de la gare",
        "city" => "Gare de Vienne",
        "price" => 590,
        "surface" => "20 m2",
        "available" => "Disponible maintenant",
        "match" => 84,
        "profile" => "Actifs, mobilite, rythme independant",
        "tags" => ["Gare", "Ascenseur", "Meuble"],
        "image" => "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80"
    ],
];

?>

<main class="container-xl">


<nav class="navbar bg-body-tertiary mt ft">
  <div class="container-fluid">
    
  <form class="d-flex flex-column formu" role="search">
      <legend>Explorer</legend>
      <input class="form-control me-2" type="search" placeholder="localisation" aria-label="localisation"/>
      <input class="form-control me-2" type="search" placeholder="votre prix min/max" aria-label="prix"/>
      <button class="btn btn-outline-success" type="submit">recherche</button>
    </form>
  </div>
</nav>



  <section class="announces-section" id="annonces">
        <div class="section-heading">
            <div>
                <h2>Vos annonces</h2>
            </div>
            <p id="resultCount"><?php echo count($annonces); ?> annonces trouvees</p>
        </div>

        
<form action="be/ajouter_annonce.php" method="POST" class="annonce-form">
        <div class="announce-grid" id="announceGrid">
            <?php foreach ($annonces as $annonce): ?>
                <?php
                    $searchText = strtolower($annonce["title"] . " " . $annonce["city"] . " " . $annonce["profile"] . " " . implode(" ", $annonce["tags"]) . " " . $annonce["available"]);
                ?>
                <article class="announce-card" data-search="<?php echo htmlspecialchars($searchText); ?>" data-price="<?php echo $annonce["price"]; ?>">
                    <div class="announce-image">
                        <img src="<?php echo $annonce["image"]; ?>" alt="<?php echo htmlspecialchars($annonce["title"]); ?>">
                        <span><?php echo $annonce["match"]; ?>% compatible</span>
                    </div>
                    <div class="announce-body">
                        <div class="announce-topline">
                            <span><?php echo $annonce["city"]; ?></span>
                            <strong><?php echo $annonce["price"]; ?> EUR/mois</strong>
        </div>

                        <h3><?php echo $annonce["title"]; ?></h3>
                        <p><?php echo $annonce["profile"]; ?></p>
                        <div class="announce-meta">
                            <span><i class="fa-regular fa-calendar"></i><?php echo $annonce["available"]; ?></span>
                            <span><i class="fa-solid fa-ruler-combined"></i><?php echo $annonce["surface"]; ?></span>
                        </div>
                        <div class="tag-list">
                            <?php foreach ($annonce["tags"] as $tag): ?>
                                <span><?php echo $tag; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
       </form>
    </section>

</main>

<?php 
include("../includes/footer.php");

?>