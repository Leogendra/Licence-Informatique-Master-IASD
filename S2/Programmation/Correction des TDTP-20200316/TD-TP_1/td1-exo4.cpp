#include <iostream>

int main()
{
    int N;
    std::cout <<"Donnez un entier positif non nul"<<std::endl;
    std::cin >> N;
    // l'utilisateur saisit la valeur de N
    int i,j,s;
    for (i=4; i<=N; i++)
    {
      // on teste tous les entiers à partir de 4 car 1, 2 et 3 sont premiers
      // et ce jusqu'à N
      //on initialise s à 1
      s = 1 ;
	// on cherche tous les diviseurs de i distincts de 1 et de i
	// et on les ajoute à s
        for (j=2; j<i; j++)
            if (i%j==0)
                s+=j;
	// on compare la somme s de tous les diviseurs propres +1 à i
	// si i = somme des divisuers propres +1 alors i est parfait
        if (s==i)
	  std::cout<<i<<" est parfait"<<std::endl;
    }
    return 0;
}
