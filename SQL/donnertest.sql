
-- This script ensures all dependencies exist before adding messages.

-- 1. Ensure User 7 exists (The Owner)
INSERT IGNORE INTO utilisateur (id_utilisateur, nom, email, mot_de_passe, situation_professionnel, garant, retraite, 


GitHub

coLocation

Manus is an AI Agent and can make mistakes. Please double-check before use.

Download Manus app

Get notified when your task is ready

-- ROBUST POPULATE SCRIPT
-- This script ensures all dependencies exist before adding messages.

-- 1. Ensure User 7 exists (The Owner)
INSERT IGNORE INTO utilisateur (id_utilisateur, nom, email, mot_de_passe, situation_professionnel, garant, retraite, caisse_allocation_familial, date_naissance, photo_profil, salaire_mensuel_net, prenom, revenu_fiscal, id_role) VALUES
(7, 'Proprio', 'owner@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Propriétaire', 1, 0.00, 0.00, '1980-01-01', '', 999.99, 'Marc', '50000', 2);

-- 2. Ensure Other Users exist
INSERT IGNORE INTO utilisateur (id_utilisateur, nom, email, mot_de_passe, situation_professionnel, garant, retraite, caisse_allocation_familial, date_naissance, photo_profil, salaire_mensuel_net, prenom, revenu_fiscal, id_role) VALUES
(1, 'Dupont', 'jean.dupont@email.com', '...', 'Admin', 0, 0, 0, '1985-03-15', '', 999.99, 'Jean', '45000', 1),
(2, 'Martin', 'sophie.martin@email.com', '...', 'Proprio', 1, 0, 0, '1978-07-22', '', 999.99, 'Sophie', '38000', 2),
(3, 'Petit', 'thomas.petit@email.com', '...', 'Etudiant', 1, 0, 0, '1998-11-05', '', 999.99, 'Thomas', '15000', 3),
(4, 'Bernard', 'julie.bernard@email.com', '...', 'Salarie', 0, 0, 0, '1995-04-18', '', 999.99, 'Julie', '22000', 3),
(5, 'Durand', 'lucas.durand@email.com', '...', 'Etudiant', 0, 0, 0, '2001-05-20', '', 500.00, 'Lucas', '0', 3);

-- 3. Ensure User 7 has Announcements (IDs 10, 11, 12)
INSERT IGNORE INTO annonce (id_annonce, titre, adresse_1, ville, code_postal, loyer_location_chez_habitant, description, surface_logement, surface_chambres, nombre_chambre, date_publication, date_modification, date_cloture, loyer_colocation) VALUES
(10, 'Appartement Centre Ville', '12 Rue de la Paix', 'Vienne', 38200, 500.00, 'Bel appartement lumineux.', 50.00, 15.00, 2, '2026-07-01', '2026-07-01', '2026-08-01', 500.00),
(11, 'Studio Etudiant', '5 Place Saint-Louis', 'Vienne', 38200, 350.00, 'Studio calme proche gare.', 25.00, 20.00, 1, '2026-07-01', '2026-07-01', '2026-08-01', 350.00),
(12, 'Chambre en Colocation', '24 Avenue Gambetta', 'Vienne', 38200, 400.00, 'Chambre meublée.', 80.00, 12.00, 3, '2026-07-01', '2026-07-01', '2026-08-01', 400.00);

-- Link these announcements to User 7
INSERT IGNORE INTO annonce_utilisateur (id_utilisateur, id_annonce) VALUES (7, 10), (7, 11), (7, 12);

-- 4. Finally, Insert the Messages
INSERT INTO envoi_message (id_utilisateur, id_utilisateur_1, id_annonce, date_, contenu) VALUES
-- Conversation with User 1 on Annonce 10
(1, 7, 10, '2026-07-01', 'Bonjour Marc, l''appartement est-il libre ?'),
(7, 1, 10, '2026-07-01', 'Oui Jean, il est libre. Voulez-vous visiter ?'),
(1, 7, 10, '2026-07-02', 'Oui, demain à 14h ?'),

-- Conversation with User 2 on Annonce 11
(2, 7, 11, '2026-07-03', 'Bonjour, est-ce que les charges sont incluses ?'),
(7, 2, 11, '2026-07-03', 'Bonjour Sophie, oui tout est inclus.'),
(2, 7, 11, '2026-07-03', 'Parfait, merci !'),

-- Conversation with User 3 on Annonce 12
(3, 7, 12, '2026-07-04', 'Salut, la chambre est-elle meublée ?'),
(7, 3, 12, '2026-07-04', 'Salut Thomas, oui : lit, bureau et placard.'),
(3, 7, 12, '2026-07-05', 'Génial, merci !');
