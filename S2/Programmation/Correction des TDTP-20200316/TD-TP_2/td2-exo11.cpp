#include <iostream>
#include <cmath>

int main()
{
  double  a;
  std::cout<<"donnez un réel"<<std::endl;
  std::cin>>a;
  double xi=a;
  double y;double aux;
  int n;
  std::cout<<"donnez un entier"<<std::endl;
  std::cin>>n;
    do
    {
        y=xi;
        xi=xi/2+a/(2*xi);
	std::cout<<"terme : "<<xi<<std::endl;
        if (y>xi)
            aux = y-xi;
        else aux=xi-y;
    }
    while (aux >(1.0/n));
    std::cout<<"resultat algo = "<<xi;
    std::cout<<" racine carree de "<<a<<" = "<<sqrt(a)<<std::endl;
    return 0;
}
