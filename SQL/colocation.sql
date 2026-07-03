-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- HÃ´te : 127.0.0.1
-- GÃ©nÃ©rÃ© le : mar. 09 juin 2026 Ã  09:36
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de donnÃ©es : `colocation`
--

-- --------------------------------------------------------
create database if not exists `colocation` default character set utf8mb4 collate utf8mb4_general_ci;
use `colocation`;
--
-- Structure de la table `age_recherche`
--

CREATE TABLE `age_recherche` (
  `id_age` int(11) NOT NULL,
  `tranche_age` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `age_recherche`
--

INSERT INTO `age_recherche` (`id_age`, `tranche_age`) VALUES
(1, '18-25'),
(2, '26-35'),
(3, '36-50'),
(4, '50-64'),
(5, '65+');

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

CREATE TABLE `annonce` (
  `id_annonce` int(11) NOT NULL,
  `titre` varchar(50) NOT NULL,
  `adresse_1` varchar(255) NOT NULL,
  `adresse_2` varchar(255) DEFAULT NULL,
  `adresse_3` varchar(255) DEFAULT NULL,
  `adresse_4` varchar(255) DEFAULT NULL,
  `ville` varchar(50) NOT NULL,
  `code_postal` int(11) NOT NULL,
  `loyer_location_chez_habitant` decimal(5,2) NOT NULL,
  `description` text NOT NULL,
  `surface_logement` decimal(5,2) NOT NULL,
  `surface_chambres` decimal(5,2) NOT NULL,
  `nombre_chambre` int(11) NOT NULL,
  `date_expiration` date DEFAULT NULL,
  `date_publication` date NOT NULL,
  `date_modification` date NOT NULL,
  `carte_coordonnee_GPS` varchar(40) DEFAULT NULL,
  `date_cloture` date NOT NULL,
  `loyer_colocation` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `annonce`
--

INSERT INTO `annonce` (`id_annonce`, `titre`, `adresse_1`, `adresse_2`, `adresse_3`, `adresse_4`, `ville`, `code_postal`, `loyer_location_chez_habitant`, `description`, `surface_logement`, `surface_chambres`, `nombre_chambre`, `date_expiration`, `date_publication`, `date_modification`, `carte_coordonnee_GPS`, `date_cloture`, `loyer_colocation`) VALUES
(1, 'Colocation Ã©tudiante lumineuse', '15 Rue de la RÃ©publique', 'Appartement 3B', NULL, NULL, 'Lyon', 69001, 650.00, 'Superbe appartement rÃ©novÃ© proche universitÃ©. TrÃ¨s lumineux avec balcon. Quartier calme et bien desservi par les transports.', 85.50, 12.50, 3, '2026-07-09', '2026-06-09', '2026-06-09', '45.764043, 4.835659', '2026-07-09', 550.00),
(2, 'Chambre cosy chez l\'habitant', '8 Avenue Victor Hugo', NULL, NULL, NULL, 'Paris', 75016, 800.00, 'Chambre meublÃ©e dans appartement spacieux. PropriÃ©taire sympathique. Jardin partagÃ©. Proche commerces et mÃ©tro.', 120.00, 15.00, 1, '2026-05-25', '2026-04-25', '2026-06-09', '48.856614, 2.352222', '2026-06-08', 700.00),
(3, 'Chambre lumineuse proche du centre', '24 Rue Marchande', 'Appartement 2A', NULL, NULL, 'Vienne', 38200, 420.00, 'Colocation calme avec fibre, balcon et espaces communs agrÃ©ables.', 72.00, 18.00, 3, '2026-08-31', '2026-07-03', '2026-07-03', NULL, '2026-08-31', 420.00),
(4, 'Appartement partagÃ© ambiance conviviale', '8 Rue des CÃ¨dres', NULL, NULL, NULL, 'Estressin', 38200, 365.00, 'Logement agrÃ©able pour colocataires sociables, proche des transports.', 64.00, 14.00, 2, '2026-08-15', '2026-07-03', '2026-07-03', NULL, '2026-08-15', 365.00),
(5, 'Maison avec jardin', '11 Chemin du Jardin', NULL, NULL, 'Maison au calme', 'Pont-Ã‰vÃªque', 38780, 510.00, 'Colocation tranquille avec jardin, parking et ambiance familiale.', 110.00, 22.00, 4, '2026-09-01', '2026-07-03', '2026-07-03', NULL, '2026-09-01', 510.00),
(6, 'Studio partagÃ© proche de la gare', '3 Avenue de la Gare', 'Studio 4', NULL, NULL, 'Vienne', 38200, 590.00, 'Logement pratique pour actifs, avec accÃ¨s rapide Ã  la gare et commerces Ã  proximitÃ©.', 42.00, 20.00, 1, '2026-08-20', '2026-07-03', '2026-07-03', NULL, '2026-08-20', 590.00);

-- --------------------------------------------------------

--
-- Structure de la table `annonce_age`
--

CREATE TABLE `annonce_age` (
  `id_annonce` int(11) NOT NULL,
  `id_age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `annonce_age`
--

INSERT INTO `annonce_age` (`id_annonce`, `id_age`) VALUES
(1, 1),
(1, 2),
(2, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `annonce_avis`
--

CREATE TABLE `annonce_avis` (
  `id_avis` int(11) NOT NULL,
  `note` decimal(2,2) DEFAULT NULL,
  `date_` date NOT NULL,
  `commentaire` text NOT NULL,
  `id_annonce` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_utilisateur_1` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `annonce_avis`
--

INSERT INTO `annonce_avis` (`id_avis`, `note`, `date_`, `commentaire`, `id_annonce`, `id_utilisateur`, `id_utilisateur_1`) VALUES
(1, 0.99, '2026-05-30', 'TrÃ¨s bonne colocation ! L\'appartement est propre et bien situÃ©. Les colocataires sont sympathiques. Je recommande.', 1, 3, 2),
(2, 0.99, '2026-06-04', 'Annonce correcte mais le propriÃ©taire est un peu strict sur les rÃ¨gles. La chambre est petite mais fonctionnelle.', 2, 4, 2);

-- --------------------------------------------------------

--
-- Structure de la table `annonce_mode_vie`
--

CREATE TABLE `annonce_mode_vie` (
  `id_annonce` int(11) NOT NULL,
  `id_mode_vie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `annonce_mode_vie`
--

INSERT INTO `annonce_mode_vie` (`id_annonce`, `id_mode_vie`) VALUES
(1, 1),
(1, 3),
(2, 4),
(2, 5),
(3, 1),
(3, 5),
(4, 2),
(4, 5),
(5, 4),
(5, 7),
(6, 5);

-- --------------------------------------------------------

--
-- Structure de la table `annonce_photo`
--

CREATE TABLE `annonce_photo` (
  `id_annonce` int(11) NOT NULL,
  `id_photo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `annonce_photo`
--

INSERT INTO `annonce_photo` (`id_annonce`, `id_photo`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 5),
(2, 6),
(2, 7),
(3, 8),
(4, 9),
(5, 10),
(6, 11);

-- --------------------------------------------------------

--
-- Structure de la table `annonce_regime_alimentaire`
--

CREATE TABLE `annonce_regime_alimentaire` (
  `id_annonce` int(11) NOT NULL,
  `id_regime_alimentaire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `annonce_regime_alimentaire`
--

INSERT INTO `annonce_regime_alimentaire` (`id_annonce`, `id_regime_alimentaire`) VALUES
(1, 1),
(1, 2),
(3, 1),
(3, 2),
(4, 1),
(5, 1),
(6, 1);

-- --------------------------------------------------------

--
-- Structure de la table `annonce_utilisateur`
--

CREATE TABLE `annonce_utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `id_annonce` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `annonce_utilisateur`
--

INSERT INTO `annonce_utilisateur` (`id_utilisateur`, `id_annonce`) VALUES
(2, 1),
(2, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6);

-- --------------------------------------------------------

--
-- Structure de la table `envoi_message`
--

CREATE TABLE `envoi_message` (
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_utilisateur_1` int(11) DEFAULT NULL,
  `id_annonce` int(11) DEFAULT NULL,
  `date_` date NOT NULL,
  `contenu` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `envoi_message`
--

INSERT INTO `envoi_message` (`id_utilisateur`, `id_utilisateur_1`, `id_annonce`, `date_`, `contenu`) VALUES
(3, 4, 1, '2026-06-02', 'Salut Julie, j\'ai vu que tu cherchais une colocation. Cette annonce Ã  Lyon est vraiment bien ! Qu\'en penses-tu '),
(4, 3, 1, '2026-06-03', 'Merci Thomas ! Oui elle a l\'air super. Je vais contacter la propriÃ©taire pour visiter cette semaine.'),
(3, 4, 1, '2026-06-04', 'Super ! N\'hÃ©site pas si tu as besoin d\'infos, j\'ai dÃ©jÃ  visitÃ© l\'appartement.');

-- --------------------------------------------------------

--
-- Structure de la table `mode_vie`
--

CREATE TABLE `mode_vie` (
  `id_mode_vie` int(11) NOT NULL,
  `mode_avis` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `mode_vie`
--

INSERT INTO `mode_vie` (`id_mode_vie`, `mode_avis`) VALUES
(1, 'Calme'),
(2, 'Festif'),
(3, 'Ã‰tudiant'),
(4, 'Famille'),
(5, 'Travailleur'),
(6, 'Retraite'),
(7, 'Parent solo');

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
  `id_photo` int(11) NOT NULL,
  `url` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `photo`
--

INSERT INTO `photo` (`id_photo`, `url`) VALUES
(1, 'photos/annonce1/salon.jpg'),
(2, 'photos/annonce1/chambre1.jpg'),
(3, 'photos/annonce1/cuisine.jpg'),
(4, 'photos/annonce1/salle_de_bain.jpg'),
(5, 'photos/annonce2/chambre.jpg'),
(6, 'photos/annonce2/salon.jpg'),
(7, 'photos/annonce2/jardin.jpg'),
(8, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2auto=format&fit=crop&w=900&q=80'),
(9, 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267auto=format&fit=crop&w=900&q=80'),
(10, 'https://images.unsplash.com/photo-1560185007-c5ca9d2c014dauto=format&fit=crop&w=900&q=80'),
(11, 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85auto=format&fit=crop&w=900&q=80');

-- --------------------------------------------------------

--
-- Structure de la table `regime_alimentaire`
--

CREATE TABLE `regime_alimentaire` (
  `id_regime_alimentaire` int(11) NOT NULL,
  `regime_alimentaire` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `regime_alimentaire`
--

INSERT INTO `regime_alimentaire` (`id_regime_alimentaire`, `regime_alimentaire`) VALUES
(1, 'Omnivore'),
(2, 'VÃ©gÃ©tarien'),
(3, 'VÃ©gan'),
(4, 'Sans gluten'),
(5, 'Halal'),
(6, 'Cacher');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `role`
--

INSERT INTO `role` (`id_role`, `role`) VALUES
(1, 'Admin'),
(2, 'PropriÃ©taire'),
(3, 'Locataire');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(100) NOT NULL,
  `situation_professionnel` varchar(40) NOT NULL,
  `garant` tinyint(1) NOT NULL,
  `retraite` decimal(5,2) NOT NULL,
  `caisse_allocation_familial` decimal(5,2) NOT NULL,
  `date_naissance` date NOT NULL,
  `photo_profil` varchar(250) NOT NULL,
  `salaire_mensuel_net` decimal(5,2) NOT NULL,
  `prenom` varchar(40) NOT NULL,
  `revenu_fiscal` varchar(50) NOT NULL,
  `id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- DÃ©chargement des donnÃ©es de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `email`, `mot_de_passe`, `situation_professionnel`, `garant`, `retraite`, `caisse_allocation_familial`, `date_naissance`, `photo_profil`, `salaire_mensuel_net`, `prenom`, `revenu_fiscal`, `id_role`) VALUES
(1, 'Dupont', 'jean.dupont@email.com', 'motdepasse123', 'Administrateur', 0, 0.00, 0.00, '1985-03-15', 'photos/profil/jean_dupont.jpg', 999.99, 'Jean', '45000â‚¬', 1),
(2, 'Martin', 'sophie.martin@email.com', 'proprio123', 'PropriÃ©taire', 1, 999.99, 200.00, '1978-07-22', 'photos/profil/sophie_martin.jpg', 999.99, 'Sophie', '38000â‚¬', 2),
(3, 'Petit', 'thomas.petit@email.com', 'locataire123', 'Ã‰tudiant', 1, 0.00, 150.00, '1998-11-05', 'photos/profil/thomas_petit.jpg', 999.99, 'Thomas', '15000â‚¬', 3),
(4, 'Bernard', 'julie.bernard@email.com', 'locataire456', 'SalariÃ©', 0, 0.00, 0.00, '1995-04-18', 'photos/profil/julie_bernard.jpg', 999.99, 'Julie', '22000â‚¬', 3);

--
-- Index pour les tables dÃ©chargÃ©es
--

--
-- Index pour la table `age_recherche`
--
ALTER TABLE `age_recherche`
  ADD PRIMARY KEY (`id_age`);

--
-- Index pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD PRIMARY KEY (`id_annonce`);

--
-- Index pour la table `annonce_age`
--
ALTER TABLE `annonce_age`
  ADD PRIMARY KEY (`id_annonce`,`id_age`),
  ADD KEY `id_age` (`id_age`);

--
-- Index pour la table `annonce_avis`
--
ALTER TABLE `annonce_avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_annonce` (`id_annonce`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_utilisateur_1` (`id_utilisateur_1`);

--
-- Index pour la table `annonce_mode_vie`
--
ALTER TABLE `annonce_mode_vie`
  ADD PRIMARY KEY (`id_annonce`,`id_mode_vie`),
  ADD KEY `id_mode_vie` (`id_mode_vie`);

--
-- Index pour la table `annonce_photo`
--
ALTER TABLE `annonce_photo`
  ADD PRIMARY KEY (`id_annonce`,`id_photo`),
  ADD KEY `id_photo` (`id_photo`);

--
-- Index pour la table `annonce_regime_alimentaire`
--
ALTER TABLE `annonce_regime_alimentaire`
  ADD PRIMARY KEY (`id_annonce`,`id_regime_alimentaire`),
  ADD KEY `id_regime_alimentaire` (`id_regime_alimentaire`);

--
-- Index pour la table `annonce_utilisateur`
--
ALTER TABLE `annonce_utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`,`id_annonce`),
  ADD KEY `id_annonce` (`id_annonce`);

--
-- Index pour la table `envoi_message`
--
ALTER TABLE `envoi_message`
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_utilisateur_1` (`id_utilisateur_1`),
  ADD KEY `id_annonce` (`id_annonce`);

--
-- Index pour la table `mode_vie`
--
ALTER TABLE `mode_vie`
  ADD PRIMARY KEY (`id_mode_vie`);

--
-- Index pour la table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id_photo`);

--
-- Index pour la table `regime_alimentaire`
--
ALTER TABLE `regime_alimentaire`
  ADD PRIMARY KEY (`id_regime_alimentaire`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT pour les tables dÃ©chargÃ©es
--

--
-- AUTO_INCREMENT pour la table `age_recherche`
--
ALTER TABLE `age_recherche`
  MODIFY `id_age` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `annonce`
--
ALTER TABLE `annonce`
  MODIFY `id_annonce` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `annonce_avis`
--
ALTER TABLE `annonce_avis`
  MODIFY `id_avis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `mode_vie`
--
ALTER TABLE `mode_vie`
  MODIFY `id_mode_vie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `id_photo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `regime_alimentaire`
--
ALTER TABLE `regime_alimentaire`
  MODIFY `id_regime_alimentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables dÃ©chargÃ©es
--

--
-- Contraintes pour la table `annonce_age`
--
ALTER TABLE `annonce_age`
  ADD CONSTRAINT `annonce_age_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`),
  ADD CONSTRAINT `annonce_age_ibfk_2` FOREIGN KEY (`id_age`) REFERENCES `age_recherche` (`id_age`);

--
-- Contraintes pour la table `annonce_avis`
--
ALTER TABLE `annonce_avis`
  ADD CONSTRAINT `annonce_avis_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`),
  ADD CONSTRAINT `annonce_avis_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `annonce_avis_ibfk_3` FOREIGN KEY (`id_utilisateur_1`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `annonce_mode_vie`
--
ALTER TABLE `annonce_mode_vie`
  ADD CONSTRAINT `annonce_mode_vie_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`),
  ADD CONSTRAINT `annonce_mode_vie_ibfk_2` FOREIGN KEY (`id_mode_vie`) REFERENCES `mode_vie` (`id_mode_vie`);

--
-- Contraintes pour la table `annonce_photo`
--
ALTER TABLE `annonce_photo`
  ADD CONSTRAINT `annonce_photo_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`),
  ADD CONSTRAINT `annonce_photo_ibfk_2` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`);

--
-- Contraintes pour la table `annonce_regime_alimentaire`
--
ALTER TABLE `annonce_regime_alimentaire`
  ADD CONSTRAINT `annonce_regime_alimentaire_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`),
  ADD CONSTRAINT `annonce_regime_alimentaire_ibfk_2` FOREIGN KEY (`id_regime_alimentaire`) REFERENCES `regime_alimentaire` (`id_regime_alimentaire`);

--
-- Contraintes pour la table `annonce_utilisateur`
--
ALTER TABLE `annonce_utilisateur`
  ADD CONSTRAINT `annonce_utilisateur_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `annonce_utilisateur_ibfk_2` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`);

--
-- Contraintes pour la table `envoi_message`
--
ALTER TABLE `envoi_message`
  ADD CONSTRAINT `envoi_message_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `envoi_message_ibfk_2` FOREIGN KEY (`id_utilisateur_1`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `envoi_message_ibfk_3` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

