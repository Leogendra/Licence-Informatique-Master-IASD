#include <iostream>
#include "affiche.h"

void minmax(int *T, int n, int *min, int *max){
  *min=*max=*T;
  for (int i=1;i<n;i++){
    if (T[i]>*max) *max=T[i];
    if (T[i]<*min) *min=T[i];
  }  
}

int main(){
  int T[7]={10,11,34,2,45,49,67};
  int min,max;
  minmax(T,7,&min,&max);

  std::cout<<"le max et le min de ";
  affiche(T,7);
  std::cout<<" sont: "<<min<<" et "<<max<<std::endl;
  
  
  return 0;
}
