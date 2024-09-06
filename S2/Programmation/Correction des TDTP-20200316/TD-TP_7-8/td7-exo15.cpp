#include <iostream>
#include "affiche.h"

bool prefixe(char *C1, int n1, char *C2, int n2){
  if (n1==0) return true;
  if (n1>n2) return false;

  return C1[0]==C2[0] && prefixe(C1+1,n1-1,C2+1,n2-1);
}

bool palindrome(char *C, int n){
  if (n==0 || n==1) return true;
  return C[0]==C[n-1] && palindrome(C+1,n-2);
}




int main(){
  char mot1[]="plat";
  char mot2[]="plateau";
  char mot3[]="placard";
  char mot4[]="pla";
  char mot5[]="rotor";

  if (prefixe(mot1,4,mot2,7))
    std::cout<<mot1<<" est prefixe de "<<mot2<<std::endl;
  if (prefixe(mot1,4,mot3,7))
    std::cout<<mot1<<" est prefixe de "<<mot2<<std::endl;
  if (prefixe(mot1,4,mot4,3))
    std::cout<<mot1<<" est prefixe de "<<mot2<<std::endl;

  if (palindrome(mot1,4))
    std::cout<<mot1<<" est un palindrome"<<std::endl;

  if (palindrome(mot5,5))
    std::cout<<mot5<<" est un palindrome"<<std::endl;

  
  return 0;
}
