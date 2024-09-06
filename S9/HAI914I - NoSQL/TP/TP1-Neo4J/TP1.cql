// Cheat sheet
// () : noeud
// [] : relation
// {} : propriété(s)
// ->, <- : direction
// - : relation non orientée
// -[:RELATION]-> : relation orientée
// -[:RELATION]- : relation non orientée
// MATCH : SELECT
// CREATE : INSERT
// MERGE : INSERT IF NOT EXISTS
// DELETE : DELETE
// DETACH DELETE : DELETE + DELETE RELATIONSHIPS
// SET : UPDATE
// REMOVE : REMOVE PROPERTY
// RETURN : RETURN
// WITH : WITH
// WHERE : WHERE
// ORDER BY : ORDER BY
// DISTINCT : DISTINCT
// COUNT : COUNT
// LIMIT : LIMIT

// Tout afficher : MATCH (n) RETURN n

// 2.1.1
CREATE (m:Commune {nom:'MONTPELLIER', latitude:43.610769, longitude:3.876716,
codeinsee:'34172'}) -[:WITHIN]-> (h:Departement {nom:'HERAULT', numero:'34'}),
(h) <-[:WITHIN]- (l:Commune {nom:'LUNEL', latitude:43.67445, longitude:4.135366,
codeinsee:'34145'}), (m) -[:NEARBY]-> (l), (c:Commune {nom:'CLAPIERS',
latitude:43.6575, longitude:3.88833, codeinsee:'34830', pop_17:5135}) -[:WITHIN]->
(h), (m) -[:NEARBY {type:'border'}]-> (c);
// RETURN l, m, h, c

MATCH (l:Commune {nom:'LUNEL'})
CREATE (m:Commune {nom:'NIMES', latitude:43.836699, longitude:4.360054,
codeinsee:'30189'}) -[:WITHIN]-> (h:Departement {nom:'GARD', numero:'30'}), (m)
-[:NEARBY]-> (l);

MATCH (h:Departement {nom:'GARD', numero:'30'})
CREATE (m:Commune {nom:'GARONS', latitude:43.770059, longitude:4.424545,
codeinsee:'30125'}) -[:WITHIN]-> (h), (m) -[:NEARBY]-> (s:Commune {nom:'SOMMIERES',
codeinsee:'30321'}), (s) -[:WITHIN]-> (h);

MATCH (g:Commune {nom:'GARONS'})
MATCH (n:Commune {nom:'NIMES'})
CREATE (g) -[:NEARBY]-> (n);

MATCH (h:Departement {numero:'34'})
MATCH (m:Commune {nom:'MONTPELLIER'})
CREATE (c:Commune {nom:'MONTFERRIER-SUR-LEZ', latitude:43.671824, longitude:3.859265,
codeinsee:'34169',pop_1975:1682}) -[:WITHIN]-> (h), (c) -[:NEARBY]-> (m),(cr:Commune
{nom:'CRES', latitude:43.644825, longitude:3.936612,
codeinsee:'34090',pop_1975:4507}) -[:WITHIN]-> (h), (cr) -[:NEARBY]-> (m),
(ca:Commune {nom:'CASTELNAU-LE-LEZ', latitude:43.634144, longitude:3.897398,
codeinsee:'34057',pop_1975:9446}) -[:WITHIN]-> (h), (ca) -[:NEARBY
{type:'border'}]-> (m),(cas:Commune {nom:'CASTRIES', latitude:43.677589,
longitude:3.985579, codeinsee:'34058',pop_1975:2494}) -[:WITHIN]-> (h), (cas)
-[:NEARBY]-> (m), (stc:Commune {nom:'SAINT-CLEMENT-DE-RIVIERE', latitude:43.6844,
longitude:3.8472, codeinsee:'34247',pop_1975:845,pop_2010:4985}) -[:WITHIN]-> (h),
(stc) -[:NEARBY]-> (m);


// 2.1.2
MATCH (s:Commune {nom:'SOMMIERES'})
SET s.pop_1975=3072, s.latitude=43.783450, s.longitude=4.089738
RETURN s

MATCH (s:Commune {nom:'SOMMIERES'}) <-[r:NEARBY]- (g:Commune {nom:'GARONS'})
SET r.distance = 27.4

// 2.1.3
MATCH (c1:Commune)-[:NEARBY]->()<-[:NEARBY]-(c2:Commune)
MERGE (c1)-[:NEARBY]-(c2)


// 2.2
// communes de lHERAULT
MATCH (d:Departement {nom:'HERAULT'}) <-[p:WITHIN]- (n:Commune)
RETURN d, n, p;

// communes qui sont proches de MONTPELLIER
MATCH (m:Commune {nom:'MONTPELLIER'}) -[:NEARBY]- (n:Commune)
RETURN m, n;

// Exploitez la notion de chemin pour renvoyer le réseau de proximité depuis MONTPELLIER
MATCH (m:Commune {nom:'MONTPELLIER'}) -[:NEARBY*]- (n:Commune)
RETURN m, n;

// communes qui sont proches dune commune qui est proche de Montpellier
MATCH (m:Commune {nom:'MONTPELLIER'}) -[:NEARBY*2]- (n:Commune)
RETURN m, n;

