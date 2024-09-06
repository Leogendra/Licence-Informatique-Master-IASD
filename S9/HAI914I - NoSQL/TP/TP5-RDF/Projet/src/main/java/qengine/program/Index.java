package qengine.program;

import java.util.List;
import java.util.Map;
import java.util.ArrayList;
import java.util.HashMap;

public class Index {

    /*
	1er niveau : hashmap 
	2nd niveau : hashmap 
	3eme niveau : arrayList
	*/
    public Map<Integer, Map<Integer, List<Integer>>> arbreIndex;

    // constructeur par défaut qui init la hmap de hmpa de list
    public Index() {
    	this.arbreIndex = new HashMap<Integer, Map<Integer, List<Integer>>>();
    }

	public Map<Integer, Map<Integer, List<Integer>>> getArbreIndex() {
		return arbreIndex;
	}
	
	public void setArbreIndex(Map<Integer, Map<Integer, List<Integer>>> arbre) {
		this.arbreIndex = arbre;
	}

    public int addTripletToIndex(int item1, int item2, int item3) {
        Map<Integer, List<Integer>> secondNiveau = new HashMap<Integer, List<Integer>>();
        if (arbreIndex.containsKey(item1)) {
            secondNiveau = arbreIndex.get(item1);
        }

        List<Integer> troisemeNiveau = new ArrayList<>();
        if (secondNiveau.containsKey(item2)) {
            troisemeNiveau = secondNiveau.get(item2);
        }

        troisemeNiveau.add(item3);
        secondNiveau.put(item2, troisemeNiveau);
        arbreIndex.put(item1, secondNiveau);

        return 0;
    }

    @Override
    public String toString() {
        String returnString = "";
    	for (Map.Entry<Integer, Map<Integer, List<Integer>>> entry : arbreIndex.entrySet()) {
    		returnString += "[" + entry.getKey() + "] -> " + "\n";
    		for (Map.Entry<Integer, List<Integer>> entry2 : entry.getValue().entrySet()) {
    			returnString += "    [" + entry2.getKey() + "] -> ";
    			for (Integer object : entry2.getValue()) {
    				returnString += "["+ object + "] ";
    			}
                returnString += "\n";
    		}
    	}

        return returnString;
    }
   
}
