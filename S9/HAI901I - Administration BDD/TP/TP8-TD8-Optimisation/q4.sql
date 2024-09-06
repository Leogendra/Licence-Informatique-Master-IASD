-- Récupérer toutes les communes qui sont chefes lieu d'un département et d'une région

SELECT nomcommaj, nomdepmaj, nomregmaj, d.cheflieu, r.cheflieu
FROM comm c
JOIN dep d ON c.codeinsee = d.cheflieu
JOIN region_opti r ON c.codeinsee = r.cheflieu;


SELECT nomcommaj, d.numdep, r.numreg
FROM comm c
JOIN dep d ON c.codeinsee = d.cheflieu
JOIN region_opti r ON c.codeinsee = r.cheflieu;