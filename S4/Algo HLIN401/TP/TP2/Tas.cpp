#include <iostream>
#include "Tas.h"

using namespace std;


// ====================
//  TAS ET TRI PAR TAS
// ====================

void afficher(int n, int* T) {
  std::cout<<"[";
  for (int i=0; i<n; i++) {
    if (i!=0) {std::cout<<",";}
    std::cout<<T[i];
  }
  std::cout<<"]";
} 



bool estTasMax(int n, int* T) {
    int i=1;
    while (i<n) {
      if (T[i]>T[(i-1)/2]) {return false;}
      i++;
    }
    return true;
  }



bool estTasMin(int n, int* T) {
    int i=1;
    while (i<n) {
      if (T[i]<T[(i-1)/2]) {return false;}
      i++;
    }
    return true;
  }



void tableauManuel(int n, int* T) {
  std::cout<<"entrez les "<<n<<" valeurs du tableau :"<<endl;
  for (int i=0; i<n; i++) {
    std::cout<<"noeud à l'indice "<<i<<" : ";
    std::cin>>T[i];
    }
}



void tableauAleatoire(int n, int* T, int m, int M)  {
  if (m>M) {
    cout<<"Les calculs sont pas bons Kévin"<<endl;
    //inversion des valeurs
    int tmp=m;
    m=M;
    M=tmp;
  }
  for (int i=0; i<n; i++) {T[i]=(rand()%(M-m+1))+m;}
}



void entasser(int n, int* T, int i) {
  int m,g,d;
  while ((i*2+1)<n) {
    m=i;
    g=(i*2)+1;
    d=(i*2)+2;
    if (T[g]>T[m]) {m=g;}
    if (d<n && T[d]>T[m]) {m=d;}
    if (m!=i) {
      int tmp=T[i];
      T[i]=T[m];
      T[m]=tmp;
      i=m;
    }
    else {i=n;}
  }
}



void tas(int n, int* T) {
    for (int i=(n/2)-1; i>=0; i--) {entasser(n,T,i);}
}



int* trier(int n, int* T) {
  int* Ttrie = new int[n];
  tas(n,T);
  
  for (int i=n-1; i>-1; i--) {
    Ttrie[i]=T[0];
    T[0]=T[n-1];
    n=n-1;
    //pas besoin de remonter le noeud 0
    entasser(n,T,0);
  }
  return Ttrie;
}



void trierSurPlace(int n, int* T) {//on veux que T[filsG]>=T[filsD]
  tas(n,T);
  for (int i=n-1; i>-1; i--) {
    int tmp=T[n-1];
    T[n-1]=T[0];
    T[0]=tmp;
    n--;
    entasser(n,T,0);
  }
}

