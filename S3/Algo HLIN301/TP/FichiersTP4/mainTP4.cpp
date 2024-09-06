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


/*****************************************************************************/
/*                                                                           */
/*                              main                                         */
/*                                                                           */
/*****************************************************************************/
int main(int argc, char *argv[]){
  int q,i,typAf;
  ArbreBin A,B,C,D;
  ostringstream stre;
  ListeSC L;
  string  chaine;
  A=creerArbreBin(8,
		  creerArbreBin(7,
				creerArbreBin(4,NULL,NULL),
				creerArbreBin(9,NULL,NULL)),
		  creerArbreBin(3,NULL,NULL));

  B=creerArbreBin(8,
		  creerArbreBin(2,
				creerArbreBin(4,NULL,NULL),
				creerArbreBin(9,
					      NULL,
					      creerArbreBin(1,
							    NULL,
							    creerArbreBin(7,
									  creerArbreBin(11,NULL,NULL),
									  creerArbreBin(5, 
											NULL,
											NULL))))),
		  creerArbreBin(3,
				creerArbreBin(12,
					      creerArbreBin(6,NULL,NULL),
					      NULL),
				creerArbreBin(9,NULL,NULL)));
  C=NULL;
  C= creerArbreBin(1,C,C);
  if (argc==1){ cout<<"Il manque l'option (1 : affichage dotty ou 2 : affichage console"<<endl;  return 0 ;}
  typAf=atoi(argv[1]);
  cout << "Numero de la question traitee (1/2/3/4/5/6/7) ? ";
  cin >> q;
  switch (q){
  case 1 :
    dessinerAB(A,"arbre.dot","Arbre Bin");
    cout << "Somme des noeuds de l'arbre :"<< sommeNoeuds(A) << endl;
    cout << "Profondeur minimum des feuilles de l'arbre : " << profMinFeuille(A) << endl;
    if(typAf==1) system("dotty arbre.dot");
    else
      if(typAf==2) afficheConsole(A);
     break;
  case 2 :
    dessinerAB(A,"arbre.dot","Arbre Bin");    
    L=parcoursInfixe(A);
    cout << "Liste des noeuds de l'arbre en ordre infixe : ";
    afficherLSC(L);
    if(typAf==1) system("dotty arbre.dot");
    else
      if(typAf==2) afficheConsole(A);
    break;
  case 3 :
    dessinerAB(B,"arbre.dot","Arbre Bin");
    if(typAf==1) system("dotty arbre.dot&");
    else if(typAf==2) afficheConsole(B);
    effeuiller(B);
    dessinerAB(B,"arbre2.dot","Arbre Bin effeuille");
    if(typAf==1) system("dotty arbre2.dot");
    else
      if(typAf==2) afficheConsole(B);
    break;
  case 4 :
    dessinerAB(B,"arbre.dot","Arbre Bin");
    if(typAf==1) system("dotty arbre.dot&");
    else if(typAf==2) afficheConsole(B);
    cout << " Donner une profondeur (entier positif) :";
    cin >> i;
    tailler(B,i);
    stre << i;
    chaine = stre.str();
    chaine = "Arbre Bin taille a la profondeur " + chaine;
    dessinerAB(B,"arbre2.dot",chaine);
    if(typAf==1) system("dotty arbre.dot&");
    else if(typAf==2) afficheConsole(B);
     break;
  case 5 :
    cout << " Donner un entier positif :";
    cin >> i;
    stre << i;
    chaine = "Arbre Bin  " + stre.str();
    D=genereAB(i);
    dessinerAB(D,"arbre.dot", "Arbre Bin  " + stre.str());
    if(typAf==1) system("dotty arbre.dot&");
    else if(typAf==2) afficheConsole(D);
     break;
  case 6 :
    dessinerAB(B,"arbre.dot","Arbre Bin");
    if(typAf==1) system("dotty arbre.dot&");
    else if(typAf==2) afficheConsole(B);
    tronconner(B);
    dessinerAB(B,"arbre2.dot","Arbre tronconne");
    if(typAf==1) system("dotty arbre.dot&");
    else if(typAf==2) afficheConsole(B);
    break;
  case 7 :
    A=genereAB(7);
    chaine= estParfait(A) ? "Arbre parfait" : "Arbre non parfait";
    dessinerAB(A,"arbre.dot",chaine);
    if(typAf==1) system("dotty arbre.dot&");
    else if(typAf==2) afficheConsole(A);
 
    B=genereAB(8);
    chaine= estParfait(B) ? "Arbre parfait" : "Arbre non parfait";
    dessinerAB(B,"arbre2.dot",chaine);
    if(typAf==1) system("dotty arbre.dot&");
    else if(typAf==2) afficheConsole(B);
     break;
  }
  return 0;
}
