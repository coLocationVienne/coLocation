-- SQL SCRIPT CRÉS PAR ROHID SAFI , ISSINTIA ET LAWRANCE

DROP DATABASE IF EXISTS coLocation;
CREATE DATABASE IF NOT EXISTS coLocation;
USE coLocation;

CREATE TABLE ROLE(
   id_role INT AUTO_INCREMENT,
   role VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_role)
);

CREATE TABLE ANNONCE(
   id_annonce INT AUTO_INCREMENT,
   titre VARCHAR(50) NOT NULL,
   adresse_1 VARCHAR(255) NOT NULL,
   adresse_2 VARCHAR(255),
   ville VARCHAR(50) NOT NULL,
   code_postal VARCHAR(10) NOT NULL,
   loyer_location_chez_habitant DECIMAL(10,2) NOT NULL,
   description TEXT NOT NULL,
   surface_logement DECIMAL(10,2) NOT NULL,
   surface_chambres DECIMAL(10,2) NOT NULL,
   nombre_chambre TINYINT NOT NULL,
   date_expiration DATE,
   date_publication DATE NOT NULL,
   date_modification DATE NOT NULL,
   carte_coordonnee_GPS VARCHAR(40),
   date_cloture DATE NOT NULL,
   loyer_colocation DECIMAL(10,2) NOT NULL,
   est_fumeur BOOLEAN NOT NULL,
   a_enfant BOOLEAN NOT NULL,
   a_animaux BOOLEAN NOT NULL,
   PRIMARY KEY(id_annonce)
);

CREATE TABLE PHOTO(
   id_photo INT AUTO_INCREMENT,
   url VARCHAR(250) NOT NULL,
   PRIMARY KEY(id_photo)
);

CREATE TABLE REGIME_ALIMENTAIRE(
   id_regime_alimentaire INT AUTO_INCREMENT,
   regime_alimentaire VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_regime_alimentaire)
);

CREATE TABLE MODE_VIE(
   id_mode_vie INT AUTO_INCREMENT,
   mode_avis VARCHAR(50),
   PRIMARY KEY(id_mode_vie)
);

CREATE TABLE AGE_RECHERCHE(
   id_age INT AUTO_INCREMENT,
   tranche_age VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_age)
);

CREATE TABLE UTILISATEUR(
   id_utilisateur INT AUTO_INCREMENT,
   nom VARCHAR(40) NOT NULL,
   email VARCHAR(100) NOT NULL,
   mot_de_passe VARCHAR(100) NOT NULL,
   situation_professionnel VARCHAR(40) NOT NULL,
   garant BOOLEAN NOT NULL,
   retraite DECIMAL(10,2) NOT NULL,
   caisse_allocation_familial DECIMAL(10,2) NOT NULL,
   date_naissance DATE NOT NULL,
   photo_profil VARCHAR(250) NOT NULL,
   salaire_mensuel_net DECIMAL(10,2) NOT NULL,
   prenom VARCHAR(40) NOT NULL,
   revenu_fiscal VARCHAR(50) NOT NULL,
   id_role INT NOT NULL,
   PRIMARY KEY(id_utilisateur),
   FOREIGN KEY(id_role) REFERENCES ROLE(id_role) ON DELETE RESTRICT
);

CREATE TABLE AVIS(
   id_avis INT AUTO_INCREMENT,
   note DECIMAL(3,2),
   date_ DATE NOT NULL,
   commentaire TEXT NOT NULL,
   id_annonce INT,
   id_utilisateur INT NOT NULL,
   id_utilisateur_1 INT NOT NULL,
   PRIMARY KEY(id_avis),
   FOREIGN KEY(id_annonce) REFERENCES ANNONCE(id_annonce) ON DELETE SET NULL,
   FOREIGN KEY(id_utilisateur) REFERENCES UTILISATEUR(id_utilisateur) ON DELETE CASCADE,
   FOREIGN KEY(id_utilisateur_1) REFERENCES UTILISATEUR(id_utilisateur) ON DELETE CASCADE
);

