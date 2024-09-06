
/**************************** ListeSC.h **************************************/
/*         Implantation du type Liste d'entiers  Simplement Chain�e          */

#ifndef LISTESC_H
#define LISTESC_H

typedef struct cellule {
  int info;
  struct cellule *succ;} CelluleSC;

typedef CelluleSC *ListeSC;


bool estVideLSC(ListeSC L );
/* Res : renvoie 1 si L est la liste vide, renvoie 1 sinon */

ListeSC creerLSC(int e, ListeSC L);
/* Res : renvoie une ListeSC dont le premier �l�ment est e et la suite de la liste L  */

void insererDebutLSC(ListeSC & L, int e);
/* Res : modifie la ListeSC L en y ins�rant en premiere place l'�l�ment e */

void insererApresLSC(ListeSC & L, ListeSC P, int e);
/* Donn�e : L est une ListeSC non vide, P l'adresse d'un �l�ment de L, e un entier */
/* Res : ins�re dans la liste L apr�s l'�l�ment d'adresse P 1 �l�ment de valeur e  */

void insererFinLSC(ListeSC & L, int e);
/* Res : modifie la listeSC L en y ins�rant en derni�re place l'�l�ment e */

ListeSC predecesseurLSC(ListeSC L, ListeSC P);
/* Donn�e : L est une ListeSC non Vide ;  */
/*          P est un pointeur non vide vers une cellule de la liste L  */
/* 	    L != P */
/* Res : renvoie l'adresse de la cellule pr�c�dant dans L celle point�e pas P */

void supprimerLSC(ListeSC & L, ListeSC P);
/* Donn�e : L est une ListeSC non Vide ;  */
/*          P est un pointeur non vide vers une cellule de la liste L  */
/* Res : modifie L en supprimant de L la cellule point�e pas P */

void supprimerFinLSC(ListeSC & L);
/* Donn�e : L est une ListeSC non Vide ;  */
/* Res : modifie L en supprimant son dernier �l�ment */

void supprimerDebutLSC(ListeSC & L);
/* Donn�e : L est une ListeSC non Vide ;  */
/* Res : modifie L en supprimant son premier �l�ment */

void afficherLSC(ListeSC L);
/* Res : affiche la liste L */

ListeSC lireLSC();
/* Res : renvoie la ListeSC des �l�ments saisis au clavier */

ListeSC concatLSC(ListeSC L1, ListeSC L2);
/* Res : renvoie la ListeSC, concat�nation des listes L1 et L2, en modifiant le cha�nage de L1*/

#endif /*LISTESC_H*/
