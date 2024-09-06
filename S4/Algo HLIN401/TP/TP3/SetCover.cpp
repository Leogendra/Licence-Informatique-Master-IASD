#include <cstdlib>
#include <fstream>
#include <iostream>
#include <string>

#include "SetCover.h"

using namespace std;

coord *maisonsAleatoires(int n, int l, int h) {
	coord *maisons = new coord[n];
	for (int i = 0; i < n; i++) {
		maisons[i] = {rand() % (l - 19) + 10, rand() % (h - 19) + 10};
	}
	return maisons;
}

bool couvre(int i, int j, coord *maisons, int dcouv) {
	coord m1 = maisons[i];
	coord m2 = maisons[j];
	return ((m1.x - m2.x) * (m1.x - m2.x) + (m1.y - m2.y) * (m1.y - m2.y)) <=
		(dcouv * dcouv);
}

bool **graphe(int n, coord *maisons, int dcouv) {
	bool **G = new bool *[n];
	for (int i = 0; i < n; i++) {
		G[i] = new bool[n];
		for (int j = 0; j < n; j++) {
			if (couvre(i, j, maisons, dcouv)) {
				G[i][j] = true;
			} else {
				G[i][j] = false;
			}
		}
	}

	return G;
}

int prochaineMaison(int n, bool **G, bool *couvertes) {
	int maison_max = 0;
	int voisin_max = 0;
	int voisin = 0;
	for (int i = 0; i < n; i++) {
		if (couvertes[i] == false) {
			voisin = 0;
			for (int j = 0; j < n; j++) {
				if (G[i][j] && couvertes[j] == false) {
					voisin++;
				}
			}
			if (voisin > voisin_max) {
				maison_max = i;
				voisin_max = voisin;
			}
		}
	}
	return maison_max;
}

int placementGlouton(int n, bool **G, bool *emetteurs) {
	int cpt_em = 0;
	bool reste_a_couvrir = true;
	bool *maisons_couvertes = new bool[n];
	for (int i = 0; i < n; i++) {
		emetteurs[i] = false;
		maisons_couvertes[i] = false;
	}

	while (reste_a_couvrir) {
		reste_a_couvrir = false;
		int nouv_em = prochaineMaison(n, G, maisons_couvertes);
		emetteurs[nouv_em] = true;
		cpt_em++;

		for (int i = 0; i < n; i++) {
			if (G[nouv_em][i]) {
				maisons_couvertes[i] = true;
			}
			if (maisons_couvertes[i] == false) {
				reste_a_couvrir = true;
			}
		}
	}
  delete[] maisons_couvertes;
	return cpt_em;
}




int placementOptimal(int n, bool **G, bool *emetteurs) {
	int nbr_em=0;
	bool reste_a_couvrir=true;
	bool *maisons_couvertes = new bool[n];

//boucle qui teste toutes les combinaisons de i émetteurs en commencant par i=1
	while (reste_a_couvrir && nbr_em<n) {
    nbr_em++;
    int *combinaisons = new int[nbr_em];
    //reset le tableau de combinaison, dernier emetteur
		for (int i=0; i<nbr_em; i++) {combinaisons[i]=i;}
    int dernier=(nbr_em-1);


    //boucle sur toutes les combinaisons de k (nb_em) émetteurs parmis n maisons
		while (reste_a_couvrir && dernier>-1) {
      reste_a_couvrir=false;

      //réinitialise les maisons couvertes et des émetteurs
		  for (int i=0; i<n; i++) {
        maisons_couvertes[i]=false;
        emetteurs[i]=false;
      }

      //transcrit le tableau de combinaison dans le tableau des émetteurs
			for (int i=0; i<nbr_em; i++) {emetteurs[combinaisons[i]]=true;}

      //couvre les maisons qui sont en contact avec un émetteur
      for (int i=0; i<n; i++) {
        if (emetteurs[i]) {
          for (int j=0; j<n; j++) {
            if (G[i][j]) {maisons_couvertes[j]=true;}
          }
        }
      }

      //est-ce que totues les maisons sont couvertes ?
      for (int i=0; i<n; i++) {
        if (maisons_couvertes[i]==false) {reste_a_couvrir=true;}
      //si c'est couvert, reste_a_couvrir=false donc on va break les deux while
      }

			// génère la prochaine combinaison
			dernier=(nbr_em-1);
			while ((dernier>-1) && (combinaisons[dernier] > (n - 1 - nbr_em + dernier))) {
				dernier--;
			}
			if (dernier>-1) {
        combinaisons[dernier]++;
				int max=combinaisons[dernier];
				dernier++;
				while (dernier<nbr_em) {
          max++;
					combinaisons[dernier]=max;
          dernier++;
				}
			}
    }

    delete[] combinaisons;
	}

  delete[] maisons_couvertes;
	return nbr_em;
}
