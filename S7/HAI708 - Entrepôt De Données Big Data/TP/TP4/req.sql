-- Q1
SELECT A.id_app, A.nom, COUNT(*) nb_photo_prises
FROM Appareil_photo A
JOIN Photo P ON A.id_app = P.appareil_id
GROUP BY A.id_app, A.nom;

-- Q2
SELECT COUNT(*) nb_photo_Eiji
FROM Appareil_photo A
JOIN Photo P ON A.id_app = P.appareil_id
WHERE A.createur = 'EIJI FUMIO';

-- Q3
SELECT A.marque, A.nom, COUNT(*) nb_photo_prises
FROM Appareil_photo A
JOIN Heritage_Photo H ON A.id_app = H.id_app
JOIN Photo P ON H.id_derive = P.appareil_id
WHERE flag_top = 1
AND ROWNUM <= 5 -- = LIMIT ou TOP
GROUP BY A.marque, A.nom
ORDER BY COUNT(*) DESC;