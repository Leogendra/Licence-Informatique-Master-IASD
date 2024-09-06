#include <iostream>
#include <iomanip>

int main()

{
  int n;
  std::cout<<"donnez un entier"<<std::endl;
  std::cin>>n;
  int i;
  double x = 0.0;
  for (i=1;i<=n;i++)
      x=x+(1.0/i);
  double y = 0.0;
  for (i=n;i>=1;i--)
       y=y+(1.0/i);
  std::cout<<std::setprecision(15)<<std::scientific;
  std::cout<<"resultats : "<<x<<" "<<y<<std::endl;;
  return 0;
}
