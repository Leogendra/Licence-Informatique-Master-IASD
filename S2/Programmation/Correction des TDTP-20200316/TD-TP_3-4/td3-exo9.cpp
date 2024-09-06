#include <iostream>
#include <iomanip>


// arbre 

int saisieEntier(){
  std::cout<<"Donnez un entier\n";
  int x;
  std::cin>>x;
  return x;
}

void afficheCaractere(char c, int i)
{
    int j ;
    for (j=1;j<=i;j++)
      std::cout<<c;
}

void afficheLigne(int n, int max)
{
    afficheCaractere(' ',(max-n)/2);
    afficheCaractere('*', n);
    afficheCaractere(' ',(max-n)/2);
    std::cout<<std::endl;
}


void afficheRec(int n, int max)
{
    if (n==1)
        afficheLigne(1, max);
    else
    {
        afficheRec(n-2, max);
        afficheLigne(n,max);
    }
}


void affiche(int base)
{
    // base est impair
    int n;
    for (n=1;n<=base;n=n+2)
    {
        afficheCaractere((base-n)/2,'/');
        afficheCaractere(n,'b');
        afficheCaractere((base-n)/2,'/');
	std::cout<<std::endl;
    }
}

int main()
{
    int a= saisieEntier();
    //affiche(a);
      afficheRec(a,a);
    return 0;
}
