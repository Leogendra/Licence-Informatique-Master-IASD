#include <iostream>
#include "affiche.h"

void shift(int T[], int n){
  int tmp;
  tmp=T[n-1];
  for (int i=n-1;i>0;i--)
    T[i]=T[i-1];
  T[0]=tmp;
}

void permutation(int T[], int n, int s){
  if (s<0)
    s=s+n;
  for (int j=0;j<s;j++)
    shift(T,n);
}

int main(){
  int d;
  int T[6]={1,2,3,4,5,6};
  affiche(T,6);
  std::cout<<"Entrer une permutation :";
  std::cin>>d;
  permutation(T,6,d);
  affiche(T,6);
  
  return 0;
}
