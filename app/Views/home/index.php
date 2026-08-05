<?php 

use App\Core\Config;

include __DIR__ . "/../partials/header.php"; 
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
                <span><strong><?php echo count($annoncesFromDb); ?></strong> annonces actives</span>
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
                <p class="section-kicker">Annonces récentes</p>
                <h2>Dernières opportunités</h2>
            </div>
            <p id="resultCount"><?php echo count($annoncesFromDb); ?> annonces trouvées</p>
        </div>

        <div class="announce-grid" id="announceGrid">
            <?php foreach ($annoncesFromDb as $a): ?>
                <?php
                    $searchText = strtolower($a->getTitre() . " " . $a->getVille() . " " . $a->getDescription());
                    $photo = $a->getPhoto() ? (strpos($a->getPhoto(), 'http') === 0 ? $a->getPhoto() : Config::url($a->getPhoto())) : "https://via.placeholder.com/400x300?text=Pas+de+photo";
                ?>
                <article class="announce-card" data-search="<?php echo htmlspecialchars($searchText); ?>" data-price="<?php echo $a->getLoyer(); ?>">
                    <div class="announce-image">
                        <img src="<?php echo $photo; ?>" alt="<?php echo htmlspecialchars($a->getTitre()); ?>">
                        <span>90% compatible</span>
                    </div>
                    <div class="announce-body">
                        <div class="announce-topline">
                            <span><?php echo htmlspecialchars($a->getVille()); ?></span>
                            <strong><?php echo number_format($a->getLoyer(), 2); ?> EUR/mois</strong>
                        </div>
                        <h3><?php echo htmlspecialchars($a->getTitre()); ?></h3>
                        <p class="text-truncate"><?php echo htmlspecialchars(substr($a->getDescription(), 0, 100)) . '...'; ?></p>
                        <div class="announce-meta">
                            <span><i class="fa-regular fa-calendar"></i> Publiée le <?php echo date('d/m/Y', strtotime($a->getDatePublication())); ?></span>
                            <span><i class="fa-solid fa-ruler-combined"></i> <?php echo $a->getSurfaceChambre(); ?> m²</span>
                        </div>
                        <div class="mt-3 d-flex gap-2">
                            <a href="<?php echo Config::url('annonce/show?id=' . $a->getId()); ?>" class="btn btn-primary btn-sm flex-grow-1">Voir l'annonce</a>
                            <a href="<?php echo Config::url('annonce/show?id=' . $a->getId()); ?>#visite" class="btn btn-outline-success btn-sm" title="Planifier une visite">
                                <i class="fas fa-calendar-check"></i>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <p class="empty-state" id="emptyState">Aucune annonce ne correspond a votre recherche. Essayez un autre quartier, budget ou style de vie.</p>
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
            const matchesBudget = maxBudget === "all" || Number(card.getAttribute('data-price')) <= Number(maxBudget);
            const isVisible = matchesText && matchesBudget;

            card.hidden = !isVisible;
            if (isVisible) {
                visibleCount++;
            }
        });

        resultCount.textContent = visibleCount + (visibleCount > 1 ? " annonces trouvées" : " annonce trouvée");
        emptyState.classList.toggle("is-visible", visibleCount === 0);
    }

    searchInput.addEventListener("input", filterAnnounces);
    budgetFilter.addEventListener("change", filterAnnounces);
    form.addEventListener("reset", () => {
        setTimeout(filterAnnounces, 0);
    });
</script>

<?php include __DIR__ . "/../partials/footer.php"; ?>
