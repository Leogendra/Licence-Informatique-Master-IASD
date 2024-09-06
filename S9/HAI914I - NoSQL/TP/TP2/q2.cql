MATCH (c:Commune {name:'MONTPELLIER'})
CREATE (gf:Personne {nom:"FRECHE",prenom:"GEORGES"}) <-[ap1:ADMINISTREE_PAR
{date_debut:1997, date_fin:2004}]- (c),
(hm:Personne {nom:"MANDROUX",prenom:"HELENE"})<-[ap2:ADMINISTREE_PAR {date_debut:2004,
date_fin:2014}]-(c), (ps:Personne
{nom:"SAUREL",prenom:"PHILIPPE"})<-[ap3:ADMINISTREE_PAR {date_debut:2014,
date_fin:2020}]-(c), (md:Personne
{nom:"DELAFOSSE",prenom:"MICKAEL"})<-[ap4:ADMINISTREE_PAR {date_debut:2020,
date_fin:2026}]-(c)
return *

// 2.1
// 1. définir l'ordre Cypher qui permet d'ajouter un label Maire aux objets de label Personne, qui sont associés à un objet de label Commune, au travers d'une relation de type "ADMINISTREE PAR"
MATCH (c:Commune)-[:ADMINISTREE_PAR]->(p:Personne)
SET p:Maire

// 2. lister l'ensemble des procédures rendues disponibles grâce à l'ajout d'archives Java dans le répertoire plugins. 
CALL dbms.procedures() YIELD name
RETURN name

// Lister aussi l'ensemble des fonctions.
CALL dbms.functions() YIELD name
RETURN name

// 3. utiliser une de ces procédures du paquetage d'extension général (préfixe apoc) pour exporter une partie des objets du graphe au format json (apoc.export.json.query). Le fichier d'export est automatiquement sauvegardé dans le répertoire import. Vous renverrez l'identifiant, les labels et le nom de la commune, ainsi que le non de son département et de sa région. Quels sont les autres formats disponibles ?
CALL apoc.export.json.query("MATCH (c:Commune)-[:WITHIN]->(d:Departement)-[:WITHIN]->(r:Region) RETURN ID(c) as id, labels(c) as labels, c.name as commune, d.name as departement, r.name as region", 'data.json', {})


// 2.1.1 Utilisation du plugin Neosemantics

// 1. vous renverrez le modèle de connaissances de la base au format RDF. Vous pouvez dessiner sous forme de graphe une partie du résultat. Que remarquez vous comme différence avec ce que vous savez contenir le modèle ?
:GET /rdf/describe/id/0

// 2. Vous renverrez au format RDF, la description du noeud correspondant à la commune de MONTPELLIER à partir de son identifiant interne. Vous pouvez dessiner sous forme de graphe une partie du résultat.
MATCH (c:Commune {name: 'MONTPELLIER'})
RETURN ID(c) as communeId

:GET /rdf/describe/id/17

// 3. Renvoyez le résultat de la requête suivante au format RDF 
// Est ce que ce résultat est équivalent au résultat de la description du noeud de la commune de MONTPELLIER ?
:POST /rdf/cypher { "cypher":"MATCH (c:Commune {name:'MONTPELLIER'}) RETURN c" , "format" : "RDF"}

// 4. Renvoyez les informations sur MONTPELLIER et ses différents maires au format RDF
:POST /rdf/cypher { "cypher":"MATCH (c:Commune {name:'MONTPELLIER'})-[w:ADMINISTREE_PAR]-(p) RETURN c, w, p", "format": "RDF"}

// 5. Enrichissez l'ordre précédent pour renvoyer le plus d'informations possibles sur MONTPELLIER
:POST /rdf/cypher { 
    "cypher":"MATCH (r:Region) <-[w2:WITHIN]- (d:Departement) <-[w1:WITHIN]- (c:Commune {name:'MONTPELLIER'})-[ap1:ADMINISTREE_PAR]-> (p:Personne), (c) -[:NEARBY]- (c1:Commune) RETURN * ", 
    "format" : "N3"
}

// 6. Comment faire pour renvoyer les informations qui correspondent aux propriétés valuées de la relation ADMINISTREE_PAR au format RDF ?
:POST /rdf/cypher { 
    "cypher":"MATCH (c:Commune {name:'MONTPELLIER'}) -[ap1:ADMINISTREE_PAR]-> (p:Personne) MERGE (c) -[:gouvernance]-> (m:Municipalite {dateDeb:ap1.date_debut, dateFin:ap1.date_fin}) <-[:dirige] -(p) RETURN c, m, p", 
    "format" : "N3"
}