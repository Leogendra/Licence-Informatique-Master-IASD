#include <iostream>
#include <iomanip>
int main()
{/*
    int a,b;
    a= (1+2*3+8)/5+7;
    b=3*4*5-4;
    std::cout<<"a="<<a<<", b="<<b<<std::endl;
 */
   
  /*
    int a,b; float c,d;
    a=2;b=3; c=1.5;
    d= a+b+c; // d= 6.5
    a=d; // a= 6
    c=a-b;// c=3
    std::cout<<"a="<<a<<", b="<<b<<std::endl;    
    std::cout<<"c="<<c<<", d="<<d<<std::endl;   
  */

  /* 
    int a,b;
    a= 2;
    b= a++; // affecter puis incrémenter b=2 et a=3
    //b= ++a; // incrémenter puis affecter
    std::cout<<"a="<<a<<", b="<<b<<std::endl; 
    b+= a+2; // b= b+a+2 donc b= 7
    a+= b--; // a= a+b puis b-- donc a= 10 et b= 6
    std::cout<<"a="<<a<<", b="<<b<<std::endl; 
  */

  
    float a,b,c;
    a=1.6;
    b= 2*a+0.8;
    c= b/3;
    std::cout<<std::fixed<<std::setprecision(2);
    std::cout<<"a="<<a<<std::endl; 
    std::cout<<"b="<<b<<std::endl; 
    std::cout<<"c="<<c<<std::endl; 
      
return 0;
}








































