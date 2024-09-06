SELECT id_produit, id_ville, id_date, SUM(montant_journalier)
FROM ventes_monoprix
GROUP BY CUBE(id_produit, id_ville, id_date);