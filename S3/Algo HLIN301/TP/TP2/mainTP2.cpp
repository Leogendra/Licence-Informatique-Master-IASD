#include <iostream>
#include <fstream>
#include <string>
#include <stdio.h>
#include <stdlib.h>
//#include <assert.h>
#include "progListeSC.h"
#include "fichierTP2.h"
using namespace std;


int main(int argc, char *argv[]){
  boolalpha(cout);
  ListeSC l1,l2,l3;
  int q;
  
  cout << "Numero de la question traitee (1/2/3/4/5) ? ";
  cin >> q;
  switch (q){
  case 1 :  // Test des operations de base sur les listes 
    l1 = lireLSC();
    
    // insertion d'un element de valeur 33 en premiere position de la liste l1 
    // Completez 

    
    cout << "Insertion de 33 en première position "; afficherLSC(l1);
    
    // insertion d'un element de valeur 11 en derniere position de la liste l1 
    // en utilisant insererFinLSC 
    // Completez 

    
    cout << "Insertion de 11 en derniere position "; afficherLSC(l1);
    
    // insertion d'un element de valeur 22 en 2eme position de la liste l1 
    // Completez 
    
    
    cout << "Insertion de 22 en 2eme position ";afficherLSC(l1);
    
    // Suppression du 2eme element de la liste en utilisant supprimerLSC
    // Completez 
    

    cout << "Suppression du 2eme element "; afficherLSC(l1);
    
    // Suppression du 2eme element de la liste sans utiliser supprimerLSC
    // Completez 


    cout << "Suppression du 2eme element "; afficherLSC(l1);
    
    // Inversion des valeurs des 2 premiers elements 
    // en modifiant les champs info (sans modifier le chainage)
    // Completez 

 
    cout << "Inversion des valeurs des 2 premiers elements " ; afficherLSC(l1);
    
    // Inversion des 2 premiers elements 
    // en modifiant les champs succ (le chainage)
    // Completez 
    

    cout << "Inversion des 2 premiers elements "; afficherLSC(l1);
    break;
    
  case 2 : // Test des fonctions  estTrieeLSC et dernierLSC 
    l1 = lireLSC();
    if (estTrieeLSC(l1))  cout << "Cette liste est triee\n";
    else cout << "Cette liste n'est pas triee\n";
    if (l1 != NULL)
      cout << "La valeur de son dernier element est " << dernierLSC(l1)->info << endl;
    break;
    
  case 3: // Test des fonctions oterRepetitionLSC 
    l1 = lireLSC();
    oterRepetitionLSC(l1);
    cout << "Liste sans repetition (version iterative) :\n";
    afficherLSC(l1);
    l1 = lireLSC();
    oterRepetitionLSCR(l1);
    cout << "Liste sans repetition (version recursive) :\n";
    afficherLSC(l1);
    break;
    
  case 4 :  // Test de la premiere fonction  de concatenation de listes 
    l1 = lireLSC();
    l2 = lireLSC();
    concatLSC(l1,l2);
    cout << "Concatenation des 2 listes (en modifiant le chainage) :\n"; afficherLSC(l1);
    if ((l1 != NULL) && (l2 != NULL) )
      cout << "Adresse derniere cellule de l1 : " << (void *) dernierLSC(l1) << ", de l2 : "<< (void *) dernierLSC(l2) << endl;
    cout << " Ajout de 44 en fin de la liste l1\n";
    insererFinLSC(l1,44);
    cout << "Nouvelle valeur de l1:"; afficherLSC(l1); cout << endl;
    cout << "Nouvelle valeur de l2: "; afficherLSC(l2); cout << endl;
    break;
    
  case 5 :  // Test des fonctions  de concatenation de listes 
    l1 = lireLSC();
    l2 = lireLSC();
    l3 = concatLSCCopie(l1,l2);
    cout << "Concatenation des 2 listes (par recopie des listes) : "; afficherLSC(l3); cout << endl;
    if ((l1 != NULL) && (l2 != NULL) )
      cout << "Adresse derniere cellule de l1 : " << (void *) dernierLSC(l1) << " , de l2 : "  << (void *) dernierLSC(l2) << ", de l3 : " << (void *) dernierLSC(l3) << endl;
    cout << " Ajout de 55 en fin de la liste l1\n";
    insererFinLSC(l1,55);
    cout << "Nouvelle valeur de l1: "; afficherLSC(l1);  cout << endl;
    cout << "Nouvelle valeur de l2: "; afficherLSC(l2); cout << endl;
    cout << "Nouvelle valeur de l3: "; afficherLSC(l3); cout << endl;
    break;
  }
  return 0;
}
