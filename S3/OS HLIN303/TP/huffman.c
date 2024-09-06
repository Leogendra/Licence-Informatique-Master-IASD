// COMPRESSEUR HUFFMAN

#include <stdio.h>
#include <stdlib.h>

typedef struct noeud {int pere; int eg; int ed; int nbOcc;} Noeud;
Noeud Arbre[511];  // 511 car 256 + 255

int* comptage(char* fichier) {
  int* nbOccurrences = (int*)malloc(256*sizeof(int));
  for (int i=0; i<256; i++) nbOccurrences[i] = 0;
  FILE* fd = fopen(fichier, "r");
  char c;
  if (fd) {
     while ((c=fgetc(fd)) != EOF) nbOccurrences[c]++;
  } else printf("Fichier inconnu");
  return nbOccurrences;
}

int constructionArbre(int* nbOccurrences) {
  for (int i=0; i < 511; i++) {
    Arbre[i].pere = -1; Arbre[i].eg = -1; Arbre[i].ed = -1; Arbre[i].nbOcc = 0; 
  }
  for (int i=0; i < 256; i++) Arbre[i].nbOcc = nbOccurrences[i];
  int numDernierNoeudCree = 255;
  int imin1, nbOccmin1, imin2, nbOccmin2;
  // Appariement de deux noeuds avec un nbOcc > 0, sans pères et de nbOcc min
  do {
    imin1 = imin2 = -1;
    nbOccmin1 = nbOccmin2 = 999999; // Il faudrait valuer ces variables avec le plus grand nombre d'occurrences trouvé

    // Parcours de tous les noeuds feuilles et des noeuds de structure déjà créés
    for (int i=0; i <= numDernierNoeudCree; i++) {
      if (Arbre[i].nbOcc > 0 && Arbre[i].pere == -1 && Arbre[i].nbOcc < nbOccmin1) {
	imin1 = i;
	nbOccmin1 = Arbre[i].nbOcc;
      }
    }

    // idem pour la recherche du second noeud à apparier
    for (int i=0; i <= numDernierNoeudCree; i++) {
      if (i != imin1 && Arbre[i].nbOcc > 0 && Arbre[i].pere == -1 && Arbre[i].nbOcc < nbOccmin2) {
	imin2 = i;
	nbOccmin2 = Arbre[i].nbOcc;
      }
    }

    if (imin1 != -1 && imin2 != -1) {
      numDernierNoeudCree++;
      Arbre[numDernierNoeudCree].eg = imin1;
      Arbre[numDernierNoeudCree].ed = imin2;      
      Arbre[numDernierNoeudCree].nbOcc = Arbre[imin1].nbOcc + Arbre[imin2].nbOcc;
      Arbre[imin1].pere = numDernierNoeudCree;
      Arbre[imin2].pere = numDernierNoeudCree;      
    }
    
  } while (imin1 != -1 && imin2 != -1);
  return numDernierNoeudCree;
}

void parcoursArbre(int noeud) {

}

void compression(char* fichier) {

}

int main(int argc, char** argv) {
   // Comptages des occurrences
   int* nbOcc = comptage(argv[1]);
   for (int i=0; i<256; i++) {
     if (nbOcc[i] > 0) printf("%i %c : %i\n", i, i, nbOcc[i]);
   }

   // Construction de l'arbre   
   int numDernierNoeud = constructionArbre(nbOcc);
   for (int i=0; i < 511; i++) {
     if (Arbre[i].nbOcc > 0) {
       printf("%i : pere=%i eg=%i ed=%i nbOcc=%i\n", i, Arbre[i].pere, Arbre[i].eg, Arbre[i].ed, Arbre[i].nbOcc);
     }
   }
   
   // Parcours de l'arbre
   parcoursArbre(numDernierNoeud);

   // Compression du fichier source
   compression(argv[1]);
}
