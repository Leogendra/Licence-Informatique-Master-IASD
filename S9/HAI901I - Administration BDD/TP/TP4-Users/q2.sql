-- 1. consulter la vue (v$version) portant sur la version du SGBD Oracle sous-jacent
SELECT * FROM V$VERSION;

-- 2. consulter l’attribut server de la vue v$session pour connaı̂tre l’architecture client-serveur retenue pour servir les connexions utilisateurs (architecture dédiée ou partagée).
SELECT DISTINCT server
FROM V$SESSION;

-- 3. consulter la vue (v$option) portant sur les fonctionnalités du serveur de données et répondre à des interrogations telles que : de quelles options disposons-nous ?
SELECT * FROM V$OPTION;