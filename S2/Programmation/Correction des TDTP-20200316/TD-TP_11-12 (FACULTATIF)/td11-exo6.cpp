#include <iostream>

struct s_point {
  char nom;
  int x,y;};

s_point saisie(){
  s_point p;
  do{
    std::cout<<"donnez le nom du point entre A et Z\n";
    std::cin>>p.nom;}
  while (p.nom <'A'|| p.nom >'Z');

  std::cout<<"donnez les coordonnées du point\n";
  std::cin>>p.x>>p.y;

  return p;
}

void affiche(s_point s){
  std::cout<<"point "<<s.nom<<" de coordonnées "<<s.x<<" "<<s.y<<"\n";}

void maz(s_point * s){
  s->nom='O';
  s->x=0;
  s->y=0;
}

s_point symetriqueO(s_point s){
  s_point r;
  r.x = - s.x;
  r.y = - s.y;
  r.nom = 'Z'+'A'-s.nom;
  return r;
}

  
int main(){
  s_point p1, p2,p3;
  p1 = saisie();
  p2= saisie();
  affiche(p1);
  affiche(p2);
  maz(&p1);
  affiche(p1);
  p3=symetriqueO(p2);
  affiche(p3);


  return 0;


}

