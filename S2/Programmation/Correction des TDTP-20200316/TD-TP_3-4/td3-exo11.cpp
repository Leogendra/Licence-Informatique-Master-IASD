#include <iostream>
#include <iomanip>


double leibnitz(double epsilon)
{
  double pisur4=0;
  int i=1;
  int signe = 1;
  while (1.0/i >= epsilon)
    {
      pisur4= pisur4 + signe*(1.0/i);
      i = i+ 2;
      signe = - signe;
    }
  return pisur4;

}

double saisieReel()
{
  double f;
  std::cout<<"Entrez  un reel:\n";
  std::cin>>f;
  return  f;
}

int main()
{
  double PI = 3.141592653589793;
  double precision = saisieReel();
  double res = leibnitz(precision);
  std::cout<<"valeur de pi avec une precision "<< precision<<" = "<<std::setprecision(15)<<4*res<<"\n";
  std::cout<<"à comparer avec la valeur de PI = "<<PI<<"\n";
  return 0;
}
