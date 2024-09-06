#include <iostream>


struct NbComplexe {
  float Re,Im;
};

NbComplexe saisie(){
  nbComplexe tmp;
  std::cout<<"Saisir un complexe: (Réelle Imaginaire): ";
  std::cin>>tmp.Re>>tmp.Im;
  return tmp;
}
void affiche(NbComplexe x){
  std::cout<<x.Re<<"+I*"<<x.Im<<std::endl;
}
NbComplexe somme(NbComplexe a, NbComplexe b){
  NbComplexe c={a.Re+b.Re,a.Im+b.Im};
  return c;
}
int main(){
  NbComplexe c1,c2;
  c1=saisie();
  c2=saisie();
  c1=somme(c1,c2);
  affiche(c1);
  return 0;  
}




























struct complexe{ float re,im;};

complexe saisie(){
  complexe c;
  std::cout<<"donnez la partie reelle"<<std::endl;
  std::cin>>c.re;
  std::cout<<"donnez la partie imaginaire"<<std::endl;
  std::cin>>c.im;
  return c;
}

void affiche(complexe c){
  std::cout<<"partie reelle=";
  std::cout<<c.re<<std::endl;
  std::cout<<"partie imaginaire=";
  std::cout<<c.im<<std::endl;;
}

complexe somme(complexe c1, complexe c2){
  complexe c;
  c.re = c1.re+c2.re;
  c.im=c1.im+c2.im;
  return c;
}


int main(){
  complexe c1,c2;
  c1 = saisie();
  affiche(c1);
  c2 = saisie();
  affiche(c2);
  c1= somme(c1,c2);
  std::cout<<"apres addition :" <<std<<endl;;
  affiche(c1);
  affiche(c2);
  return 0;
}
