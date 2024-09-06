#include <iostream>

int saisieEntier()
{
  int n;
  std::cout<<"donnez un entier\n";
  std::cin>>n;
  return n;
}

int testerUn(int a, int b, int c)
{
  return (a*a*a+b*b*b+c*c*c== a*100+b*10+c);
}

void  testerTout()
{
  int i ;
  for (i=2; i<1000; i++)
    {
      int a,b,c ;
      c = (i%100)%10;
      b = (i%100)/10;
      a = (i/100);
      if (testerUn(a,b,c))
	std::cout<<i<<" vérifie la propriete\n";
    }
}

void testerToutBis()
{
  int a,b,c,z;
  for (a=0;a<10;a++)
    for (b=0;b<10;b++)
      for (c=0;c<10;c++)
	{
	  z=a*100+b*10+c;
	  if (z>=2 && testerUn(a,b,c))
	     std::cout<<z<<" vérifie la propriete avec "<<a<<" "<<b<<" "<<c<<"\n";
	}
}
int main()
{
  std::cout<<"tests n°1\n";
  testerTout();
  std::cout<<"tests n°2\n";
  testerToutBis();
  return 0;
}
