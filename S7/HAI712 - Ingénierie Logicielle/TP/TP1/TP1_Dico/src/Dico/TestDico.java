package Dico;

public class TestDico {
	public static void main(String[] args){

		//ORDERED DICTIONNARY
		System.out.println("\n------ ORDERED DICTIONNARY -------\n");
		
		//AFFECTATION
		IDico dicO = new OrderedDico();		//affectation a une instance de orderedDico
		System.out.println("Affectation reussi\n");
				
		//UTILISATION METHODE
		System.out.println("Est ce que le dictionaire est vide ? "+ dicO.isEmpty()+ "\n");
		
		System.out.println("La taille du dictionnaire est "+ dicO.size()+ "\n");
		
		//AJOUT ELEMENTS
		System.out.println("J'ajoute Chocolat au dictionnaire");
		dicO.put("Chocolat", "Je suis le gateau au chocolat");
		System.out.println("J'ajoute Vanille au dictionnaire");
		dicO.put("Vanille", "Je suis la glace à la vanille");
		System.out.println("J'ajoute Pistache au dictionnaire\n");
		dicO.put("Pistache", "Miam");
		
		
		System.out.println("La valeur associé à Chocolat est : "+ dicO.get("Chocolat") + "\n");
		System.out.println("La valeur associé à Vanille est : "+ dicO.get("Vanille") + "\n");
		System.out.println("La valeur associé à Pistache est : "+ dicO.get("Pistache") + "\n");
		
		System.out.println("Est ce que le dictionaire contient Pistache ? "+ dicO.containsKey("Pistache")+ "\n");
		
		System.out.println("Est ce que le dictionaire est vide ? "+ dicO.isEmpty()+ "\n");
		
		System.out.println("La taille du dictionnaire après les insertions est "+ dicO.size() + "\n");
		
		
		System.out.println("FIN ORDERED DICTIONARY\n");
		
		/*
		//FAST DICTIONNARY
		System.out.println("\n------ FAST DICTIONNARY -------\n");
		
		//AFFECTATION
		IDico dicF = new FastDico();		//affectation a une instance de orderedDictio
		System.out.println("Affectation reussi\n");
				
		//UTILISATION METHODE
		System.out.println("Est ce que le dictionaire est vide ? "+ dicF.isEmpty()+ "\n");
		
		System.out.println("La taille du dictionnaire est "+ dicF.size()+ "\n");
		
		//AJOUT ELEMENTS
		dicF.put("Chocolat", "Je suis le gateau au chocolat");
		System.out.println("J'ajoute Chocolat au dictionnaire");
		dicF.put("Vanille", "Je suis la glace à la vanille");
		System.out.println("J'ajoute Vanille au dictionnaire");
		dicF.put("Pistache", "Amuse bouche pour l'apéro");
		System.out.println("J'ajoute Pistache au dictionnaire\n");
		
		
		System.out.println("La valeur associé à Chocolat est : "+ dicF.get("Chocolat") + "\n");
		System.out.println("La valeur associé à Vanille est : "+ dicF.get("Vanille") + "\n");
		System.out.println("La valeur associé à Pistache est : "+ dicF.get("Pistache") + "\n");
		
		System.out.println("Est ce que le dictionaire contient Pistache ? "+ dicF.containsKey("Pistache")+ "\n");
		
		System.out.println("Est ce que le dictionaire est vide ? "+ dicF.isEmpty()+ "\n");
		
		System.out.println("La taille du dictionnaire après les insertions est "+ dicF.size() + "\n");
		
		
		System.out.println("FIN FAST DICTIONARY\n");
		*/
		
	}
}
