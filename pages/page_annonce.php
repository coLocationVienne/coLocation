<?php 
include("../includes/header.php");

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



<section class="d-flex g-3 section">
<!--ici seront mes images-->

           <figure class="figure">
                <img src="https://media.istockphoto.com/photos/stylish-living-room-interior-with-beautiful-house-plants-picture-id1312439845?k=20&m=1312439845&s=170667a&w=0&h=Dm0vTngpk4UP8Zh80DxaDoOAl1IdHxegakHnFlV709o=" class="figure-img img-fluid rounded img-thumbnail" alt="...">
                <figcaption class="blockquote-footer">245$</figcaption>
            </figure>

            <figure class="figure">
                <img src="https://media.istockphoto.com/id/1312439694/fr/photo/int%C3%A9rieur-%C3%A9l%C3%A9gant-de-salle-de-salon-avec-de-belles-usines-de-maison.jpg?s=2048x2048&w=is&k=20&c=y3JkqGZj4qWfYzE1zJNs9A6JFO8p767hYBKFOoxgIx4=" class="figure-img img-fluid rounded img-thumbnail" alt="...">
                <figcaption class="blockquote-footer">475$</figcaption>
            </figure>

              <figure class="figure">
                <img src="https://media.istockphoto.com/id/1305457119/fr/photo/int%C3%A9rieur-%C3%A9l%C3%A9gant-de-salle-de-s%C3%A9jour-avec-le-sofa-et-les-coussins-confortables.jpg?s=2048x2048&w=is&k=20&c=dgZBhEiiHj2vbYSwSCzfvqc8W_WjiNkJ6VpoS5ds5RU=" class="figure-img img-fluid rounded img-thumbnail" alt="...">
                <figcaption class="blockquote-footer">566$</figcaption>
            </figure>
 
            <a href="" class="btn btn-primary">creez vos annonces</a>
</section>


</main>


<?php 
include("../includes/footer.php");

?>