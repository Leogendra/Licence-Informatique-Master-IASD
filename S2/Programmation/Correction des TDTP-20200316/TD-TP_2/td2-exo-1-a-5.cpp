#include <iostream>
#include <cmath>
int main()
{
  /* 
    // exo1
    int a,b,t;
    t=0;
    std::cout<<"Donnez deux entiers"<<std::endl;
    std::cin>>a>>b;

    if (a>b)
    {
        t=a;
        a=b;
        b=t;
    }
    std::cout<<"le max est "<<b<<" le min est "<<a<<std::endl;
  */
   
  /*
 //exo2
  int j;
  std::cout<<"Donnez un entier"<<std::endl;
  std::cin>>j;
  if (j>=0)
    std::cout<<"valeur absolue de "<<j<<" = "<<j<<std::endl;
    else
    std::cout<<"valeur absolue de "<<j<<" = "<<-j<<std::endl;;
  */
  
   // exo3
  /*   
    int n,prod, somme;
    prod = 1;
    somme=0;
    for (n=1;n<=10; n++)
    {
            somme=somme+n;
            prod = prod*n;
        }
    std::cout<<"somme= "<<somme<<" produit = "<<prod<<std::endl;
  */
  //exo4
  /*    
 int n,prod, somme;
    prod = 1;
    somme=0;
    for (n=1;n<=10; n++)
    {
        if (n%2==0)
        {
            somme=somme+n;
            prod = prod*n;
        }
    }
    std::cout<<"somme= "<<somme<<" produit = "<<prod<<std::endl;
    
    
    prod = 1;
    somme= 0;
     for (n=2;n<=10; n=n+2)
    {
            somme=somme+n;
            prod = prod*n;
    }
    std::cout<<"somme= "<<somme<<" produit = "<<prod<<std::endl;
 */
    //exo5
    
    float x;
    do
    {
      std::cout<<"donnez un réel"<<std::endl;
      std::cin>> x;
        if (x>0)
	  std::cout<<"racine carree de "<<x<<" = "<<sqrt(x)<<std::endl;
        else
            if (x<0)
	      std::cout<<x<<" est négatif"<<std::endl;
    }
    while (x !=0);

    return 0;
}
