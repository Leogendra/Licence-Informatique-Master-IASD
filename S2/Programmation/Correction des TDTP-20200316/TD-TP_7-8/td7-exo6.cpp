#include <iostream>
#include "affiche.h"
int occ(int T[10], int x){
  int n=0;
  for(int i=0;i<10;i++)
    {
      if (T[i]==x)
	{ n++; }
    }
  return n;
}
int main(){
  int T[10]={2,3,4,2,3,1,8,2,5,9};
  int x;
  std::cin>>x;
  std::cout<<"nbocc= "<<occ(T,x)<<std::endl;  
  return 0;
}
