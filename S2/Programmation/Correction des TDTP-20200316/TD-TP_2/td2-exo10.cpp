#include <iostream>

int main()
{
    int i;
    int compteur = 0;
    int somme=0;
    do
    {
        std::cout<<"donnez un entier"<<std::endl;
	std::cin>>i;
        if (i>0)
            {compteur ++;somme=somme+i;}
    }
    while (i>=0);
    std::cout<<"nombre de valeurs positives saisies "<<compteur<<std::endl;
    std::cout<<"moyenne : "<<(somme+0.0)/compteur<<std::endl;
    return 0;
}
