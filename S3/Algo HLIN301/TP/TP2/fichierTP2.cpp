#include <iostream>
#include <fstream>
#include <string>
#include <stdio.h>
#include <stdlib.h>
#include <assert.h>
#include "progListeSC.h"
using namespace std;

//  DERNIERLSC ET ESTTRIEELSC 
// Res : Renvoie l'adresse de la derniere cellule de la liste non vide L 
ListeSC dernierLSC(ListeSC L){
  assert(L != NULL);

  return L;
}

//   Res : Renvoie 1 si L est une ListeSC triee, 0 sinon 
bool estTrieeLSC(ListeSC L){
  return true;

}


//  OTERREPETITION 
//    Res : Supprime de la ListeSC L tous les elements consecutifs egaux 
//          Opere en modifiant le chainage de la ListeSC L 
// version iterative 
void oterRepetitionLSC(ListeSC L){

  return;
}

//    Res : Supprime de la ListeSC L tous les elements consecutifs egaux 
//          Opere en modifiant le chainage de la ListeSC L 
//    version recursive 
void oterRepetitionLSCR(ListeSC L){

  return;
}


//   CONCATENATION DE 2 LISTES  
//   Res : Modifie la liste L1 en la concatenant avec la liste l2 
//         Opere en modifiant le chainage de la listeSC L1 
//   Version utilisant la fonction dernierLSC 
void concatLSC(ListeSC& L1,ListeSC L2){
  return;
}

//   Res : Renvoie la ListeSC obtenue par concatenation des ListeSC L1 et L2 
//         Opere en recopiant les elements des 2 listeSC L1 et L2 
//   complexite : ??? 
ListeSC concatLSCCopie(ListeSC L1,ListeSC L2){

  return NULL;
}


