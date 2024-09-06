#include <iostream>

int main(){

  float c,f;
  std::cout<<"donnez une température en degrés Fahrenheit"<<std::endl;
  std::cin>>f;
  c= (5.0/9)*(f-32);
  std::cout<<f<<" degrés Fahrenheit = "<<c<<" degrés Celsius"<<std::endl;
  //on fait l'inverse maintenant
  std::cout<<"donnez une température en degrés Celsius"<<std::endl;
  std::cin>>c;
  f= (9.0/5)*c +32;
  std::cout<<c<<" degrés Celsius= "<<f<<" degrés Fahrenheit"<<std::endl;
  return 0;

}
