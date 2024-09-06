#include <iostream>
#include "affiche.h"

void initSquare(int *T, int n){
  for(int i=0;i<n;i++)
    T[i]=i*i;
}


int main(){

  int T[10];
  initSquare(T,10);
  affiche(T,10);

  
  
  return 0;
}
