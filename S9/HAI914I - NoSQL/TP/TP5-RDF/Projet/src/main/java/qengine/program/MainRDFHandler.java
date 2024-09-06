package qengine.program;

import org.apache.jena.sparql.function.library.e;
import org.eclipse.rdf4j.model.Statement;
import org.eclipse.rdf4j.rio.helpers.AbstractRDFHandler;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;


public final class MainRDFHandler extends AbstractRDFHandler {

    private Map<Integer, String> intToStringDictionary;
    private Map<String, Integer> stringToIntDictionary;
    private Map<Integer, Integer> idMap; // Dictionnaire intermédiaire
    private Index arbreIndex;
    private String ordre;

    public MainRDFHandler(String ordre) {
    	this.arbreIndex = new Index();
        this.intToStringDictionary = new HashMap<>();
        this.stringToIntDictionary = new HashMap<>();
        this.idMap = new HashMap<>();
        this.ordre = ordre;
    }

    // ========================================================================

    @Override
    public void handleStatement(Statement st) {
        String subject = st.getSubject().stringValue();
        String predicate = st.getPredicate().stringValue();
        String object = st.getObject().stringValue();

        int subjectId = getResourceId(subject);
        int predicateId = getResourceId(predicate);
        int objectId = getResourceId(object);
        
        intToStringDictionary.put(subjectId, subject);
        intToStringDictionary.put(predicateId, predicate);
        intToStringDictionary.put(objectId, object);

        stringToIntDictionary.put(subject, subjectId);
        stringToIntDictionary.put(predicate, predicateId);
        stringToIntDictionary.put(object, objectId);
        
        if (ordre.equals("SPO")) {
        	this.arbreIndex.addTripletToIndex(subjectId, predicateId, objectId);
        } 
        else if (ordre.equals("POS")) {
        	this.arbreIndex.addTripletToIndex(predicateId, objectId, subjectId);
        } 
        else if (ordre.equals("OSP")) {
        	this.arbreIndex.addTripletToIndex(objectId, subjectId, predicateId);
        }
        else if (ordre.equals("SOP")) {
        	this.arbreIndex.addTripletToIndex(subjectId, objectId, predicateId);
        }
        else if (ordre.equals("PSO")) {
        	this.arbreIndex.addTripletToIndex(predicateId, subjectId, objectId);
        }
        else if (ordre.equals("OPS")) {
        	this.arbreIndex.addTripletToIndex(objectId, predicateId, subjectId);
        }

        System.out.println("\n" + subjectId + "\t " + predicateId + "\t " + objectId);
    }

    // ========================================================================
    
    private int getResourceId(String resource) {
        return idMap.computeIfAbsent(resource.hashCode(), k -> idMap.size());
    }

    // ========================================================================

    public Index permuterIndex(String permutation) {
        
        if (permutation.equals("SPO")) {
            return arbreIndex;
        }

        Index permutedIndex = new Index();

        // pour tous les sujets de l'index, on regarde tous les predicats, puis tous les objets
        for (Map.Entry<Integer, Map<Integer, List<Integer>>> entry : arbreIndex.getArbreIndex().entrySet()) {
            int subject = entry.getKey();
            for (Map.Entry<Integer, List<Integer>> entry2 : entry.getValue().entrySet()) {
                int predicate = entry2.getKey();
                for (Integer object : entry2.getValue()) {

                    if (permutation.equals("POS")) {
                        permutedIndex.addTripletToIndex(predicate, object, subject);
                    }
                    else if (permutation.equals("OSP")) {
                        permutedIndex.addTripletToIndex(object, subject, predicate);
                    }
                    else if (permutation.equals("SOP")) {
                        permutedIndex.addTripletToIndex(subject, object, predicate);
                    }
                    else if (permutation.equals("PSO")) {
                        permutedIndex.addTripletToIndex(predicate, subject, object);
                    }
                    else if (permutation.equals("OPS")) {
                        permutedIndex.addTripletToIndex(object, predicate, subject);
                    }
                        
                }
            }
        }

        return permutedIndex;
    }

    // ========================================================================

    public void setOrdre(String ordre) {
        if (ordre.equals("SPO") || ordre.equals("POS") || ordre.equals("OSP") || ordre.equals("SOP") || ordre.equals("PSO") || ordre.equals("OPS")) {
            this.ordre = ordre;
        }
        else {
            System.err.println("Erreur lors du traitement de la requête : " + "Ordre invalide");
        }
    }

    public String getOrdre() {
        return ordre;
    }

    public Map<Integer, String> getIntToStringDictionary() {
        return intToStringDictionary;
    }

    public void setIntToStringDictionary(Map<Integer, String> intToStringDictionary) {
        this.intToStringDictionary = intToStringDictionary;
    }

    public Map<String, Integer> getStringToIntDictionary() {
        return stringToIntDictionary;
    }

    public void setStringToIntDictionary(Map<String, Integer> stringToIntDictionary) {
        this.stringToIntDictionary = stringToIntDictionary;
    }

    public Index getArbreIndex() {
        return arbreIndex;
    }

    public void setArbreIndex(Index arbreIndex) {
        this.arbreIndex = arbreIndex;
    }

}
