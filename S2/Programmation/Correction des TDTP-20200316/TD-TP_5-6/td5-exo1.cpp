#include <iostream>

int main(){
  int a,b;
  int *p1,*p2;
  p1=&a;
  p2=&b;

  *p1=5;
  *p2=8;

  std::cout<<"a="<<a<<std::endl
	   <<"b="<<b<<std::endl;

  return 0;
}
