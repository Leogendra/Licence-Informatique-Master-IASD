#include <iostream>

int* max(int* a,int *b, int *c){
  if (*a>=*b && *a>=*c) return a;
  if (*b>=*a && *b>=*c) return b;
  if (*c>=*a && *c>=*b) return c;
}


int main(){
  int x=2,y=4,z=3;
  std::cout<<&x<<" "<<&y<<" "<<&z<<std::endl;
  std::cout<<"le max est à l'adresse: "<<max(&x,&y,&z)<<std::endl;
  return 0;
}
