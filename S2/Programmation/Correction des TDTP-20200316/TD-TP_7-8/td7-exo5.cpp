#include <iostream>
#include "affiche.h"

double moyenne(double T[], int n){
  double som=0.;
  for(int i=0;i<n;i++){
    som+=T[i];
  }
  return som/n;
}


int main(){
  double A[4]={1,2,3,4};
  double B[8]={1.2,2.3,3.4,4.5,5.6,6.7,7.8,8.9};
  std::cout<<moyenne(A,4)<<std::endl;
  std::cout<<moyenne(B,8)<<std::endl;
  return 0;
}



















double moyenne(double *T, int n){
  double som=0;
  for (int i=0;i<n;i++)
    som+=T[i];
  return som/n;
}
  
int main(){
  double T[4]={12,13,17,5};

  std::cout<<"moyenne= "<<moyenne(T,4)<<std::endl;
  
  return 0;
}
