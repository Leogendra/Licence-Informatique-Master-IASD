#include <iostream>
#include <fstream>
#include <string>
#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include "outilsTab.h"

using namespace std;


int main(int argc, char ** argv)
{
    int* Tab;
    struct triplet res;
    int sm, taille;
    clock_t t1, t2;

    srand(time(NULL));
    if (argc==1){ cout<<"Il manque l'option (1/2/3/4)"<<endl;  return 0 ;}
    switch (atoi(argv[1])){
        case 1 :
            taille=1000;
            Tab=genTab(taille);
            t1=clock(); sm=ssTabSomMax1(Tab,taille); t2=clock();
            cout << "\n Somme Max1 : " << sm << " tps : " <<  (double) (t2-t1)/  (double) CLOCKS_PER_SEC << endl;
            t1=clock(); sm=ssTabSomMax2(Tab,taille); t2=clock();
            cout << "\n Somme Max2 : " << sm << " tps : " <<  (double) (t2-t1)/  (double) CLOCKS_PER_SEC << endl;
            t1=clock(); sm=ssTabSomMax3(Tab,taille); t2=clock();
            cout << "\n Somme Max3 : " << sm << " tps : " <<  (double) (t2-t1)/  (double) CLOCKS_PER_SEC << endl;
            t1=clock(); sm=ssTabSomMax4(Tab,taille); t2=clock();
            cout << "\n Somme Max4 : " << sm << " tps : " <<  (double) (t2-t1)/  (double) CLOCKS_PER_SEC << endl;

        break;
        case 2 :
            fichierTemps("sm1.dat", 1000, 100, ssTabSomMax1);
            fichierTemps("sm2.dat", 1000, 100, ssTabSomMax2);
            fichierTemps("sm3.dat", 1000, 100, ssTabSomMax3);
            fichierTemps("sm4.dat", 1000, 100, ssTabSomMax4);
            system("gnuplot trace1.gnu");
            fichierTemps("sm2.dat", 20000, 1000, ssTabSomMax2);
            fichierTemps("sm3.dat", 20000, 1000, ssTabSomMax3);
            fichierTemps("sm4.dat", 20000, 1000, ssTabSomMax4);
            system("gnuplot trace2.gnu");
            fichierTemps("sm3.dat", 10000000, 1000000, ssTabSomMax3);
            fichierTemps("sm4.dat", 10000000, 1000000, ssTabSomMax4);
            system("gnuplot trace3.gnu");
       break;
        case 3 :
            Tab=genTab(20);
            afficheTab(Tab,20);
            res=indSsTabSomMax(Tab,20);
            cout << "\n somme Max : " << res.somMax << "\n debut souTab : " << res.deb << "\n fin SouTab : " << res.fin << endl;
        break;
        case 4 :
            Tab=genTab100(20);
            afficheTab(Tab,20);
            rangerPairs(Tab,20);
            afficheTab(Tab,20);
        break;
    }
   return 0;
}

