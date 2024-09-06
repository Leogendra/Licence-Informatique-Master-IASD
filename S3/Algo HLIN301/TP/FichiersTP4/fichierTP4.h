/**************************** fichierTP4.h **************************************/
#ifndef FicTP4_H
#define FICTP4_H

using namespace std;


typedef struct noeud {
  int info;
  struct noeud *sag;
  struct noeud *sad;} NoeudSC;
typedef NoeudSC *ArbreBin;

void afficheConsole(ArbreBin A);

ArbreBin creerArbreBin(int e, ArbreBin G, ArbreBin D);
  /* Res : renvoie une ArbreBin dont la racine vaut e, le sag G et le sad D   */
  

void dessinerAB(ArbreBin A, const char * nomFic, string titre);
/* Ecrit l'arbre A dans le fichier nomFic avec titre comme légende */


int sommeNoeuds(ArbreBin A);
  /* renvoie la somme des etiquettes des noeuds de l arbre binaire A */
 

int profMinFeuille(ArbreBin A);
/* renvoie la profondeur minimum des feuilles de l'arbre A ; A est non vide */
    
ListeSC parcoursInfixe(ArbreBin A);
  /* renvoie la liste composee des etiquettes des noeuds de l'arbre A ordonnée
     selon l'ordre infixe */

void effeuiller(ArbreBin& A);
  /* modifie l'arbre A en supprimant ses feuilles */
  
void tailler(ArbreBin& A, int p);
  /* modifie l'arbre A, en supprimant ses noeuds de profondeur au moins p ; p est un entier positif ou nul */
  
void tronconner(ArbreBin& A);
  /* modifie l'arbre A, en supprimant les noeuds dont un seul sous-arbre est vide */
  
ArbreBin genereAB(int n);

bool estParfait(ArbreBin A);
  // Vérifie si A est un arbre binaire parfait



#endif
