#include <iostream>
#include "affiche.h"

void affiche(int *tab, int n){
  for (int i=0;i<n;i++)
    std::cout<<tab[i]<<" ";
  std::cout<<std::endl;
}



int main(){

  int T[7]={10,11,34,2,45,49,67};
  affiche(T,7);  
  
  return 0;
}
