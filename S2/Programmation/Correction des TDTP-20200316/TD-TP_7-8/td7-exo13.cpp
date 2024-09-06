#include <iostream>
#include "affiche.h"

void initSuite(int *T, int n){
  T[0]=0;
  for(int i=1;i<n;i++)
    T[i]=T[i-1]+2*(i-1)+1;
}


int main(){

  int T1[4],T2[10],T3[100];

  initSuite(T1,4);
  affiche(T1,4);
  initSuite(T2,10);
  affiche(T2,10);
  initSuite(T3,100);
  affiche(T3,100);
  
  
  return 0;
}
