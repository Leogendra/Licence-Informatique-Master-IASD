#include <iostream>
#include <iomanip>

bool premier(int n){
  if (n==0|| n==1)
    return false;
  else if (n==2)
    return true;
else
  {int i;
   for (i=2;i*i<=n;i++)
     if(n%i==0)
       return false;
       return true;}
}

int prochainpremier(int n)
{

    int s = n;
    while (! premier(s))
    {
      s++;
    }
    return s;
}


int saisieEntier(){
  std::cout<<"Donnez un entier\n";
  int x;
  std::cin>>x;
  return x;
}

int main()
{
    int x = saisieEntier();
    int y= prochainpremier(x);
    std::cout<<" le plus petit nombre premier superieur ou égal à "<<x<<" = "<<y<<std::endl;
    return 0;
}
