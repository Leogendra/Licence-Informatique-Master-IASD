
#include <iostream>

double saisieNote(){
  double x;
  do{
    std::cout<<"donnez une note comprise entre 0 et 20\n";
    std::cin>>x;
  }
  while (x<0 || x>20);
  return x;
}


double moyenne(int nbNotes){
  if (nbNotes ==0)
    return 0;
  else
    {
      int i;double s= 0;
      for (i=0;i<nbNotes;i++)
	s+=saisieNote();
      return s/nbNotes;}
}


int main(){
  int nb;
  std::cout<<"DONNEZ LE NOMBRE DE NOTES À SAISIR\n";
  std::cin>>nb;
  double a=moyenne(nb);
  std::cout<<"moyenne = "<<a<<std::endl;
  return 0;
} 
  
