CREATE INDEX ON :Resource(uri) 


WITH ' PREFIX sch: <http://schema.org/> 
CONSTRUCT{ ?item a sch:City; 
           sch:address ?inseeCode; 
           sch:name ?itemLabel ; 
           sch:geoTouches ?otherItem .
	   ?otherItem a sch:City;	
	   sch:name ?otheritemLabel ;
	   sch:address ?otherinseeCode . } 
WHERE { ?item wdt:P374 ?inseeCode .
	?item wdt:P47 ?otherItem .
	?otherItem wdt:P374 ?otherinseeCode .
        ?item rdfs:label ?itemLabel . 
          filter(lang(?itemLabel) = "fr")  . 
        ?otherItem rdfs:label ?otheritemLabel . 
          filter(lang(?otheritemLabel) = "fr")  .
	 FILTER regex(?inseeCode, "^34")  .
        } ' 
AS sparql CALL semantics.importRDF(
  "https://query.wikidata.org/sparql?query=" +  
      apoc.text.urlencode(sparql),"JSON-LD", 
    { headerParams: { Accept: "application/ld+json"} })
YIELD terminationStatus, triplesLoaded,namespaces,extraInfo
RETURN terminationStatus, triplesLoaded,namespaces,extraInfo

// 3.1
/*
La requête SPARQL récupère des informations sur les communes de l"Hérault depuis Wikidata et crée un ensemble de triplets RDF au format JSON-LD. 
Pour ce faire :
     - Elle cherche des communes de l"Hérault en utilisant leur code INSEE. Si un code INSEE commence par "34", cela signifie qu"il se trouve - Pour chaque commune trouvée, elle crée un triplet RDF pour représenter cette commune comme une "City" selon le vocabulaire schema.org.
     - Elle ajoute le cod2e INSEE de la commune comme l"adresse de la ville.
     - Elle récupère le nom de la commune en français et l"ajoute comme le nom de la ville.
     - Elle recherche d"autres communes qui sont voisines de la commune actuelle en utilisant la relation "geoTouches" et crée des triplets RDF pour ces communes voisines.
     - L"objectif de cette requête est de créer un ensemble de données RDF représentant les communes de l"Hérault, leurs noms, leurs adresses (codes INSEE) et leurs relations de voisinage.

Le graphe RDF résultant pourrait ressembler à ceci :

//           [Voisin]
//          /        \
// [Montpellier] - [Béziers]

Dans ce graphe, "Montpellier" et "Béziers" sont des nœuds de type "City" et sont voisins l"un de l"autre.
*/

// 2 Les nœuds importés correspondent à des villes et villages de l'Hérault. Vous ferez en sorte de les lier au nœud correspondant au département de l'Hérault via la relation WITHIN
MATCH (ci:sch__City)
MATCH (n:Departement {id:'34'})
CREATE (ci) -[:WITHIN]-> (n)

// 3. Vous supprimerez les nœuds de type ”Commune” du graphe
MATCH (c:Commune) 
DETACH DELETE c

// 4. Renvoyez le nombre de communes limitrophes de la commune de Montpellier
MATCH (c:sch__City) -[:sch__geoTouches]- (m:sch__City {sch__name:'MONTPELLIER'})
RETURN count(c) as limitrophes


// 3.2
// 1. renvoyez un des plus courts chemins entre Montpellier et Beaulieu
MATCH p=shortestPath( 
  (m:sch__City {sch__name:'Montpellier'})
  -[:sch__geoTouches*]-
  (n:sch__City {sch__name:'Beaulieu'}) 
)
RETURN p
// RETURN length(p) as taillePLusCourtChemin


// 2. renvoyez tous les plus courts chemins entre Montpellier et Beaulieu
MATCH p=allshortestPaths( 
  (m:sch__City {sch__name:'Montpellier'})
  -[:sch__geoTouches*]-
  (n:sch__City {sch__name:'Beaulieu'}) 
)
RETURN p

// 3. renvoyez le nom des cités traversées par un des plus courts chemins entre Montpellier et Beaulieu
MATCH p=shortestPath( 
  (m:sch__City {sch__name:'Montpellier'})
  -[:sch__geoTouches*]-
  (n:sch__City {sch__name:'Beaulieu'}) 
)
RETURN EXTRACT (n in nodes(p) | {name:n.sch__name})


// 4. renvoyez le nom et l’adresse des cités traversées par un des plus courts chemins entre Montpellier et Beaulieu
MATCH p=shortestPath( 
  (m:sch__City {sch__name:'Montpellier'})
  -[:sch__geoTouches*]-
  (n:sch__City {sch__name:'Beaulieu'}) 
)
RETURN EXTRACT (n in nodes(p) | {name:n.sch__name, codeinsee:n.sch__address})


// 5. renvoyez le nombre des plus courts chemins entre Montpellier et Beaulieu
MATCH p=allshortestPaths( 
  (m:sch__City {sch__name:'Montpellier'})
  -[:sch__geoTouches*]-
  (n:sch__City {sch__name:'Beaulieu'}) 
)
RETURN count(p) as NombreplusCourtsChemins


// 6. renvoyez tous les chemins entre Montpellier et Beaulieu (très coûteuse). Que faire pour réduire la complexité ?


// 7. retourner un des plus courts chemins qui ne passe pas par Clapiers ?
