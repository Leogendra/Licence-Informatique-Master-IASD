#include <iostream>

int main(){
  int T[10];
  for(int i=0;i<10;i++){
    std::cout<<"Entrez la valeur de la case "<<i<<" :";
    std::cin>>T[i];
  }
  for(int i=9;i>=0;i--)
    std::cout<<"case("<<i<<")="<<T[i]<<std::endl;
  
  return 0;
}
