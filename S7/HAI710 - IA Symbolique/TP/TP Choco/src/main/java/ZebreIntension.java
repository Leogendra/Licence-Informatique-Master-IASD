import org.chocosolver.solver.Model;
import org.chocosolver.solver.variables.IntVar;
import org.chocosolver.solver.constraints.extension.Tuples;

public class ZebreIntension {

	public static void main(String[] args) {

		// Création du modele
		Model model = new Model("Zebre");

		// Création des variables
		IntVar blu = model.intVar("Blue", 1, 5); // blue est une variable entière dont le nom est "Blue" est le domaine
													// [1,5]
		IntVar gre = model.intVar("Green", 1, 5);
		IntVar ivo = model.intVar("Ivory", 1, 5);
		IntVar red = model.intVar("Red", 1, 5);
		IntVar yel = model.intVar("Yellow", 1, 5);

		IntVar eng = model.intVar("English", 1, 5);
		IntVar jap = model.intVar("Japanese", 1, 5);
		IntVar nor = model.intVar("Norwegian", 1, 5);
		IntVar spa = model.intVar("Spanish", 1, 5);
		IntVar ukr = model.intVar("Ukrainian", 1, 5);

		IntVar cof = model.intVar("Coffee", 1, 5);
		IntVar mil = model.intVar("Milk", 1, 5);
		IntVar ora = model.intVar("Orange Juice", 1, 5);
		IntVar tea = model.intVar("Tea", 1, 5);
		IntVar wat = model.intVar("Water", 1, 5);

		IntVar dog = model.intVar("Dog", 1, 5);
		IntVar fox = model.intVar("Fox", 1, 5);
		IntVar hor = model.intVar("Horse", 1, 5);
		IntVar sna = model.intVar("Snail", 1, 5);
		IntVar zeb = model.intVar("Zebra", 1, 5);

		IntVar che = model.intVar("Chesterfield", 1, 5);
		IntVar koo = model.intVar("Kool", 1, 5);
		IntVar luc = model.intVar("Lucky Strike", 1, 5);
		IntVar old = model.intVar("Old Gold", 1, 5);
		IntVar par = model.intVar("Parliament", 1, 5);

		// Création des contraintes
		model.allDifferent(new IntVar[] { blu, red, yel, gre, ivo }).post();
		model.allDifferent(new IntVar[] { eng, jap, nor, spa, ukr }).post();
		model.allDifferent(new IntVar[] { cof, mil, wat, ora, tea }).post();
		model.allDifferent(new IntVar[] { dog, fox, sna, hor, zeb }).post();
		model.allDifferent(new IntVar[] { che, koo, luc, old, par }).post();
		
		// C2
		model.absolute(eng, red).post();

		// C3
		model.arithm(spa, "=", dog).post();
		
		// C4
		model.arithm(cof, "=", gre).post();
		
		// C5
		model.arithm(ukr, "=", tea).post();
		
		// C6
		model.arithm(gre, "=", ivo, "+", 1).post();

		// C7
		model.arithm(old, "=", sna).post();

		// C8
		model.arithm(koo, "=", yel).post();

		// C9
		model.arithm(mil, "=", 3).post();

		// C10
		model.arithm(nor, "=", 1).post();

		// C11
		model.distance(che, fox, "=", 1).post();
		
		// C12
		model.distance(koo, hor, "=", 1).post();

		// C13
		model.arithm(luc, "=", ora).post();

		// C14
		model.arithm(jap, "=", par).post();

		// C15
		model.distance(nor, blu, "=", 1).post();

		// Affichage du réseau de contraintes créé
		System.out.println("*** Réseau Initial ***");
		System.out.println(model);

		// Calcul de la première solution
		if (model.getSolver().solve()) {
			System.out.println("\n\n*** Première solution ***");
			System.out.println(model);
		}
		else {
			System.out.println("\n\n*** Pas de solution ***");
		}

		
		 // Calcul de toutes les solutions
		 System.out.println("\n\n*** Autres solutions ***");
		 /* Solution S = model.getSolver().findSolution();
		 while (S != null) {
			 System.out.println(S);
		 } */
		 while(model.getSolver().solve()) { 
			 System.out.println("Sol "+model.getSolver().getSolutionCount()+"\n"+model); 
			 }
		 

		// Affichage de l'ensemble des caractéristiques de résolution
		System.out.println("\n\n*** Bilan ***");
		model.getSolver().printStatistics();
	}
}
