#include <iostream>
int main() {
  int a,b;
  std::cout<<"donnez deux entiers"<<std::endl;
  std::cin>>a;
  std::cin>>b;
  std::cout<<"a="<<a<<" adresse de a="<<&a<<std::endl;
  std::cout<<"b="<<b<<" adresse de b="<<&b<<std::endl;
  // échange des valeurs de a et de b
  int aux=a; a=b; b=aux;
  std::cout<<"après échange :"<<std::endl;
  std::cout<<"a="<<a<<" adresse de a="<<&a<<std::endl;
  std::cout<<"b="<<b<<" adresse de b="<<&b<<std::endl;

  return 0;
}
