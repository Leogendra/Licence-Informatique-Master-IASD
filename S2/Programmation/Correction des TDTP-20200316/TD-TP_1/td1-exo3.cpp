#include <iostream>

int main()
{
    int n=5, p=9;
    int q;
    float x;
    q= n<p;
    std::cout<<"q="<<q<<std::endl;
    q= n==p;
    std::cout<<"q="<<q<<std::endl;
    q= p%n+p>n;
    std::cout<<"q="<<q<<std::endl;
    x = p/n;
    std::cout<<"x="<<x<<std::endl;
    x = (float)p/n;
     std::cout<<"x="<<x<<std::endl;
    x = (p+0.5)/n;
     std::cout<<"x="<<x<<std::endl;
    x = (int)(p+0.5)/n;
    std::cout<<"x="<<x<<std::endl;

    return 0;
}
