#include <iostream>

void saisie(int * t, int taille){
  for(int j=0;j<taille;j++){
    std::cout<<"donnez un entier\n";
    std::cin>> t[j];
  }
}

void affiche(int *t, int taille){
  for(int i=0;i<taille;i++)
    std::cout<<t[i]<<" ";
  std::cout<<std::endl;
}

int main(){
  int * t1 = new int[5];
  for(int j=0;j<5;j++){
    std::cout<<"donnez un entier\n";
    std::cin>>t1[j];
  }
  for(int i=0;i<5;i++)
    std::cout<< t1[i]<<" ";
  std::cout<<std::endl;
  delete [] t1;
  std::cout<<"donnez la taille du tableau\n";
  int n;
  std::cin>>n;
  t1 = new int[n];
  saisie(t1,n);
  affiche(t1,n);
  delete [] t1;
  return 0;
}
