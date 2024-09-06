#include <iostream>

struct point
{
  float x,y;
};  
struct segment{
  point a;
  point b;
};
struct cercle{
  point centre;
  float rayon;
};

void affiche (cercle c){
  std::cout<<"centre : "<<c.centre.x<<","<<c.centre.y;
  std::cout<<"rayon : "<<c.rayon <<std::endl;
}
int main(){
  cercle a={{1.2,2.4},6.5};
  cercle b={{6.6, 8.2},5};
  cercle c;
  cercle d;
    c=b;
  c.centre.x=1.2;
  c.centre.y=2.4;
  d={{1.2,2.4,3.4}};
  affiche(a);
  affiche(b);
  affiche(c);
  return 0;
}