CREATE TABLE REDIGER_ANNONCE(
   id_utilisateur INT,
   id_annonce INT,
   PRIMARY KEY(id_utilisateur, id_annonce),
   FOREIGN KEY(id_utilisateur) REFERENCES UTILISATEUR(id_utilisateur) ON DELETE CASCADE,
   FOREIGN KEY(id_annonce) REFERENCES ANNONCE(id_annonce) ON DELETE CASCADE
);

CREATE TABLE PRESENTER_LOGEMENT(
   id_annonce INT,
   id_photo INT,
   PRIMARY KEY(id_annonce, id_photo),
   FOREIGN KEY(id_annonce) REFERENCES ANNONCE(id_annonce) ON DELETE CASCADE,
   FOREIGN KEY(id_photo) REFERENCES PHOTO(id_photo) ON DELETE CASCADE
);

CREATE TABLE ENVOI_MESSAGE(
   id_utilisateur INT,
   id_utilisateur_1 INT,
   id_annonce INT,
   date_ DATE NOT NULL,
   contenu VARCHAR(250) NOT NULL,
   PRIMARY KEY(id_utilisateur, id_utilisateur_1, id_annonce, date_),
   FOREIGN KEY(id_utilisateur) REFERENCES UTILISATEUR(id_utilisateur) ON DELETE CASCADE,
   FOREIGN KEY(id_utilisateur_1) REFERENCES UTILISATEUR(id_utilisateur) ON DELETE CASCADE,
   FOREIGN KEY(id_annonce) REFERENCES ANNONCE(id_annonce) ON DELETE CASCADE
);

CREATE TABLE AVOIR(
   id_annonce INT,
   id_regime_alimentaire INT,
   PRIMARY KEY(id_annonce, id_regime_alimentaire),
   FOREIGN KEY(id_annonce) REFERENCES ANNONCE(id_annonce) ON DELETE CASCADE,
   FOREIGN KEY(id_regime_alimentaire) REFERENCES REGIME_ALIMENTAIRE(id_regime_alimentaire) ON DELETE CASCADE
);

CREATE TABLE ANNONCE_MODE_VIE(
   id_annonce INT,
   id_mode_vie INT,
   PRIMARY KEY(id_annonce, id_mode_vie),
   FOREIGN KEY(id_annonce) REFERENCES ANNONCE(id_annonce) ON DELETE CASCADE,
   FOREIGN KEY(id_mode_vie) REFERENCES MODE_VIE(id_mode_vie) ON DELETE CASCADE
);

CREATE TABLE ANNONCE_AGE(
   id_annonce INT,
   id_age INT,
   PRIMARY KEY(id_annonce, id_age),
   FOREIGN KEY(id_annonce) REFERENCES ANNONCE(id_annonce) ON DELETE CASCADE,
   FOREIGN KEY(id_age) REFERENCES AGE_RECHERCHE(id_age) ON DELETE CASCADE
);

INSERT INTO ROLE (role) VALUES ('Admin'), ('Propriétaire'), ('Locataire');

INSERT INTO REGIME_ALIMENTAIRE (regime_alimentaire) VALUES 
('Omnivore'), ('Végétarien'), ('Végan'), ('Sans gluten'), ('Halal'), ('Cacher');

INSERT INTO MODE_VIE (mode_avis) VALUES 
('Calme'), ('Festif'), ('Étudiant'), ('Famille'), ('Travailleur');

INSERT INTO AGE_RECHERCHE (tranche_age) VALUES 
('18-25'), ('26-35'), ('36-50'), ('50+');



-- Insert Users
-- Admin User
INSERT INTO UTILISATEUR (
    nom, prenom, email, mot_de_passe, situation_professionnel, 
    garant, retraite, caisse_allocation_familial, date_naissance, 
    photo_profil, salaire_mensuel_net, revenu_fiscal, id_role
) VALUES (
    'Dupont', 'Jean', 'jean.dupont@email.com', 'motdepasse123', 'Administrateur',
    0, 0.00, 0.00, '1985-03-15',
    'photos/profil/jean_dupont.jpg', 4500.00, '45000€', 1
);


