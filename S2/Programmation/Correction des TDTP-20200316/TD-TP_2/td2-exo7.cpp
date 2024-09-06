#include <iostream>

int main()
{
    int i,j,k;
    std::cout<<"donnez un entier"<<std::endl;
    std::cin>>i;
    std::cout<<"donnez un entier"<<std::endl;
    std::cin>>j;
    std::cout<<"donnez un entier"<<std::endl;
    std::cin>>k;
    int max, min;
    if (i<=j)
    {
        min=i;
        max=j;
    }
    else
    {
        min=j;
        max=i;
    }
    if (k<=min)
        min=k;
    else if (k>max)
        max=k;
    std::cout<<"3 nombres :"<<i<<" "<<j<<" "<<k<<" min : "<<min<<" max : "<<max<<std::endl;
    return 0;
}
