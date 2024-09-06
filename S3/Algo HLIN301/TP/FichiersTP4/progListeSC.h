
/**************************** ListeSC.h **************************************/
/*         Implantation du type Liste d'entiers  Simplement Chainée          */

#ifndef LISTESC_H
#define LISTESC_H

typedef struct cellule {
  int info;
  struct cellule *succ;} CelluleSC;

typedef CelluleSC *ListeSC;


bool estVideLSC(ListeSC L );
/* Res : renvoie 1 si L est la liste vide, renvoie 1 sinon */

ListeSC creerLSC(int e, ListeSC L);
/* Res : renvoie une ListeSC dont le premier élément est e et la suite de la liste L  */

void insererDebutLSC(ListeSC & L, int e);
/* Res : modifie la ListeSC L en y insérant en premiere place l'élément e */

void insererApresLSC(ListeSC & L, ListeSC P, int e);
/* Donnée : L est une ListeSC non vide, P l'adresse d'un élément de L, e un entier */
/* Res : insère dans la liste L après l'élément d'adresse P 1 élément de valeur e  */

void insererFinLSC(ListeSC & L, int e);
/* Res : modifie la listeSC L en y insérant en dernière place l'élément e */

ListeSC predecesseurLSC(ListeSC L, ListeSC P);
/* Donnée : L est une ListeSC non Vide ;  */
/*          P est un pointeur non vide vers une cellule de la liste L  */
/* 	    L != P */
/* Res : renvoie l'adresse de la cellule précédant dans L celle pointée pas P */

void supprimerLSC(ListeSC & L, ListeSC P);
/* Donnée : L est une ListeSC non Vide ;  */
/*          P est un pointeur non vide vers une cellule de la liste L  */
/* Res : modifie L en supprimant de L la cellule pointée pas P */

void supprimerFinLSC(ListeSC & L);
/* Donnée : L est une ListeSC non Vide ;  */
/* Res : modifie L en supprimant son dernier élément */

void supprimerDebutLSC(ListeSC & L);
/* Donnée : L est une ListeSC non Vide ;  */
/* Res : modifie L en supprimant son premier élément */

void afficherLSC(ListeSC L);
/* Res : affiche la liste L */

ListeSC lireLSC();
/* Res : renvoie la ListeSC des éléments saisis au clavier */

ListeSC concatLSC(ListeSC L1, ListeSC L2);
/* Res : renvoie la ListeSC, concaténation des listes L1 et L2, en modifiant le chaînage de L1*/

#endif /*LISTESC_H*/
