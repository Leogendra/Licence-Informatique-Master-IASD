#include <iostream>
#include "affiche.h"

int* extract(int *T, int n, int a, int b){
  int k=b-a+1;
  int *T2=new int[k];
  for(int i=0;i<k;i++)
    {  T2[i]=T[i+a]; }
  return T2;
}
int main(){
  int T[]={1,2,3,4,5,6,7,8};
  int a,b;
  affiche(T,8);
  std::cout<<"Entrez a et b (a<=b): "<<std::endl;
  std::cin>>a>>b;
  int *A=extract(T,8,a,b);
  affiche(A,b-a+1);
  delete[] A;
  return 0;
} 
