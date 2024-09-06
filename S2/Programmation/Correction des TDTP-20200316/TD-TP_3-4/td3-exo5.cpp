#include <iostream>


double saisieNote(){
  double note;
  do {
    std::cout<<"Donnez une note entre 0 et 20: ";
    std::cin>>note;  
  } while ( note<0. || note>20.);   
  return note;
}

int main(){
  double a=saisieNote();
  double b=saisieNote();
  std::cout<<" première saisie = "<<a
	   <<" deuxième saisie = "<<b<<std::endl;
  return 0;
} 


