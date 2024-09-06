package qengine.program;

import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.Reader;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Iterator;
import java.util.List;
import java.util.stream.Stream;

import org.eclipse.rdf4j.query.algebra.Projection;
import org.eclipse.rdf4j.query.algebra.StatementPattern;
import org.eclipse.rdf4j.query.algebra.helpers.AbstractQueryModelVisitor;
import org.eclipse.rdf4j.query.algebra.helpers.StatementPatternCollector;
import org.eclipse.rdf4j.query.parser.ParsedQuery;
import org.eclipse.rdf4j.query.parser.sparql.SPARQLParser;
import org.eclipse.rdf4j.rio.RDFFormat;
import org.eclipse.rdf4j.rio.RDFParser;
import org.eclipse.rdf4j.rio.Rio;

import java.util.HashMap;
import java.util.Map;

import java.io.BufferedReader;
import java.io.InputStreamReader;



final class Main {
	static final String baseURI = null;

	static final String workingDir = "data/";
	static final String queryFile = workingDir + "sample_query.queryset";
	static final String dataFile = workingDir + "sample_data.nt";

    static final SPARQLParser sparqlParser = new SPARQLParser();
    static MainRDFHandler myRDFHandler = new MainRDFHandler("SOP");
    static Index IndexSOP = new Index();
    static Index IndexSPO = new Index();
    static Index IndexPOS = new Index();
    static Index IndexPSO = new Index();
    static Index IndexOSP = new Index();
    static Index IndexOPS = new Index();


	public static void main(String[] args) throws Exception {
		System.out.println("Parsing du graphe RDF");
		parseData();
		System.out.println("\n\nAffichage du dictionnaire");
	    testDictionary(myRDFHandler);
	    // System.out.println("\n\nParsing des requêtes");
		// parseQueries();

        System.out.println("Fin du programme.");
	}

	private static void parseData() throws FileNotFoundException, IOException {

        try (Reader dataReader = new FileReader(dataFile)) {
            RDFParser rdfParser = Rio.createParser(RDFFormat.NTRIPLES);
            
            // utilisation de notre handler
            rdfParser.setRDFHandler(myRDFHandler);
            rdfParser.parse(dataReader, baseURI);
            
            Index newIndex = new Index();

            for (String ordre : new String[]{"POS", "PSO", "OSP", "OPS", "SOP", "SPO"}) {
                System.out.println("\n\nAffichage de l'index de " + ordre);  

                newIndex = myRDFHandler.permuterIndex(ordre);
                System.out.println(newIndex);

                switch (ordre) {
                    case "SPO":
                        IndexSPO = newIndex;
                        break;
                    case "POS":
                        IndexPOS = newIndex;
                        break;
                    case "OSP":
                        IndexOSP = newIndex;
                        break;
                    case "SOP":
                        IndexSOP = newIndex;
                        break;
                    case "PSO":
                        IndexPSO = newIndex;
                        break;
                    case "OPS":
                        IndexOPS = newIndex;
                        break;
                }
            }
        }
    }

	// ========================================================================
	
	public static void testDictionary(MainRDFHandler rdfHandler) {
        System.out.println("Nombre de ressources : " + rdfHandler.getIntToStringDictionary().size());
        System.out.println("Entier vers ressource : ");
	    for (Map.Entry<Integer, String> entry : rdfHandler.getIntToStringDictionary().entrySet()) {
	        System.out.println(entry.getKey() + " -> " + entry.getValue());
	    }
        System.out.println("\nRessource vers entier : ");
        for (Map.Entry<String, Integer> entry : rdfHandler.getStringToIntDictionary().entrySet()) {
            System.out.println(entry.getKey() + " -> " + entry.getValue());
        }
	}
	


	// ========================================================================


	private static void parseQueries() throws FileNotFoundException, IOException {

		/*
		 * On utilise un stream pour lire les lignes une par une, sans avoir à toutes les stocker
		 * entièrement dans une collection.
		 */
		try (Stream<String> lineStream = Files.lines(Paths.get(queryFile))) {
			SPARQLParser sparqlParser = new SPARQLParser();
			Iterator<String> lineIterator = lineStream.iterator();
			StringBuilder queryString = new StringBuilder();

			while (lineIterator.hasNext())
			/*
			 * On stocke plusieurs lignes jusqu'à ce que l'une d'entre elles se termine par un '}'
			 * On considère alors que c'est la fin d'une requête
			 */
			{
				String line = lineIterator.next();
				queryString.append(line);

				if (line.trim().endsWith("}")) {
					ParsedQuery query = sparqlParser.parseQuery(queryString.toString(), baseURI);

					processAQuery(query); // Traitement de la requête, à adapter/réécrire pour votre programme

					queryString.setLength(0); // Reset le buffer de la requête en chaine vide
				}
			}
		}
	}

	// ========================================================================

	
    public static void processAQuery(ParsedQuery query) {
        List<StatementPattern> patterns = StatementPatternCollector.process(query.getTupleExpr());

        System.out.println("first pattern : " + patterns.get(0));
        System.out.println("object of the first pattern : " + patterns.get(0).getObjectVar().getValue());
        System.out.println("variables to project : ");

        query.getTupleExpr().visit(new AbstractQueryModelVisitor<RuntimeException>() {

            public void meet(Projection projection) {
                System.out.println(projection.getProjectionElemList().getElements());
            }
        });
    }
    
    // ========================================================================

    public static void executeSPARQLQuery(String sparqlQuery) {
        try {
            // Parse la requête
            ParsedQuery query = sparqlParser.parseQuery(sparqlQuery, baseURI);

            // Traitement de la requête
            processAQuery(query); // À adapter pour traiter les résultats ou effectuer d'autres actions
        } catch (Exception e) {
            System.err.println("Erreur lors du traitement de la requête : " + e.getMessage());
        }
    }
}
