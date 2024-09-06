import org.chocosolver.solver.Model;
import org.chocosolver.solver.variables.IntVar;


public class nReine {

	public static void main(String[] args) {

		// Création du modele

		
		// Création des variables
		for (int k = 1; k<16; k++) {
			int nb_reine = k;
			System.out.println("\n*** Problème des "+nb_reine+" reines ***");
			Model model = new Model(nb_reine+"_Reines");
			IntVar [] reines = model.intVarArray("reine",nb_reine,1,nb_reine);
			
	
			// Création des contraintes
			model.allDifferent(reines).post();
			
			for (int i=0; i<nb_reine; i++) {
				for (int j=i+1; j<nb_reine; j++) {
					model.arithm(reines[i], "-", reines[j], "!=", i-j).post();
					model.arithm(reines[j], "-", reines[i], "!=", j-i).post();
					model.arithm(reines[j], "-", reines[i], "!=", i-j).post();
					model.arithm(reines[i], "-", reines[j], "!=", j-i).post();
				}
			}
			
	
			// Affichage du réseau de contraintes créé
			//System.out.println("*** Réseau Initial ***");
			//System.out.println(model);
	/*
			// Calcul de la première solution
			if (model.getSolver().solve()) {
				System.out.println("\n\n*** Première solution ***");
				//System.out.println(model);
			}
			else {
				System.out.println("\n\n*** Pas de solution ***");
			}
*/
		/*
		 // Calcul de toutes les solutions
		 System.out.println("\n\n*** Autres solutions ***");
		 Solution S = model.getSolver().findSolution();
		 while (S != null) {
			 System.out.println(S);
		 }
		 */
			int cpt = 0;
			 while(model.getSolver().solve()) { 
				 cpt += 1;
				 //System.out.println("Sol "+model.getSolver().getSolutionCount()+"\n"+model); 
			}
			 System.out.println(cpt+" solutions");
		

		// Affichage de l'ensemble des caractéristiques de résolution
			 //System.out.println("\n\n*** Bilan ***");
			//model.getSolver().printStatistics();
		}
	}
}
