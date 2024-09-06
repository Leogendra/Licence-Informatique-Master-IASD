#include <iostream>
#include <sstream>
#include <fstream>
#include <string>
#include <stdlib.h>
#include <assert.h>
#include "outilsTab5.h"
using namespace std;

 
int main(int argc, char ** argv) 
{ 
  int *Tab, *Tab2;
  size_t taille;
  int q; 
  srand(time(NULL));
  cout << "Numéro de la question traitée (1/2/3) ? ";
  cin >> q;
  switch (q){
  case 1 : 
    taille=10; 
    Tab=genTab(taille); 
    afficheTab(Tab,taille);

    Tab2=copieTab(Tab,taille); triInsertion(Tab2,taille); 
    cout << "\n Tri par insertion\n";afficheTab(Tab2,taille);
    
    Tab2=copieTab(Tab,taille); triSelection(Tab2,taille); 
    cout << "\n Tri par sélection\n";afficheTab(Tab2,taille);

    Tab2=copieTab(Tab,taille); triParTas(Tab2,taille); 
    cout << "\n Tri par tas\n";afficheTab(Tab2,taille);    

    Tab2=copieTab(Tab,taille); triRapide1(Tab2,taille); 
    cout << "\n Tri Rapide\n";afficheTab(Tab2,taille);

    Tab2=copieTab(Tab,taille); triFusion(Tab2,taille); 
    cout << "\n Tri par fusion\n";afficheTab(Tab2,taille);

    break;
  case 2 :
    fichierTemps("triInsertion.dat", 20000, 2000, triInsertion);
    fichierTemps("triSelection.dat", 20000, 2000, triSelection);
    fichierTemps("triParTas.dat", 20000, 2000, triParTas);
    fichierTemps("triFusion.dat", 20000, 2000, triFusion);
    fichierTemps("triRapide1.dat", 20000, 2000, triRapide1);
    system("gnuplot trace1.gnu");
    
    fichierTemps("triParTas.dat", 500000, 50000, triParTas);
    fichierTemps("triFusion.dat", 500000, 50000, triFusion);
    fichierTemps("triRapide1.dat", 500000, 50000, triRapide1);
    system("gnuplot trace2.gnu");
    break;
  case 3 :
    taille=1000000;
    Tab=genTab(taille); 
    cout << "Nombre de valeurs différentes : " << nbValDiff(Tab,taille) << endl;
    break;
  } 
  return 0; 
}
 
