#include <iostream>

int multiple2(int n)
{
  return (n%2==0);
}

int multiple3(int n)
{
  return (n%3==0);
}


int main()
{
  int x;
  std::cout<<"Donnez un entier"<<std::endl;
  std::cin>>x;


    
  if (multiple2(x) && multiple3(x)){
    std::cout<<x<<" est multiple de 6 \n";
  }
  else { 
    if (multiple2(x)){
      std::cout<<x<<" est multiple de 2 \n";
    }
    else {
      if (multiple3(x)){
	std::cout<<x<<" est multiple de 3 \n";
      }
      else {  
	std::cout<<x<<" n'est multiple ni de 2 ni de 3 \n";
      }
    }
      
    
      return 0;
  }
