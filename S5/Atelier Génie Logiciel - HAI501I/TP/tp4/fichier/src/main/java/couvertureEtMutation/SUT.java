package couvertureEtMutation;

import java.util.OptionalInt;

public class SUT {
	private OptionalInt[] tab;
	private int taille=0;

	public SUT(int tailleMax) {
		tab=new OptionalInt[tailleMax];
	}

	public void ajout(int e) throws TableauPleinException{
		if (taille==tab.length) {
			throw new TableauPleinException();
		}
		tab[taille]=OptionalInt.of(e);
		taille++;
	}

	public int[] values() {
		int[] result=new int[taille];
		for (int i=0;i<taille;i++) {
				result[i]=tab[i].getAsInt();
		}
		return result;
	}

	public int retourneEtSupprimePremiereOccurenceMin() throws TableauVideException{
		if (taille==0) {
			throw new TableauVideException();
		}
		int min =tab[0].getAsInt();
		int imin=0;
		for (int i=1;i<taille;i++) {
			if (tab[i].getAsInt()<=min) {
				// on a un nouveau min
				min=tab[i].getAsInt();
				imin=i;
			}
		}
		for (int i=taille-1;i>imin;i--) { // parcours du tableau depuis la fin pour décalage à gauche
			tab[i-1]=tab[i]; // suppression et décalage à gauche
		}
		tab[taille-1]=OptionalInt.empty();
		taille--;
		return min;
	}
}
