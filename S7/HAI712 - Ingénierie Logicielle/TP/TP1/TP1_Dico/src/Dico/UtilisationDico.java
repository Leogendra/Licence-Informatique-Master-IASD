package Dico;

public class UtilisationDico {
	public static void main(String[] args) {

		IDico dic = new OrderedDico();
		// Test de base sur la taille et l'insertion
		System.out.println("\nORDERED DICTIONARY\n");
		System.out.println("La taille du dictionnaire est de "+ dic.size() + " mots\n");
		Object cle = "Lavoisier";
		dic.put(cle, "Chimiste français");
		System.out.println("La taille du dictionnaire est de "+ dic.size() + " mots\n");
		System.out.println(cle + ": " + dic.get(cle));
		
		// insertion de string
		dic.put("Godel", "Logicien et mathématicien autrichien");
		cle = "Godel";
		System.out.println(cle + ": " + dic.get(cle));
		
		// insertion d'int 
		dic.put(1, "1 en INT");
		cle = 1;
		System.out.println(cle + ": " + dic.get(cle));
		
		// insertion en masse
		for(int i = 0; i < 100; i++) {
			dic.put(i, i);
		}
	}

}
