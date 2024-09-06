#include <iostream>

void addComplex(double *Cr, double *Ci, double Ar, double Ai,double Br, double Bi){
  *Cr =Ar+Br;
  *Ci =Ai+Bi;
}

void mulComplex(double *Cr, double *Ci, double Ar, double Ai,double Br, double Bi){
  *Cr =Ar*Br-Ai*Bi;
  *Ci =Ai*Br+Ar*Bi;
}

int main(){
  double Ai,Ar,Bi,Br,Ci,Cr;
  std::cout<<"Entrez deux complexes: "<<std::endl;
  std::cin>>Ar>>Ai>>Br>>Bi;

  addComplex(&Cr,&Ci,Ar,Ai,Br,Bi);
  std::cout<<"l'addition complexe est: "<< Cr<<"+ I* "<<Ci<<std::endl;

  mulComplex(&Cr,&Ci,Ar,Ai,Br,Bi);
  std::cout<<"la multiplication  complexe est: "<< Cr<<"+ I* "<<Ci<<std::endl;

  return 0;
}

