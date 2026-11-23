-- Photos d'illustration des annonces de demonstration uniquement.
-- Sources et licence : photos/demo-appartements/CREDITS.txt
-- Les fichiers d'origine sont conserves sur disque.
START TRANSACTION;
UPDATE photo SET url = 'photos/demo-appartements/deborah-cortelazzi-gREquCUXQLI.jpg' WHERE id_photo = 1 AND url = 'photos/annonce12/6a552320dce4c.jpg';
UPDATE photo SET url = 'photos/demo-appartements/patrick-perkins-3wylDrjxH-E.jpg' WHERE id_photo = 2 AND url = 'photos/annonce12/6a552320dce4c.jpg';
UPDATE photo SET url = 'photos/demo-appartements/naomi-hebert-MP0bgaS_d1c.jpg' WHERE id_photo = 3 AND url = 'photos/annonce12/6a552320dce4c.jpg';
UPDATE photo SET url = 'photos/demo-appartements/frames-for-your-heart-FqqiAvJejto.jpg' WHERE id_photo = 4 AND url = 'photos/annonce12/6a552320dce4c.jpg';
UPDATE photo SET url = 'photos/demo-appartements/frames-for-your-heart-FqqiAvJejto.jpg' WHERE id_photo = 5 AND url = 'photos/annonce12/6a552320dce4c.jpg';
UPDATE photo SET url = 'photos/demo-appartements/minh-pham-OtXADkUh3-I.jpg' WHERE id_photo = 6 AND url = 'photos/annonce12/6a552320dce4c.jpg';
UPDATE photo SET url = 'photos/demo-appartements/naomi-hebert-MP0bgaS_d1c.jpg' WHERE id_photo = 7 AND url = 'photos/annonce12/6a552320dce4c.jpg';
UPDATE photo SET url = 'photos/demo-appartements/patrick-perkins-3wylDrjxH-E.jpg' WHERE id_photo = 8 AND url = 'photos/annonce12/6a552320dce4c.jpg';
UPDATE photo SET url = 'photos/demo-appartements/minh-pham-OtXADkUh3-I.jpg' WHERE id_photo = 9 AND url = 'photos/annonce12/6a552320dce4c.jpg';
UPDATE photo SET url = 'photos/demo-appartements/francesca-tosolini-tHkJAMcO3QE.jpg' WHERE id_photo = 10 AND url = 'photos/annonce12/6a552320dce4c.jpg';
UPDATE photo SET url = 'photos/demo-appartements/deborah-cortelazzi-gREquCUXQLI.jpg' WHERE id_photo = 11 AND url = 'photos/annonce12/6a552320dce4c.jpg';
UPDATE photo SET url = 'photos/demo-appartements/frames-for-your-heart-FqqiAvJejto.jpg' WHERE id_photo = 15 AND url = 'photos/annonce12/6a5666db6c869.jpg';
UPDATE photo SET url = 'photos/demo-appartements/naomi-hebert-MP0bgaS_d1c.jpg' WHERE id_photo = 16 AND url = 'photos/annonce12/6a5667a5557e1.jpeg';
UPDATE photo SET url = 'photos/demo-appartements/patrick-perkins-3wylDrjxH-E.jpg' WHERE id_photo = 18 AND url = 'photos/annonce_6a61b3e583d97.jpg';
UPDATE photo SET url = 'photos/demo-appartements/minh-pham-OtXADkUh3-I.jpg' WHERE id_photo = 19 AND url = 'photos/annonce_6a61b3f484482.jpg';
UPDATE photo SET url = 'photos/demo-appartements/francesca-tosolini-tHkJAMcO3QE.jpg' WHERE id_photo = 20 AND url = 'photos/annonce_6a6211ae1abe3.jpg';
UPDATE photo SET url = 'photos/demo-appartements/roberto-nickson-rEJxpBskj3Q.jpg' WHERE id_photo = 22 AND url = 'photos/annonce_6b044ba852593.PNG';

