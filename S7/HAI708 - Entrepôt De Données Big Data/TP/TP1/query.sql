-- 1 Toutes les photos de Montpellier
SELECT * FROM Photo 
WHERE lieu LIKE '%Montpellier%';

-- 2 Utilisateur(s) ayant le plus de photos
SELECT id_use, nom_user FROM Utilisateur
GROUP BY id_use, nom_user
HAVING COUNT(*) >= ALL(SELECT COUNT(*) 
                        FROM Photo 
                        GROUP BY code);

-- 3 Photos prises avec le flash
SELECT code, date_photo, lieu FROM Photo, Appareil_photo
WHERE id_app = appareil_id 
AND id_app IN (SELECT appareil_id 
                FROM Configuration 
                WHERE flash LIKE '%oui%'
                );