INSERT INTO UTILISATEUR (
    nom, prenom, email, mot_de_passe, situation_professionnel, 
    garant, retraite, caisse_allocation_familial, date_naissance, 
    photo_profil, salaire_mensuel_net, revenu_fiscal, id_role
) VALUES (
    'Martin', 'Sophie', 'sophie.martin@email.com', 'proprio123', 'Propriétaire',
    1, 1200.00, 200.00, '1978-07-22',
    'photos/profil/sophie_martin.jpg', 3800.00, '38000€', 2
);

INSERT INTO UTILISATEUR (
    nom, prenom, email, mot_de_passe, situation_professionnel, 
    garant, retraite, caisse_allocation_familial, date_naissance, 
    photo_profil, salaire_mensuel_net, revenu_fiscal, id_role
) VALUES (
    'Petit', 'Thomas', 'thomas.petit@email.com', 'locataire123', 'Étudiant',
    1, 0.00, 150.00, '1998-11-05',
    'photos/profil/thomas_petit.jpg', 1500.00, '15000€', 3
);

INSERT INTO UTILISATEUR (
    nom, prenom, email, mot_de_passe, situation_professionnel, 
    garant, retraite, caisse_allocation_familial, date_naissance, 
    photo_profil, salaire_mensuel_net, revenu_fiscal, id_role
) VALUES (
    'Bernard', 'Julie', 'julie.bernard@email.com', 'locataire456', 'Salarié',
    0, 0.00, 0.00, '1995-04-18',
    'photos/profil/julie_bernard.jpg', 2200.00, '22000€', 3
);

INSERT INTO ANNONCE (
    titre, adresse_1, adresse_2, ville, code_postal, 
    loyer_location_chez_habitant, description, surface_logement, 
    surface_chambres, nombre_chambre, date_expiration, 
    date_publication, date_modification, carte_coordonnee_GPS, 
    date_cloture, loyer_colocation, est_fumeur, a_enfant, a_animaux
) VALUES (
    'Colocation étudiante lumineuse', '15 Rue de la République', 'Appartement 3B', 'Lyon', '69001',
    650.00, 'Superbe appartement rénové proche université. Très lumineux avec balcon. Quartier calme et bien desservi par les transports.',
    85.50, 12.50, 3, DATE_ADD(CURDATE(), INTERVAL 30 DAY),
    CURDATE(), CURDATE(), '45.764043, 4.835659',
    DATE_ADD(CURDATE(), INTERVAL 30 DAY), 550.00, 0, 0, 0
);

INSERT INTO ANNONCE (
    titre, adresse_1, adresse_2, ville, code_postal, 
    loyer_location_chez_habitant, description, surface_logement, 
    surface_chambres, nombre_chambre, date_expiration, 
    date_publication, date_modification, carte_coordonnee_GPS, 
    date_cloture, loyer_colocation, est_fumeur, a_enfant, a_animaux
) VALUES (
    'Chambre cosy chez l''habitant', '8 Avenue Victor Hugo', NULL, 'Paris', '75016',
    800.00, 'Chambre meublée dans appartement spacieux. Propriétaire sympathique. Jardin partagé. Proche commerces et métro.',
    120.00, 15.00, 1, DATE_SUB(CURDATE(), INTERVAL 15 DAY),
    DATE_SUB(CURDATE(), INTERVAL 45 DAY), CURDATE(), '48.856614, 2.352222',
    DATE_SUB(CURDATE(), INTERVAL 1 DAY), 700.00, 1, 0, 1
);


INSERT INTO REDIGER_ANNONCE (id_utilisateur, id_annonce) VALUES (2, 1);

INSERT INTO REDIGER_ANNONCE (id_utilisateur, id_annonce) VALUES (2, 2);

INSERT INTO PHOTO (url) VALUES 
('photos/annonce1/salon.jpg'),
('photos/annonce1/chambre1.jpg'),
('photos/annonce1/cuisine.jpg'),
('photos/annonce1/salle_de_bain.jpg');


