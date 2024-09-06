
#include<iostream>
int main(){
	int a,b,c;
	int *p1, *p2;
	a=b=c=3;
	p1=&a;
	p2=&b;
	c=a+b;
	std::cout<<"a="<<a<<" b="<<b<<" c="<<c<<std::endl;
	*p2=b+2;
	std::cout<<"a="<<a<<" b="<<b<<" c="<<c<<std::endl;
	a=*p2**p1;
	std::cout<<"a="<<a<<" b="<<b<<" c="<<c<<std::endl;
	p1=p2;
	std::cout<<"a="<<a<<" b="<<b<<" c="<<c<<std::endl;
	*p2=*p1-a;
	std::cout<<"a="<<a<<" b="<<b<<" c="<<c<<std::endl;
	p2=&(*p1);
	std::cout<<"a="<<a<<" b="<<b<<" c="<<c<<std::endl;
	*p2=*p1+ *(&(*p2));
	std::cout<<"a="<<a<<" b="<<b<<" c="<<c<<std::endl;
	a=b+*p2;
	std::cout<<"a="<<a<<" b="<<b<<" c="<<c<<std::endl;
	
  return 0;
}
