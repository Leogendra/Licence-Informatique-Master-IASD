#include <iostream>
#include "affiche.h"


int dotproduct(int d, int *u, int *v){
  int x=0;
  for (int i=0;i<d;i++)
    x+=u[i]*v[i];
  return x;
}

int main(){
  int u1[4]={1,2,3,4};
  int v1[4]={1,2,3,4};

  int u2[5]={1,10,100,1000,10000};
  int v2[5]={10000,1000,100,10,1};

  std::cout<<"u1.v1: "<<dotproduct(4,u1,v1)<<std::endl;
  std::cout<<"u2.v2: "<<dotproduct(5,u2,v2)<<std::endl;
  
  
  return 0;
}
