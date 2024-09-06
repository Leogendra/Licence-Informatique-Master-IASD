#include <iostream>
#include <cmath>

void f1(){
  int a,b;
  std::cout<<"distance en int: "<<&a-&b<<std::endl;
  char *p1= (char*)&a,*p2=(char*)&b;
  std::cout<<"distance en octet: "<<std::abs(p2-p1)<<std::endl;
}

void f2(){
  double a,b;
  std::cout<<"distance en double: "<<&a-&b<<std::endl;
  char *p1= (char*)&a,*p2=(char*)&b;
  std::cout<<"distance en octet: "<<std::abs(p2-p1)<<std::endl;

}

void f3(){
  int b;
  double a;

  char *p1= (char*)&a,*p2=(char*)&b;
  std::cout<<"distance en octet: "<<std::abs(p2-p1)<<std::endl;

}

int main(){
  f1();
  f2();
  f3();
  return 0;
}