INSERT INTO photo (url)
SELECT 'photos/demo-appartements/kam-idris-_HqHX3LBN18.jpg' FROM annonce a
WHERE a.id_annonce = 3 AND NOT EXISTS (SELECT 1 FROM annonce_photo ap WHERE ap.id_annonce = a.id_annonce)
AND NOT EXISTS (SELECT 1 FROM photo p WHERE p.url = 'photos/demo-appartements/kam-idris-_HqHX3LBN18.jpg');
INSERT INTO annonce_photo (id_annonce, id_photo)
SELECT a.id_annonce, p.id_photo FROM annonce a JOIN photo p ON p.url = 'photos/demo-appartements/kam-idris-_HqHX3LBN18.jpg'
WHERE a.id_annonce = 3 AND NOT EXISTS (SELECT 1 FROM annonce_photo ap WHERE ap.id_annonce = a.id_annonce)
ORDER BY p.id_photo LIMIT 1;

INSERT INTO photo (url)
SELECT 'photos/demo-appartements/patrick-perkins-3wylDrjxH-E.jpg' FROM annonce a
WHERE a.id_annonce = 8 AND NOT EXISTS (SELECT 1 FROM annonce_photo ap WHERE ap.id_annonce = a.id_annonce)
AND NOT EXISTS (SELECT 1 FROM photo p WHERE p.url = 'photos/demo-appartements/patrick-perkins-3wylDrjxH-E.jpg');
INSERT INTO annonce_photo (id_annonce, id_photo)
SELECT a.id_annonce, p.id_photo FROM annonce a JOIN photo p ON p.url = 'photos/demo-appartements/patrick-perkins-3wylDrjxH-E.jpg'
WHERE a.id_annonce = 8 AND NOT EXISTS (SELECT 1 FROM annonce_photo ap WHERE ap.id_annonce = a.id_annonce)
ORDER BY p.id_photo LIMIT 1;

INSERT INTO photo (url)
SELECT 'photos/demo-appartements/naomi-hebert-MP0bgaS_d1c.jpg' FROM annonce a
WHERE a.id_annonce = 9 AND NOT EXISTS (SELECT 1 FROM annonce_photo ap WHERE ap.id_annonce = a.id_annonce)
AND NOT EXISTS (SELECT 1 FROM photo p WHERE p.url = 'photos/demo-appartements/naomi-hebert-MP0bgaS_d1c.jpg');
INSERT INTO annonce_photo (id_annonce, id_photo)
SELECT a.id_annonce, p.id_photo FROM annonce a JOIN photo p ON p.url = 'photos/demo-appartements/naomi-hebert-MP0bgaS_d1c.jpg'
WHERE a.id_annonce = 9 AND NOT EXISTS (SELECT 1 FROM annonce_photo ap WHERE ap.id_annonce = a.id_annonce)
ORDER BY p.id_photo LIMIT 1;

INSERT INTO photo (url)
SELECT 'photos/demo-appartements/deborah-cortelazzi-gREquCUXQLI.jpg' FROM annonce a
WHERE a.id_annonce = 11 AND NOT EXISTS (SELECT 1 FROM annonce_photo ap WHERE ap.id_annonce = a.id_annonce)
AND NOT EXISTS (SELECT 1 FROM photo p WHERE p.url = 'photos/demo-appartements/deborah-cortelazzi-gREquCUXQLI.jpg');
INSERT INTO annonce_photo (id_annonce, id_photo)
SELECT a.id_annonce, p.id_photo FROM annonce a JOIN photo p ON p.url = 'photos/demo-appartements/deborah-cortelazzi-gREquCUXQLI.jpg'
WHERE a.id_annonce = 11 AND NOT EXISTS (SELECT 1 FROM annonce_photo ap WHERE ap.id_annonce = a.id_annonce)
ORDER BY p.id_photo LIMIT 1;

INSERT INTO photo (url)
SELECT 'photos/demo-appartements/roberto-nickson-rEJxpBskj3Q.jpg' FROM annonce a
WHERE a.id_annonce = 17 AND NOT EXISTS (SELECT 1 FROM annonce_photo ap WHERE ap.id_annonce = a.id_annonce)
AND NOT EXISTS (SELECT 1 FROM photo p WHERE p.url = 'photos/demo-appartements/roberto-nickson-rEJxpBskj3Q.jpg');
INSERT INTO annonce_photo (id_annonce, id_photo)
SELECT a.id_annonce, p.id_photo FROM annonce a JOIN photo p ON p.url = 'photos/demo-appartements/roberto-nickson-rEJxpBskj3Q.jpg'
WHERE a.id_annonce = 17 AND NOT EXISTS (SELECT 1 FROM annonce_photo ap WHERE ap.id_annonce = a.id_annonce)
ORDER BY p.id_photo LIMIT 1;

COMMIT;

