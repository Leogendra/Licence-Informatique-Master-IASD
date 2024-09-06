#include <iostream>

void f1(){
  std::cout<<"bonjour"<<std::endl;
}

void f2(unsigned int n){
  for(unsigned int i=0;i<n;i++){
    f1();
  }
}

int main(){
  f2(10);
  return 0;
}
