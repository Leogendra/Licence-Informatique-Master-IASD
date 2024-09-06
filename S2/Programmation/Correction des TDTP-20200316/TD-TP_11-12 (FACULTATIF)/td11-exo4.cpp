#include <iostream>
struct temps{
  int  h,m,s;
};
temps conversion(int n){
  temps t;
  t.h= n/3600;
  t.m= (n%3600)/60;
  t.s = n%60;
  return t;
}
void  saisie(temps *t){
  char c1,c2;
  do{
    std::cout<<"donnez la duree sous la forme hh:mm:ss"<<std::endl;
    std::cin>> t->h >> c1 >> t->m >> c2 >> t->s;
  }
  while(c1!=':'||c2!=':'||t->m<0||t->m>=60||t->s<0||t->s>=60);
}
void  addtime(temps t1,temps t2, temps *res){
  int aux = t1.s+t2.s;
  res->s = aux%60;
  aux = aux/60+t1.m+t2.m;
  res->m = aux%60;
  res->h = aux/60+t1.h+t2.h;
}
int  comparetime(temps t1, temps t2){
  int s1,s2;
  s1=3600*t1.h+60*t1.m+t1.s;
  s2=3600*t2.h+60*t2.m+t2.s;
  if (s1<s2) return -1;
  if (s1>s2) return 1;
  return 0;
}
void affiche(temps *t){
  std::cout<<(t->h)<<':'<<(t->m)<<':'<<(t->s)<<std::endl;;
}

int main(){
  temps t1 = {5,37,54}, t2 = {6,29,13};
  temps t3 = conversion(7540);temps t4;
  affiche(&t1);
  affiche(&t2);
  affiche(&t3);
  addtime(t1,t2,&t4);
  affiche(&t4);
  std::cout<<"comparaison : "<<comparetime(t1,t2)<<std::endl;
  return 0;
}
