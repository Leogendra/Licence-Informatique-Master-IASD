#include <iostream>
#include "affiche.h"

int som(int *T, int n){
  int s=0;
  for(int i=0;i<n;i++)
    { s+=T[i]; }
  return s;
}
int main(){
  std::cout<<"donnez la taille du tableau"<<std::endl;
  int n;
  std::cin>>n;  
  int *T=new int[n]; 
  for (int i=0;i<n;i++)
    T[i]=i;
  std::cout<<som(T,n)<<std::endl;

  delete[] T;
  return 0;
}
