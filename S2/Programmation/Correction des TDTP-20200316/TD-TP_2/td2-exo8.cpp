#include <iostream>

int main()
{
    int x,n;
    std::cout<<"donnez l'opérande"<<std::endl;
    std::cin>>x;
    std::cout<<"donnez l'exposant"<<std::endl;
    std::cin>>n;
    int p= 1;int i;
    for (i=1;i<=n; i++)
      p=p*x;
    std::cout<<x<<" puissance "<<n<<" = "<<p<<std::endl;
    return 0;
}
