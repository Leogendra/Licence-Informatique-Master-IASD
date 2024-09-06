#include <iostream>
#include <cmath>

struct s_point {
  char nom;
  int x,y;};

struct lignePolygonale{
  int nb;
  s_point * ligne;
};

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

lignePolygonale saisieLP(){
  lignePolygonale lp;
  std::cout<<"donnez le nombre de points\n";
  std::cin>>lp.nb;
  lp.ligne = new s_point[lp.nb];
  for(int i=0;i<lp.nb;i++)
    lp.ligne[i]=saisie();
  return lp;
}



void affiche(s_point s){
  std::cout<<"point "<<s.nom<<" de coordonnées "<<s.x<<" "<<s.y<<"\n";}

void afficheLP(lignePolygonale lp){
  std::cout<<"ligne polygonale\n";
  for(int i = 0;i<lp.nb; i++)
    affiche(lp.ligne[i]);}


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

float distance( s_point a, s_point b){
  return  sqrt(pow( a.x-b.x,2)+pow(a.y-b.y,2));
}

float longueur(lignePolygonale lp){
  float r=0;
  for(int i = 0;i<lp.nb-1;i++)
    r+=distance(lp.ligne [i], lp.ligne[i+1]);
  return r;
}

char suivant1(char c){
  if (c+1<='Z')
    return c+1;
  else return c-'Z'+'A';
}

char suivant(char x)
{
  return 'A' + (x+1 -'A')%26  ;
}
lignePolygonale carre(int n, s_point s){
  lignePolygonale lp;
  lp.nb=5;
  lp.ligne = new s_point[5];
  lp.ligne[0] = s;
  lp.ligne[1]= {suivant(s.nom), s.x+n, s.y};
  lp.ligne[2] = {suivant(s.nom+1), s.x+n, s.y+n};
  lp.ligne[3] = {suivant(s.nom+2), s.x, s.y+n};
  lp.ligne[4] = s;
  return lp;
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
  s_point p = {'Z',2,4};
  // lignePolygonale l= saisieLP();
  lignePolygonale l= carre(2,p);
  afficheLP(l);
  std::cout<<"longueur "<<longueur(l)<<"\n";
  return 0;
}

