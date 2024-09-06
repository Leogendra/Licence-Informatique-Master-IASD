#include <iostream>
#include "affiche.h"


void prog1(){
  int A[8]={2,0,1,4,6,3,12,9};
  int B[8];

  B[0]=A[0];
  for (int i=1;i<8;i++)
    B[i]=B[i-1]+A[i];
  affiche(B,8);
}
void prog2(){
  int A[8]={2,0,1,4,6,3,12,9};
  int B[8];
  int pair=0,impair=7;
  for (int i=0;i<8;i++)
    if (A[i]%2)
      B[impair--]=A[i];
    else
      B[pair++]=A[i];
  affiche(B,8);
}

int main(){
  prog1();
  prog2();
  
  return 0;
}
