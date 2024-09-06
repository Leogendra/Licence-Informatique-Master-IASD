#include <iostream>
#include "affiche.h"

void initSuite(int *T, int n){
  T[0]=0;
  for(int i=1;i<n;i++)
    T[i]=T[i-1]+2*(i-1)+1;
}

void initSquare(int *T, int n){
  for(int i=0;i<n;i++)
    T[i]=i*i;
}

bool areEqual(int *T1, int n1, int *T2, int n2){

  if (n1!=n2) return false;

  int i=0;
  while(i<n1 && T1[i]==T2[i]) i++;
  return i==n1;
}


int main(){

  int T1[100], T2[100];
  initSuite(T1,100);
  initSquare(T2,100);

  std::cout<<"Les tableaux sont egaux ? "<<std::boolalpha<<areEqual(T1,100,T2,100)<<std::endl;
  
  return 0;
}
