#include <iostream>

int saisieEntier(){
  std::cout<<"Donnez un entier\n";
  int x;
  std::cin>>x;
  return x;
}

int ackermann(int m, int n)
{
    if (m==0)
        return (n+1);
    else if ((n==0) && (m>0))
        return ackermann(m-1,1);
    else return ackermann(m-1, ackermann(m,n-1));

}
int main()
{
    int i =  saisieEntier();
    int j =  saisieEntier();

    for (size_t m=0;m<i;m++)
      for (size_t n=0;n<j;n++)
	std::cout<<"ackermann("<<m<<","<<n<<") = "<<ackermann(m,n)<<"\n";
    return 0;
}
