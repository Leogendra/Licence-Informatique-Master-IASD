#include <iostream>
#include <sstream>
#include <fstream>
#include <string>
//#include <stdio.h>
#include <stdlib.h>
#include <assert.h>
#include "progListeSC.h"
#include "fichierTP4.h"
using namespace std;


int nbBrDrNd(ArbreBin A){return A->info/100;}

void nbBrDr(ArbreBin A){
  if (A!=NULL){
    nbBrDr(A->sag);
    nbBrDr(A->sad);
    if (A->sag==NULL){
      if (A->sad!=NULL) A->info=A->info+100*(nbBrDrNd(A->sad)+1);}
    else
      if (A->sad==NULL) A->info=A->info+100*nbBrDrNd(A->sag);
      else A->info=A->info+100*max(nbBrDrNd(A->sag),nbBrDrNd(A->sad)+1);
  }}

void affc(ArbreBin A, int prof){
  if (A != NULL){
    affc(A->sad,prof+1);
    for(int i=1; i<=prof; i++)
      cout <<"   ";
    //cout<<"I__";  


    cout << A->info<<endl;
    affc(A->sag,prof+1);
  }
}

void afficheConsole(ArbreBin A){
  cout <<"arbre"<<endl;

  affc(A,0);

  cout << "---------------------" << endl;
}

ArbreBin creerArbreBin(int e, ArbreBin G, ArbreBin D){
  /* Res : renvoie une ArbreBin dont la racine vaut e, le sag G et le sad D   */
  ArbreBin A = new NoeudSC;
  A->info=e;  A->sag=G;  A->sad=D;
  return A;}
  
void codageABdot(ofstream& fichier, ArbreBin A){
  if (A != NULL){ 
    fichier << (long) A << " [label=\""  << A->info << "\" ] ;\n";
    if (A->sag != NULL) {
      fichier << (long)A << " -> " << (long)(A->sag) <<  " [color=\"red\",label=\"g\" ] ;\n";
      codageABdot(fichier,A->sag);} 
    if (A->sad != NULL) {
      fichier << (long)A << " -> " << (long)(A->sad) << " [color=\"blue\",label=\"d\" ] ;\n";
      codageABdot(fichier,A->sad);}
  }
  return;}
    

void dessinerAB(ArbreBin A, const char * nomFic, string titre){
  ofstream f(nomFic);
  if (!f.is_open()){
   cout << "Impossible d'ouvrir le fichier en écriture !" << endl;
  }
  else {
    f<< "digraph G { label = \""<< titre << "\" \n";
    codageABdot(f,A);
    f << "\n }\n" ;
    f.close();}
  return;}


/* A COMPLETER */
int sommeNoeuds(ArbreBin A){
  /* renvoie la somme des etiquettes des noeuds de l arbre binaire A */
  /* A COMPLETER */
  return 0;}

int profMinFeuille(ArbreBin A){
  /* renvoie la profondeur minimum des feuilles de l'arbre A ; A est non vide */
  assert(A!=NULL);
  /* A COMPLETER */
  return 0;}
    
ListeSC parcoursInfixe(ArbreBin A){
  /* renvoie la liste composee des etiquettes des noeuds de l'arbre A ordonnée
     selon l'ordre infixe */
  /* A COMPLETER */
  return NULL;}

void effeuiller(ArbreBin& A){
  /* modifie l'arbre A en supprimant ses feuilles */
  /* A COMPLETER */
  return;}

void tailler(ArbreBin& A, int p){
  /* modifie l'arbre A, en supprimant ses noeuds de profondeur au moins p ; p est un entier positif ou nul */
  /* A COMPLETER */
  return;}

void tronconner(ArbreBin& A){
  /* modifie l'arbre A, en supprimant les noeuds dont un seul sous-arbre est vide */
  /* A COMPLETER */
  return;}

ArbreBin genereAB(int n){
  /* A COMPLETER */
  return NULL;}


bool estParfait(ArbreBin A){
  // Vérifie si A est un arbre binaire parfait
  return true;
}

