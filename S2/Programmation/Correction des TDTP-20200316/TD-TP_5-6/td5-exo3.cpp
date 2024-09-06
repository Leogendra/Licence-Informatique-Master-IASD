#include <iostream>

void plusUn(int* x){ (*x)++;}

int main(){
  int a;
  std::cout<<"Entrez un entier: ";
  std::cin>>a;
  plusUn(&a);
  std::cout<<"plusUn -> "<<a<<std::endl;;
  
  
  return 0;
}
