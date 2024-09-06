#include <iostream>
#include <iomanip>
int main(){
  int x;
  std::cout<<"Entrez un entier: "<<std::endl;  
  std::cin>>x;
  std::cout<<std::setprecision(10);
  std::cout<<"Le codage binaire de "<<*(float*)&x<<" est le même que l'entier "<<x<<std::endl;



  float y;
  std::cout<<"Entrez un flottant: "<<std::endl;  
  std::cin>>y;
  std::cout<<std::setprecision(10);
  std::cout<<"Le codage binaire de "<<*(int*)&y<<" est le même que le flottant "<<y<<std::endl;





  return 0;
}
