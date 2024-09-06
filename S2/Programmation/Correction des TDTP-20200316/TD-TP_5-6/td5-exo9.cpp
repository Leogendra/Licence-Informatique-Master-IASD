#include <iostream>

void echange(int *a, int *b){
  int t=*a;
  *a=*b;
  *b=t;
}

int main(){
  int x,y;
  int *p;
  x=2;y=3;
  std::cout<<x<<" "<<y<<std::endl;
  p=&y;
  echange(&x,p);
  std::cout<<x<<" "<<y<<std::endl;
  return 0;
}
