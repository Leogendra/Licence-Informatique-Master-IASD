#include <iostream>

struct poupeeRusse1{
  int reference;
  float prix;
  // poupeeRusse2 contenu;
};
struct poupeeRusse2{
  int reference;
  float prix;
  poupeeRusse2 * contenu;
};

void saisiePR1(poupeeRusse1 *p){
  std::cout<<"donnez le prix en euros de la poupee russe \n";
  std::cin>>p->prix;
  std::cout<<"donnez sa référence \n";
  std::cin>>p->reference;
}

void affichePR1(poupeeRusse1 p){
  std::cout<<" prix en euros de la poupee russe = ";
  std::cout<<p.prix<<"\n";
  std::cout<<"référence = ";
  std::cout<<p.reference<<"\n";
}

void saisiePR2(poupeeRusse2 *p){
  std::cout<<"donnez le prix en euros de la poupee russe \n";
  std::cin>>p->prix;
  std::cout<<"donnez sa référence \n";
  std::cin>>p->reference;
  p->contenu=NULL;
}

void ajouter(poupeeRusse2 *dedans, poupeeRusse2 *contenant){
  contenant->contenu = dedans;
}


void affichePR2(poupeeRusse2 *p){
  std::cout<<" prix en euros de la poupee russe = ";
  std::cout<<p->prix<<"\n";
  std::cout<<"référence = ";
  std::cout<<p->reference<<"\n";
  if (p->contenu !=NULL){
    std::cout<<"contenu : \n";
    affichePR2(p->contenu);
  }
}

int main(){
  poupeeRusse1 x;
  poupeeRusse2 y,z;
  std::cout<<"1e poupee \n";
  saisiePR2(&y);
  affichePR2(&y);
  std::cout<<"2e poupee \n";
  saisiePR2(&z);
  affichePR2(&z);
  ajouter(&z,&y);
  std::cout<<"poupees emboitées\n";
  affichePR2(&y);
  poupeeRusse1 tab [3]; // un tableau de 3 poupeeRusse1
  poupeeRusse1 * t; // un pointeur vers une poupeeRusse1
  poupeeRusse1 * t1 [2]; // un tableau de 2 pointeurs vers une poupeeRusse1
  poupeeRusse2 t3[2][10]; // une tableau de poupeeRusse2 de dimension 2, 10.
}
