#include <iostream>
#include <cmath>
int solve(double a, double b, double c, double* x1, double *x2){

  double delta=b*b-4.*a*c;

  if (delta<0){
    return 0;
  }
  else {
    *x1=(-b-std::sqrt(delta))/(2.*a);
    *x2=(-b+std::sqrt(delta))/(2.*a);
    return 2-(delta==0);
  }  
}



int main(){
  double a,b,c,x1,x2;

  std::cout<<"Entrez les coefficients de votre équation ax^2+bx+c=0: "<<std::endl;
  std::cin>>a>>b>>c;
  int nbsol=solve(a,b,c,&x1,&x2);

  switch(nbsol){
  case 0:
    std::cout<<"aucune solution"<<std::endl; break;
  case 1:
    std::cout<<"une solution"<<std::endl;
    std::cout<<"x1="<<x1<<std::endl;
    break;
  case 2:
    std::cout<<"deux solutions"<<std::endl;
    std::cout<<"x1="<<x1<<std::endl;
    std::cout<<"x2="<<x2<<std::endl;
    break;

  default:
    std::cout<<"ERREUR"<<std::endl; break;
  };

    
  return 0;
}
