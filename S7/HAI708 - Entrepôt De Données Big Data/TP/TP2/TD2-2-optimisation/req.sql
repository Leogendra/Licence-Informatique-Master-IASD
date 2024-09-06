SELECT /*+ use_nl(d v)*/ d.nom, v.nom
FROM departement d, ville v 
WHERE v.dep = d.id;