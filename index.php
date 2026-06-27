<?php
include("includes/header.php");

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

<main class="landing-page">
    <section class="hero-section">
        <div class="hero-content">
            <p class="hero-kicker">Colocation Vienne</p>
            <h1>Vivez mieux, ensemble.</h1>
            <p class="hero-text">
                Trouvez une chambre, mais surtout une ambiance: des annonces pensees pour matcher avec vos habitudes, votre budget et votre facon de vivre.
            </p>
            <form class="hero-search" id="announceSearchForm">
                <label class="search-field" for="announceSearch">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" id="announceSearch" placeholder="Rechercher: centre, jardin, fibre, calme...">
                </label>
                <select id="budgetFilter" aria-label="Filtrer par budget">
                    <option value="all">Tous budgets</option>
                    <option value="400">Moins de 400 EUR</option>
                    <option value="500">Moins de 500 EUR</option>
                    <option value="600">Moins de 600 EUR</option>
                </select>
                <button type="reset">Effacer</button>
            </form>
            <div class="hero-stats" aria-label="Statistiques de la plateforme">
                <span><strong>42</strong> annonces actives</span>
                <span><strong>91%</strong> match moyen</span>
                <span><strong>24h</strong> reponse rapide</span>
            </div>
        </div>
        <div class="hero-panel" aria-label="Apercu du matching">
            <div class="match-card">
                <span class="match-card-label">Votre vibe</span>
                <strong>Calme + propre + social</strong>
                <div class="match-line">
                    <span style="width: 92%"></span>
                </div>
                <p>Le moteur de recherche croise budget, quartier et rythme de vie pour faire remonter les colocations qui ont du sens.</p>
            </div>
        </div>
    </section>

    <section class="announces-section" id="annonces">
        <div class="section-heading">
            <div>
                <p class="section-kicker">Annonces test</p>
                <h2>Dernieres opportunites</h2>
            </div>
            <p id="resultCount"><?php echo count($annonces); ?> annonces trouvees</p>
        </div>

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

        <p class="empty-state" id="emptyState">Aucune annonce ne correspond a votre recherche. Essayez un autre quartier, budget ou style de vie.</p>
    </section>

    <section class="steps-section">
        <div>
            <p class="section-kicker">Pourquoi ca marche</p>
            <h2>Ne cherchez plus seulement un appartement, trouvez vos futurs amis.</h2>
        </div>
        <div class="steps-grid">
            <article>
                <span class="nombre-cercle">1</span>
                <h3>Compatibilite</h3>
                <p>Mode de vie, rythme, menage et invites sont pris en compte avant la visite.</p>
            </article>
            <article>
                <span class="nombre-cercle">2</span>
                <h3>Discussion</h3>
                <p>Une prise de contact claire pour faire connaissance et poser les bonnes questions.</p>
            </article>
            <article>
                <span class="nombre-cercle">3</span>
                <h3>Dossier simple</h3>
                <p>Les documents, cautions et informations utiles sont organises au meme endroit.</p>
            </article>
        </div>
    </section>
</main>

<script>
    const searchInput = document.getElementById("announceSearch");
    const budgetFilter = document.getElementById("budgetFilter");
    const resultCount = document.getElementById("resultCount");
    const emptyState = document.getElementById("emptyState");
    const cards = Array.from(document.querySelectorAll(".announce-card"));
    const form = document.getElementById("announceSearchForm");

    function filterAnnounces() {
        const query = searchInput.value.trim().toLowerCase();
        const maxBudget = budgetFilter.value;
        let visibleCount = 0;

        cards.forEach((card) => {
            const matchesText = card.dataset.search.includes(query);
            const matchesBudget = maxBudget === "all" || Number(card.dataset.price) <= Number(maxBudget);
            const isVisible = matchesText && matchesBudget;

            card.hidden = !isVisible;
            if (isVisible) {
                visibleCount++;
            }
        });

        resultCount.textContent = visibleCount + (visibleCount > 1 ? " annonces trouvees" : " annonce trouvee");
        emptyState.classList.toggle("is-visible", visibleCount === 0);
    }

    searchInput.addEventListener("input", filterAnnounces);
    budgetFilter.addEventListener("change", filterAnnounces);
    form.addEventListener("reset", () => {
        setTimeout(filterAnnounces, 0);
    });
</script>

<?php
include("includes/footer.php");
?>
