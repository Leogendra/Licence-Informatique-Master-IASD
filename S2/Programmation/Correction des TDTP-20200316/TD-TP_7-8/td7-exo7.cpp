#include <iostream>
#include "affiche.h"

void binary(int Tx[32], int x){
  for (int i=31;i>=0;i--){
    Tx[i]=x%2;
    x/=2;
  }     
}
int main(){
  int T[32];
  int x;
  std::cin>>x;
  std::cout<<"binary("<<x<<")=";
  binary(T,x);
  affiche(T,32);
  
  return 0;
}