// tailles des chemins menant de Montpellier a Nimes (ou son inverse)
MATCH p=((c1:Commune {nom:'MONTPELLIER'})-[:NEARBY*]-(c2:Commune {nom:'NIMES'}))
RETURN DISTINCT length(p) AS LongueurDuChemin;


// 2.2.1

// Tout supprimer
MATCH (n)
DETACH DELETE n;

// un noeud
MATCH (n:Commune {nom:'SOMMIERES'})
DELETE n;

// une relation
MATCH (n:Commune { nom:'GARONS' })-[r:NEARBY]->()
DELETE r;

// un attribut de noeud
MATCH (n:Commune { nom:'NIMES' })
REMOVE n.codeinsee
RETURN n;

// 3.
LOAD CSV WITH HEADERS FROM 'file:///Commune.csv' AS communes
CREATE (co:Commune {codeinsee : toInteger(communes.codeinsee), name : communes.name, longitude : toFloat(communes.longitude), latitude : toFloat(communes.latitude)});


LOAD CSV WITH HEADERS FROM 'file:///Departement.csv' AS departements
CREATE (d:Departement {id : departements.id, name : departements.name});


LOAD CSV WITH HEADERS FROM 'file:///Region.csv' AS regions
CREATE (r:Region {id : toInteger(regions.id), name : regions.name});


LOAD CSV WITH HEADERS FROM 'file:///Com_Dep.csv' AS com_dep
MERGE (co : Commune { codeinsee : toInteger(com_dep.codeinsee) })
MERGE (d : Departement { id : com_dep.id })
CREATE (co)-[:WITHIN]->(d);


LOAD CSV WITH HEADERS FROM 'file:///Reg_Dep.csv' AS reg_dep
MERGE (r : Region { id : toInteger(reg_dep.idR) })
MERGE (d : Departement { id : reg_dep.idD })
CREATE (d)-[:WITHIN]->(r);


LOAD CSV WITH HEADERS FROM 'file:///Com_Com.csv' AS com_com
MERGE (co1 : Commune { codeinsee : toInteger(com_com.codeinsee1) })
MERGE (co2 : Commune { codeinsee : toInteger(com_com.codeinsee2) })
CREATE (co1)-[:NEARBY]->(co2);

// 3.1
MATCH (c:Commune { name:'MONTPELLIER'})
MATCH (d:Departement { name:'HERAULT'})
CREATE (c) <-[:CHEF_LIEU_DEPT]- (d)

MATCH (c:Commune { name:'NIMES'})
MATCH (d:Departement { name:'GARD'})
CREATE (d) -[:CHEF_LIEU_DEPT]-> (c)

CREATE (:Commune {name:'Toulouse', codeinsee:'31555', latitude:43.604652, longitude:1.444209})

MATCH (c:Commune {name:'TOULOUSE'})
MATCH (r:Region {name:'OCCITANIE'})
MATCH (d:Departement { name:'HAUTE-GARONNE'})
CREATE (r) -[:CHEF_LIEU_REGION]-> (c)

MATCH (c:Commune {name:'TOULOUSE'})
MATCH (d:Departement { name:'HAUTE-GARONNE'})
CREATE (d) -[:CHEF_LIEU_DEPT]-> (c)

// 4.

// 4.1
MATCH (:Departement)-[:WITHIN]->(c:Region {name:'OCCITANIE'})
RETURN c.name as Reg, count(*) as departement

// 4.2
MATCH (:Departement)-[:WITHIN]->(c:Region)
RETURN c.name as Region, count(*) as departement

// 4.3
MATCH (:Departement)-[:WITHIN]->(c:Region)
WITH c, count(*) as Dep
WHERE Dep > 6
RETURN c.name as Reg, Dep as departement

// 4.4
MATCH (c1:Commune) -[:NEARBY]-> (:Commune {name:'MONTPELLIER'})
MATCH (c1) -[:WITHIN]-> (d1:Departement)
WITH c1, d1
RETURN c1.name as commune, d1.name as departement


















// 4.4
MATCH (c:Commune)-[:WITHIN]->(d:Departement)
WITH c, d
WHERE  (c)-[:NEARBY]-(:Commune {name:'MONTPELLIER'})
RETURN c.name as commune, d.name as departement

// 4.5
MATCH (i:Commune)-[:NEARBY*]-(:Commune {name:'MONTPELLIER'})
WHERE NOT (i) -[:WITHIN]-> (:Departement {name:'HERAULT'})
RETURN i.name as commune, i.codeinsee as codeinsee

// 4.6
MATCH (c:Commune)-[w:WITHIN]-(d:Departement), (d)-[w2:WITHIN]-(r:Region)
RETURN c.name as commune, r.name as region, d.name as departement

// 4.7
MATCH p=allshortestPaths( (m:Commune)-[:NEARBY*]-(n:Commune) )
WHERE m.name='MONTPELLIER' and n.name = 'NIMES'
RETURN extract (n in nodes(p) | n.name) as communesSurLeChemin

// 4.8
MATCH p=shortestPath( (m:Commune)-[:NEARBY*]-(n:Commune) )
WHERE m.name='MONTPELLIER' and n.name = 'NIMES'
RETURN extract (n in nodes(p) | n.name) as communesSurLeChemin
