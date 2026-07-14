<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../includes/dbConnection.php";

if (empty($_SESSION['user_id'])) {
    header("Location: connexion.php?error=erreur_connexion");
    exit();
}

$dbConn = getDbConnection();
$userId = (int) $_SESSION['user_id'];
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

$stmt = $dbConn->prepare("
    SELECT
        a.*,
        (
            SELECT p.url
            FROM annonce_photo ap
            INNER JOIN photo p ON p.id_photo = ap.id_photo
            WHERE ap.id_annonce = a.id_annonce
            ORDER BY p.id_photo ASC
            LIMIT 1
        ) AS image_url
    FROM annonce a
    INNER JOIN annonce_utilisateur au ON au.id_annonce = a.id_annonce
    WHERE au.id_utilisateur = :id_utilisateur
    ORDER BY a.date_publication DESC, a.id_annonce DESC
");
$stmt->execute([
    ':id_utilisateur' => $userId
]);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../includes/header.php");
?>

<main class="page-annonce">
    <section class="annonce-entete">
        <div>
            <p class="petit-titre">Mes annonces</p>
            <h1>Gérez vos annonces</h1>
            <p>
                Retrouvez uniquement les annonces que vous avez publiées, puis modifiez les informations du logement quand nécessaire.
            </p>
        </div>

        <form class="recherche-annonce">
            <h2>Explorer</h2>
            <input type="search" id="searchLocation" placeholder="Localisation">
            <input type="number" id="searchBudget" placeholder="Budget maximum">
            <button type="button" id="searchButton">Rechercher</button>
        </form>
    </section>

    <section class="announces-section" id="annonces">
        <div class="section-heading">
            <div>
                <p class="petit-titre">Disponibles</p>
                <h2>Vos annonces</h2>
            </div>
            <p id="resultCount"><?php echo count($annonces); ?> annonce(s) trouvée(s)</p>
        </div>

        <?php if ($success === 'updated'): ?>
            <p class="annonce-message">Votre annonce a bien été modifiée.</p>
        <?php endif; ?>

        <?php if ($error === 'unauthorized'): ?>
            <p class="annonce-message">Vous ne pouvez pas modifier une annonce qui ne vous appartient pas.</p>
        <?php endif; ?>

        <?php if (empty($annonces)): ?>
            <p class="empty-state is-visible">Vous n'avez pas encore publié d'annonce.</p>
        <?php else: ?>
            <div class="announce-grid" id="announceGrid">
                <?php foreach ($annonces as $annonce): ?>
                    <?php
                        $image = !empty($annonce['image_url'])
                            ? '../' . $annonce['image_url']
                            : 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=80';
                        $searchText = strtolower($annonce['titre'] . ' ' . $annonce['ville'] . ' ' . $annonce['description']);
                    ?>

                    <article class="announce-card" data-search="<?php echo htmlspecialchars($searchText); ?>" data-price="<?php echo htmlspecialchars($annonce['loyer_location_chez_habitant']); ?>">
                        <div class="announce-image">
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($annonce['titre']); ?>">
                            <span><?php echo htmlspecialchars($annonce['loyer_location_chez_habitant']); ?> EUR/mois</span>
                        </div>

                        <div class="announce-body">
                            <div class="announce-topline">
                                <span><?php echo htmlspecialchars($annonce['ville']); ?></span>
                                <strong><?php echo htmlspecialchars($annonce['surface_logement']); ?> m²</strong>
                            </div>

                            <h3><?php echo htmlspecialchars($annonce['titre']); ?></h3>
                            <p><?php echo htmlspecialchars($annonce['description']); ?></p>

                            <div class="announce-meta">
                                <span><i class="fa-regular fa-calendar"></i> Expire le <?php echo htmlspecialchars($annonce['date_expiration']); ?></span>
                                <span><i class="fa-solid fa-bed"></i> <?php echo htmlspecialchars($annonce['nombre_chambre']); ?> chambre(s)</span>
                            </div>

                            <div class="tag-list">
                                <span><?php echo htmlspecialchars($annonce['surface_chambres']); ?> m² chambre</span>
                                <span><?php echo htmlspecialchars($annonce['code_postal']); ?></span>
                            </div>

                            <div class="annonce-actions mt-3">
                                <a href="formulaire_modifier_annonce.php?id_annonce=<?php echo (int) $annonce['id_annonce']; ?>" class="btn btn-primary">
                                    Modifier
                                </a>

                                <form action="be/supprimer_annonce.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette annonce ?');">
                                        <input type="hidden" name="id_annonce" value="<?php echo (int) $annonce['id_annonce']; ?>">
                                        <button type="submit" class="btn btn-danger">
                                            Supprimer
                                        </button>
                                </form>

                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <p class="empty-state" id="emptyState">Aucune annonce ne correspond à votre recherche.</p>
        <?php endif; ?>
    </section>
</main>

<script>
    const searchLocation = document.getElementById("searchLocation");
    const searchBudget = document.getElementById("searchBudget");
    const searchButton = document.getElementById("searchButton");
    const resultCount = document.getElementById("resultCount");
    const emptyState = document.getElementById("emptyState");
    const cards = Array.from(document.querySelectorAll(".announce-card"));

    function filterAnnounces() {
        const query = searchLocation.value.trim().toLowerCase();
        const budget = searchBudget.value;
        let total = 0;

        cards.forEach((card) => {
            const matchText = card.dataset.search.includes(query);
            const matchBudget = budget === "" || Number(card.dataset.price) <= Number(budget);
            const visible = matchText && matchBudget;

            card.hidden = !visible;

            if (visible) {
                total++;
            }
        });

        resultCount.textContent = total + " annonce(s) trouvée(s)";

        if (emptyState) {
            emptyState.classList.toggle("is-visible", total === 0);
        }
    }

    if (searchButton) {
        searchButton.addEventListener("click", filterAnnounces);
        searchLocation.addEventListener("input", filterAnnounces);
        searchBudget.addEventListener("input", filterAnnounces);
    }
</script>

<?php
include("../includes/footer.php");
?>
