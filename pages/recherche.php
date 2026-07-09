<?php require_once "../includes/header.php"; ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
    #map-container {
        display: flex;
        height: calc(100vh - 70px);
        margin-top: 70px;
    }
    #map {
        flex: 2;
        height: 100%;
    }
    #sidebar {
        flex: 1;
        height: 100%;
        overflow-y: auto;
        background: #f8f9fa;
        padding: 20px;
        border-left: 1px solid #ddd;
    }
    .annonce-card {
        background: white;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: transform 0.2s;
    }
    .annonce-card:hover {
        transform: translateY(-3px);
    }
    .annonce-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }
    .annonce-info {
        padding: 15px;
    }
    .popup-card img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
    }
</style>

<div id="map-container">
    <!-- Left: Map -->
    <div id="map"></div>

    <!-- Right: Filters and List -->
    <div id="sidebar">
        <h4>Filtrer les annonces</h4>
        <div class="mb-4">
            <input type="text" id="searchFilter" class="form-control mb-2" placeholder="Rechercher par titre ou ville..." onkeyup="updateFilters()">
            <input type="number" id="priceFilter" class="form-control" placeholder="Prix max (€)" onchange="updateFilters()">
        </div>
        <hr>
        <div id="annonce-list">
            <!-- Dynamic list here -->
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    let map;
    let markers = [];
    let allAnnonces = [];

    function initMap() {
        // Center on Vienne, France
        map = L.map('map').setView([45.525, 4.875], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        fetchAnnonces();
    }

    function fetchAnnonces() {
        fetch('be/get_annonces_json.php')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    allAnnonces = data.annonces;
                    displayAnnonces(allAnnonces);
                }
            });
    }

    function displayAnnonces(annonces) {
        // Clear existing markers
        markers.forEach(m => map.removeLayer(m));
        markers = [];

        const listContainer = document.getElementById('annonce-list');
        listContainer.innerHTML = '';

        annonces.forEach(a => {
            // Add Marker if GPS exists
            if (a.gps) {
                const coords = a.gps.split(',').map(c => parseFloat(c.trim()));
                if (coords.length === 2 && !isNaN(coords[0])) {
                    const marker = L.marker(coords).addTo(map);
                    
                    const popupContent = `
                        <div class="popup-card" style="width: 200px;">
                            ${a.photo ? `<img src="../${a.photo}">` : ''}
                            <h6 class="mt-2">${a.titre}</h6>
                            <p class="mb-1 text-primary"><strong>${a.loyer}€ / mois</strong></p>
                            <a href="formulaire_modifier_annonce.php?id=${a.id}" class="btn btn-sm btn-outline-primary w-100">Voir détails</a>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                    markers.push(marker);
                }
            }

            // Add to Sidebar List
            const card = document.createElement('div');
            card.className = 'annonce-card';
            card.innerHTML = `
                ${a.photo ? `<img src="../${a.photo}">` : '<div style="height:150px; background:#eee; display:flex; align-items:center; justify-content:center;">Pas de photo</div>'}
                <div class="annonce-info">
                    <h6>${a.titre}</h6>
                    <p class="text-muted mb-1">${a.ville}</p>
                    <p class="text-primary mb-0"><strong>${a.loyer}€ / mois</strong></p>
                </div>
            `;
            card.onclick = () => {
                if (a.gps) {
                    const coords = a.gps.split(',').map(c => parseFloat(c.trim()));
                    map.setView(coords, 16);
                    // Find marker to open popup
                    const marker = markers.find(m => m.getLatLng().lat === coords[0] && m.getLatLng().lng === coords[1]);
                    if (marker) marker.openPopup();
                }
            };
            listContainer.appendChild(card);
        });
    }

    function updateFilters() {
        const search = document.getElementById('searchFilter').value.toLowerCase();
        const price = parseFloat(document.getElementById('priceFilter').value) || Infinity;

        const filtered = allAnnonces.filter(a => {
            const matchesSearch = a.titre.toLowerCase().includes(search) || a.ville.toLowerCase().includes(search);
            const matchesPrice = a.loyer <= price;
            return matchesSearch && matchesPrice;
        });

        displayAnnonces(filtered);
    }

    document.addEventListener('DOMContentLoaded', initMap);
</script>

<?php require_once "../includes/footer.php"; ?>
