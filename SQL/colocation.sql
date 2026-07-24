
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 15, 2026 at 02:02 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `colocation`
--
CREATE database if not exists colocation;
use colocation;

-- --------------------------------------------------------

CREATE TABLE `age_recherche` (
  `id_age` int(11) NOT NULL,
  `tranche_age` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `age_recherche`
--

INSERT INTO `age_recherche` (`id_age`, `tranche_age`) VALUES
(1, '18-25'),
(2, '26-35'),
(3, '36-50'),
(4, '50-64'),
(5, '65+');

-- --------------------------------------------------------

--
-- Table structure for table `annonce`
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
-- Dumping data for table `annonce`
--

INSERT INTO `annonce` (`id_annonce`, `titre`, `adresse_1`, `adresse_2`, `adresse_3`, `adresse_4`, `ville`, `code_postal`, `loyer_location_chez_habitant`, `description`, `surface_logement`, `surface_chambres`, `nombre_chambre`, `date_expiration`, `date_publication`, `date_modification`, `carte_coordonnee_GPS`, `date_cloture`, `loyer_colocation`) VALUES
(1, 'Colocation étudiante lumineuse', '15 Rue de la République', 'Appartement 3B', NULL, NULL, 'Lyon', 69001, 650.00, 'Superbe appartement rénové proche université. Très lumineux avec balcon. Quartier calme et bien desservi par les transports.', 85.50, 12.50, 3, '2026-07-09', '2026-06-09', '2026-06-09', '45.764043, 4.835659', '2026-07-09', 550.00),
(2, 'Chambre cosy chez l\'habitant', '8 Avenue Victor Hugo', NULL, NULL, NULL, 'Paris', 75016, 800.00, 'Chambre meublée dans appartement spacieux. Propriétaire sympathique. Jardin partagé. Proche commerces et métro.', 120.00, 15.00, 1, '2026-05-25', '2026-04-25', '2026-06-09', '48.856614, 2.352222', '2026-06-08', 700.00),
(3, 'loulou', '40 RUE AMECON', 'UKML', 'NVK', '', 'Châtellerault', 86100, 560.00, 'Il s&#039;agit d&#039;un T3, salon non compris puisque c&#039;est une colocation. nous sommes a la recherche d&#039;un troisième colocataire.', 30.00, 18.00, 3, '2026-07-29', '2026-07-01', '2026-07-01', '', '2026-07-29', 560.00),
(4, 'Chambre lumineuse proche du centre', '24 Rue Marchande', 'Appartement 2A', NULL, NULL, 'Vienne', 38200, 420.00, 'Colocation calme avec fibre, balcon et espaces communs agreables.', 72.00, 18.00, 3, '2026-08-31', '2026-07-03', '2026-07-03', NULL, '2026-08-31', 420.00),
(5, 'Appartement partage ambiance conviviale', '8 Rue des Cedres', NULL, NULL, NULL, 'Estressin', 38200, 365.00, 'Logement agreable pour colocataires sociables, proche des transports.', 64.00, 14.00, 2, '2026-08-15', '2026-07-03', '2026-07-03', NULL, '2026-08-15', 365.00),
(6, 'Maison avec jardin', '11 Chemin du Jardin', NULL, NULL, 'Maison au calme', 'Pont-Eveque', 38780, 510.00, 'Colocation tranquille avec jardin, parking et ambiance familiale.', 110.00, 22.00, 4, '2026-09-01', '2026-07-03', '2026-07-03', NULL, '2026-09-01', 510.00),
(7, 'Studio partage proche de la gare', '3 Avenue de la Gare', 'Studio 4', NULL, NULL, 'Vienne', 38200, 590.00, 'Logement pratique pour actifs, avec acces rapide a la gare et commerces a proximite.', 42.00, 20.00, 1, '2026-08-20', '2026-07-03', '2026-07-03', NULL, '2026-08-20', 590.00),
(8, 'chambre en banlieu', '40 rue aime rasset', '56', '76', '', 'Chauvigny', 86100, 450.00, 'c&#039;est une chambre dans un appartement en banlieu', 999.99, 20.00, 50, '2026-07-29', '2026-07-03', '2026-07-03', '', '2026-07-29', 450.00),
(9, 'lala', '530 RUE AMECON', '98', '43', '', 'NaintrÃ©', 6974, 999.99, 'HIGIYFVK', 546.00, 66.00, 6, '2026-07-31', '2026-07-03', '2026-07-07', '', '2026-07-31', 999.99),
(11, 'Studio Etudiant', '5 Place Saint-Louis', '', '', '', 'Vienne', 38200, 350.00, 'Studio calme proche gare.', 25.00, 20.00, 1, '2026-07-26', '2026-07-16', '2026-07-16', '', '2026-08-01', 350.00),
(12, 'Chambre en Colocation', '24 Avenue Gambetta', '', '', '', 'Vienne', 38200, 400.00, 'Chambre meublée.', 80.00, 12.00, 3, '2026-07-14', '2026-07-16', '2026-07-16', '', '2026-08-01', 400.00);

-- --------------------------------------------------------

--
-- Table structure for table `annonce_age`
--

CREATE TABLE `annonce_age` (
  `id_annonce` int(11) NOT NULL,
  `id_age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `annonce_age`
--

INSERT INTO `annonce_age` (`id_annonce`, `id_age`) VALUES
(1, 1),
(1, 2),
(2, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `annonce_avis`
--

CREATE TABLE `annonce_avis` (
  `id_avis` int(11) NOT NULL,
  `note` decimal(3,2) DEFAULT NULL,
  `date_` date NOT NULL,
  `commentaire` text NOT NULL,
  `id_annonce` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_utilisateur_1` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `annonce_avis`
--

INSERT INTO `annonce_avis` (`id_avis`, `note`, `date_`, `commentaire`, `id_annonce`, `id_utilisateur`, `id_utilisateur_1`) VALUES
(1, 0.99, '2026-05-30', 'Très bonne colocation ! L\'appartement est propre et bien situé. Les colocataires sont sympathiques. Je recommande.', 1, 3, 2),
(2, 0.99, '2026-06-04', 'Annonce correcte mais le propriétaire est un peu strict sur les règles. La chambre est petite mais fonctionnelle.', 2, 4, 2),
(3, 3.00, '2026-07-13', 'huu', 11, 7, 7),
(4, 5.00, '2026-07-13', 'cesr', 11, 7, 7),
(5, 5.00, '2026-07-13', 'hhhh', 11, 7, 7);

-- --------------------------------------------------------

--
-- Table structure for table `annonce_mode_vie`
--

CREATE TABLE `annonce_mode_vie` (
  `id_annonce` int(11) NOT NULL,
  `id_mode_vie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `annonce_mode_vie`
--

INSERT INTO `annonce_mode_vie` (`id_annonce`, `id_mode_vie`) VALUES
(1, 1),
(1, 3),
(2, 4),
(2, 5),
(4, 1),
(4, 5),
(5, 2),
(5, 5),
(6, 4),
(6, 7),
(7, 5),
(8, 1),
(8, 5),
(8, 7),
(9, 1),
(9, 7);

-- --------------------------------------------------------

--
-- Table structure for table `annonce_photo`
--

CREATE TABLE `annonce_photo` (
  `id_annonce` int(11) NOT NULL,
  `id_photo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `annonce_photo`
--

INSERT INTO `annonce_photo` (`id_annonce`, `id_photo`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 5),
(2, 6),
(2, 7),
(4, 8),
(5, 9),
(6, 10),
(7, 11),
(12, 15),
(12, 16);

-- --------------------------------------------------------

--
-- Table structure for table `annonce_regime_alimentaire`
--

CREATE TABLE `annonce_regime_alimentaire` (
  `id_annonce` int(11) NOT NULL,
  `id_regime_alimentaire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `annonce_regime_alimentaire`
--

INSERT INTO `annonce_regime_alimentaire` (`id_annonce`, `id_regime_alimentaire`) VALUES
(1, 1),
(1, 2),
(4, 1),
(4, 2),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 5);

-- --------------------------------------------------------

--
-- Table structure for table `annonce_utilisateur`
--

CREATE TABLE `annonce_utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `id_annonce` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `annonce_utilisateur`
--

INSERT INTO `annonce_utilisateur` (`id_utilisateur`, `id_annonce`) VALUES
(1, 4),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(2, 1),
(2, 2),
(3, 5),
(7, 11),
(7, 12);

-- --------------------------------------------------------

--
-- Table structure for table `envoi_message`
--

CREATE TABLE `envoi_message` (
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_utilisateur_1` int(11) DEFAULT NULL,
  `id_annonce` int(11) DEFAULT NULL,
  `date_` date NOT NULL,
  `contenu` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `envoi_message`
--

INSERT INTO `envoi_message` (`id_utilisateur`, `id_utilisateur_1`, `id_annonce`, `date_`, `contenu`) VALUES
(3, 4, 1, '2026-06-02', 'Salut Julie, j\'ai vu que tu cherchais une colocation. Cette annonce à Lyon est vraiment bien ! Qu\'en penses-tu ?'),
(4, 3, 1, '2026-06-03', 'Merci Thomas ! Oui elle a l\'air super. Je vais contacter la propriétaire pour visiter cette semaine.'),
(3, 4, 1, '2026-06-04', 'Super ! N\'hésite pas si tu as besoin d\'infos, j\'ai déjà visité l\'appartement.'),
(3, 2, 1, '2026-07-08', 'Bonjour, je suis très intéressé par votre colocation !'),
(2, 7, 11, '2026-07-03', 'Bonjour, est-ce que les charges sont incluses ?'),
(7, 2, 11, '2026-07-03', 'Bonjour Sophie, oui tout est inclus.'),
(2, 7, 11, '2026-07-03', 'Parfait, merci !'),
(3, 7, 12, '2026-07-04', 'Salut, la chambre est-elle meublée ?'),
(7, 3, 12, '2026-07-04', 'Salut Thomas, oui : lit, bureau et placard.'),
(3, 7, 12, '2026-07-05', 'Génial, merci !'),
(3, 2, 1, '2026-07-08', 'hi'),
(3, 7, 12, '2026-07-08', 'hi'),
(7, 3, 12, '2026-07-08', 'how are you doing'),
(3, 7, 12, '2026-07-08', 'i am good thanks'),
(7, 7, 12, '2026-07-13', 'hi'),
(7, 7, 11, '2026-07-13', 'hi'),
(7, 7, 12, '2026-07-13', 'hu');

-- --------------------------------------------------------

--
-- Table structure for table `mode_vie`
--

CREATE TABLE `mode_vie` (
  `id_mode_vie` int(11) NOT NULL,
  `mode_avis` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mode_vie`
--

INSERT INTO `mode_vie` (`id_mode_vie`, `mode_avis`) VALUES
(1, 'Calme'),
(2, 'Festif'),
(3, 'Étudiant'),
(4, 'Famille'),
(5, 'Travailleur'),
(6, 'Retraite'),
(7, 'Parent solo');

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `id_photo` int(11) NOT NULL,
  `url` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`id_photo`, `url`) VALUES
(1, 'photos/annonce12/6a552320dce4c.jpg'),
(2, 'photos/annonce12/6a552320dce4c.jpg'),
(3, 'photos/annonce12/6a552320dce4c.jpg'),
(4, 'photos/annonce12/6a552320dce4c.jpg'),
(5, 'photos/annonce12/6a552320dce4c.jpg'),
(6, 'photos/annonce12/6a552320dce4c.jpg'),
(7, 'photos/annonce12/6a552320dce4c.jpg'),
(8, 'photos/annonce12/6a552320dce4c.jpg'),
(9, 'photos/annonce12/6a552320dce4c.jpg'),
(10, 'photos/annonce12/6a552320dce4c.jpg'),
(11, 'photos/annonce12/6a552320dce4c.jpg'),
(15, 'photos/annonce12/6a5666db6c869.jpg'),
(16, 'photos/annonce12/6a5667a5557e1.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `regime_alimentaire`
--

CREATE TABLE `regime_alimentaire` (
  `id_regime_alimentaire` int(11) NOT NULL,
  `regime_alimentaire` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regime_alimentaire`
--

INSERT INTO `regime_alimentaire` (`id_regime_alimentaire`, `regime_alimentaire`) VALUES
(1, 'Omnivore'),
(2, 'Végétarien'),
(3, 'Végan'),
(4, 'Sans gluten'),
(5, 'Halal'),
(6, 'Cacher');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id_role`, `role`) VALUES
(1, 'Admin'),
(2, 'Propriétaire'),
(3, 'Locataire');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
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
  `id_role` int(11) NOT NULL,
  `type_compte` enum('colocataire','propriétaire') DEFAULT 'colocataire'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `email`, `mot_de_passe`, `situation_professionnel`, `garant`, `retraite`, `caisse_allocation_familial`, `date_naissance`, `photo_profil`, `salaire_mensuel_net`, `prenom`, `revenu_fiscal`, `id_role`, `type_compte`) VALUES
(1, 'Dupont', 'jean.dupont@email.com', '$2y$10$jPyT2RHjQDbVPRzs7nNW2OhS9N9Wf9Nocvmtxmo23cfs04fVApPNi', 'Administrateur', 1, 0.00, 0.00, '1985-03-15', 'uploads/profile_photos/user_1_1783414945.jpg', 999.99, 'Jean', '45000', 1, 'colocataire'),
(2, 'Martin', 'sophie.martin@email.com', 'proprio123', 'Propriétaire', 1, 999.99, 200.00, '1978-07-22', 'photos/profil/sophie_martin.jpg', 999.99, 'Sophie', '38000€', 2, 'colocataire'),
(3, 'Petit', 'thomas.petit@email.com', '$2y$10$C7JeXhHsvdPHc9PF9a0P3eLFEc1BCF0SE1LJe5atLTzgmXRmgYosa', 'Étudiant', 1, 0.00, 150.00, '1998-11-05', 'photos/profil/thomas_petit.jpg', 999.99, 'Thomas', '15000', 3, 'colocataire'),
(4, 'Bernard', 'julie.bernard@email.com', '$2y$10$NHG15nBQizpCPewNnqZfJONkW8ZmFINwJuoFWzKGBR9YHUr6lHUDu', 'Salarié', 0, 0.00, 0.00, '1995-04-18', 'photos/profil/julie_bernard.jpg', 999.99, 'Julie', '22000', 3, 'colocataire'),
(7, 'test', 'test@test.com', '$2y$10$FDJ.nEIAp9SNMuCtWd0jGeMWTpAzjA6DyOfREpWvSlnJ7flDGNY8O', 'Étudiant', 1, 0.00, 0.00, '2026-07-01', '', 500.00, 'test', '888', 1, 'colocataire'),
(8, 'non', 'non@non.no', '$2y$10$037.PyC14.MKHB1YCxgHZ.c2qdmhYHghRh5Lc3QPl0Uhac5xcjNVO', 'Étudiant', 1, 0.00, 0.00, '2026-07-02', '', 233.00, 'non', '3333', 3, 'colocataire'),
(9, 'safi', 'sroheed@gmail.com', '$2y$10$3/DCjH0CJMtItkcDFU/R9O2UC49BNBDzzxvrTu/pFga/6MULuYK3C', 'Salarié', 0, 0.00, 0.00, '2026-07-02', 'uploads/profile_photos/user_9_1783514015.jpg', 112.00, 'rohid', '888', 3, 'colocataire');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `age_recherche`
--
ALTER TABLE `age_recherche`
  ADD PRIMARY KEY (`id_age`);

--
-- Indexes for table `annonce`
--
ALTER TABLE `annonce`
  ADD PRIMARY KEY (`id_annonce`);

--
-- Indexes for table `annonce_age`
--
ALTER TABLE `annonce_age`
  ADD PRIMARY KEY (`id_annonce`,`id_age`),
  ADD KEY `id_age` (`id_age`);

--
-- Indexes for table `annonce_avis`
--
ALTER TABLE `annonce_avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_annonce` (`id_annonce`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_utilisateur_1` (`id_utilisateur_1`);

--
-- Indexes for table `annonce_mode_vie`
--
ALTER TABLE `annonce_mode_vie`
  ADD PRIMARY KEY (`id_annonce`,`id_mode_vie`),
  ADD KEY `id_mode_vie` (`id_mode_vie`);

--
-- Indexes for table `annonce_photo`
--
ALTER TABLE `annonce_photo`
  ADD PRIMARY KEY (`id_annonce`,`id_photo`),
  ADD KEY `id_photo` (`id_photo`);

--
-- Indexes for table `annonce_regime_alimentaire`
--
ALTER TABLE `annonce_regime_alimentaire`
  ADD PRIMARY KEY (`id_annonce`,`id_regime_alimentaire`),
  ADD KEY `id_regime_alimentaire` (`id_regime_alimentaire`);

--
-- Indexes for table `annonce_utilisateur`
--
ALTER TABLE `annonce_utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`,`id_annonce`),
  ADD KEY `id_annonce` (`id_annonce`);

--
-- Indexes for table `envoi_message`
--
ALTER TABLE `envoi_message`
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_utilisateur_1` (`id_utilisateur_1`),
  ADD KEY `id_annonce` (`id_annonce`);

--
-- Indexes for table `mode_vie`
--
ALTER TABLE `mode_vie`
  ADD PRIMARY KEY (`id_mode_vie`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id_photo`);

--
-- Indexes for table `regime_alimentaire`
--
ALTER TABLE `regime_alimentaire`
  ADD PRIMARY KEY (`id_regime_alimentaire`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `age_recherche`
--
ALTER TABLE `age_recherche`
  MODIFY `id_age` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `annonce`
--
ALTER TABLE `annonce`
  MODIFY `id_annonce` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `annonce_avis`
--
ALTER TABLE `annonce_avis`
  MODIFY `id_avis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mode_vie`
--
ALTER TABLE `mode_vie`
  MODIFY `id_mode_vie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `id_photo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `regime_alimentaire`
--
ALTER TABLE `regime_alimentaire`
  MODIFY `id_regime_alimentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `annonce_age`
--
ALTER TABLE `annonce_age`
  ADD CONSTRAINT `annonce_age_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`),
  ADD CONSTRAINT `annonce_age_ibfk_2` FOREIGN KEY (`id_age`) REFERENCES `age_recherche` (`id_age`);

--
-- Constraints for table `annonce_avis`
--
ALTER TABLE `annonce_avis`
  ADD CONSTRAINT `annonce_avis_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`),
  ADD CONSTRAINT `annonce_avis_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `annonce_avis_ibfk_3` FOREIGN KEY (`id_utilisateur_1`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Constraints for table `annonce_mode_vie`
--
ALTER TABLE `annonce_mode_vie`
  ADD CONSTRAINT `annonce_mode_vie_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`),
  ADD CONSTRAINT `annonce_mode_vie_ibfk_2` FOREIGN KEY (`id_mode_vie`) REFERENCES `mode_vie` (`id_mode_vie`);

--
-- Constraints for table `annonce_photo`
--
ALTER TABLE `annonce_photo`
  ADD CONSTRAINT `annonce_photo_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`),
  ADD CONSTRAINT `annonce_photo_ibfk_2` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`);

--
-- Constraints for table `annonce_regime_alimentaire`
--
ALTER TABLE `annonce_regime_alimentaire`
  ADD CONSTRAINT `annonce_regime_alimentaire_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`),
  ADD CONSTRAINT `annonce_regime_alimentaire_ibfk_2` FOREIGN KEY (`id_regime_alimentaire`) REFERENCES `regime_alimentaire` (`id_regime_alimentaire`);

--
-- Constraints for table `annonce_utilisateur`
--
ALTER TABLE `annonce_utilisateur`
  ADD CONSTRAINT `annonce_utilisateur_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `annonce_utilisateur_ibfk_2` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`);

--
-- Constraints for table `envoi_message`
--
ALTER TABLE `envoi_message`
  ADD CONSTRAINT `envoi_message_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `envoi_message_ibfk_2` FOREIGN KEY (`id_utilisateur_1`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `envoi_message_ibfk_3` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`);

--
-- Constraints for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;