INSERT INTO PHOTO (url) VALUES 
('photos/annonce2/chambre.jpg'),
('photos/annonce2/salon.jpg'),
('photos/annonce2/jardin.jpg');


INSERT INTO PRESENTER_LOGEMENT (id_annonce, id_photo) VALUES 
(1, 1), (1, 2), (1, 3), (1, 4),
(2, 5), (2, 6), (2, 7);


INSERT INTO ANNONCE_MODE_VIE (id_annonce, id_mode_vie) VALUES 
(1, 1),  
(1, 3); 

INSERT INTO AVOIR (id_annonce, id_regime_alimentaire) VALUES 
(1, 1), 
(1, 2); 

INSERT INTO ANNONCE_AGE (id_annonce, id_age) VALUES 
(1, 1),  
(1, 2);  

INSERT INTO ANNONCE_MODE_VIE (id_annonce, id_mode_vie) VALUES 
(2, 4), 
(2, 5);  

INSERT INTO AVOIR (id_annonce, id_regime_alimentaire) VALUES 
(2, 1),  
(2, 4);  

INSERT INTO ANNONCE_AGE (id_annonce, id_age) VALUES 
(2, 2),  
(2, 3);  



INSERT INTO AVIS (
    note, date_, commentaire, id_annonce, id_utilisateur, id_utilisateur_1
) VALUES (
    4.5, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 
    'Très bonne colocation ! L''appartement est propre et bien situé. Les colocataires sont sympathiques. Je recommande.',
    1, 3, 2
);

INSERT INTO AVIS (
    note, date_, commentaire, id_annonce, id_utilisateur, id_utilisateur_1
) VALUES (
    3.0, DATE_SUB(CURDATE(), INTERVAL 5 DAY),
    'Annonce correcte mais le propriétaire est un peu strict sur les règles. La chambre est petite mais fonctionnelle.',
    2, 4, 2
);

INSERT INTO ENVOI_MESSAGE (id_utilisateur, id_utilisateur_1, id_annonce, date_, contenu) VALUES
(3, 4, 1, DATE_SUB(CURDATE(), INTERVAL 7 DAY), 'Salut Julie, j''ai vu que tu cherchais une colocation. Cette annonce à Lyon est vraiment bien ! Qu''en penses-tu ?'),
(4, 3, 1, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Merci Thomas ! Oui elle a l''air super. Je vais contacter la propriétaire pour visiter cette semaine.'),
(3, 4, 1, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Super ! N''hésite pas si tu as besoin d''infos, j''ai déjà visité l''appartement.');


-- Rohid SAfiL: test script select all data from tables to verify inserts
/* Test script to verify data insertion s
SELECT '=== USERS ===' AS '';
SELECT id_utilisateur, nom, prenom, email, (SELECT role FROM ROLE WHERE id_role = U.id_role) AS role 
FROM UTILISATEUR U;

SELECT '\n=== ANNONCES ===' AS '';
SELECT id_annonce, titre, ville, date_publication, date_cloture, date_expiration 
FROM ANNONCE;

SELECT '\n=== AVIS ===' AS '';
SELECT A.id_avis, A.note, A.commentaire, CONCAT(U.nom, ' ', U.prenom) AS auteur, 
       CASE WHEN A.id_utilisateur_1 != A.id_utilisateur THEN 'Avec user' ELSE 'Sans user' END AS type_avis
FROM AVIS A
LEFT JOIN UTILISATEUR U ON A.id_utilisateur = U.id_utilisateur;

SELECT '\n=== MESSAGES ===' AS '';
SELECT CONCAT(U1.nom, ' ', U1.prenom) AS expediteur, 
       CONCAT(U2.nom, ' ', U2.prenom) AS destinataire,
       M.contenu, M.date_
FROM ENVOI_MESSAGE M
JOIN UTILISATEUR U1 ON M.id_utilisateur = U1.id_utilisateur
JOIN UTILISATEUR U2 ON M.id_utilisateur_1 = U2.id_utilisateur;
*/