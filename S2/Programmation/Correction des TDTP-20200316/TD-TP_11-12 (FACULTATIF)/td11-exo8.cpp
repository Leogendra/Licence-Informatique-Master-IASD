#include <iostream>
struct poly {
  int       deg;
  double* coeff;
};
poly creerPoly(){
  std::cout<<"Entrez le degrés du polynome: ";
  poly p;
  std::cin>>p.deg;
  p.coeff=new double[p.deg+1];
  return p;
}
void saisieCoeff(poly* p){ 
  for(int i=0;i<= p->deg;i++)
    { std::cin>>p->coeff[i]; }
}
void affiche(poly p){
  for(int i=0;i<= p.deg;i++)
    { std::cout<<p.coeff[i]<<"x^"<<i<<","; }
  std::cout<<std::endl;
}


poly derivee(poly p){
  poly res;
  res.deg= p.deg-1;
  res.coeff = new double[res.deg+1];
  for (int i = 0;i<=res.deg;i++)
    res.coeff[i]= (i+1) * p.coeff[i+1];
  return res;}
poly primitive (poly p){
  poly res;
  res.deg= p.deg+1;
  res.coeff = new double[res.deg+1];
  res.coeff[0]=0;
  for (int i = 1;i<=res.deg;i++)
    res.coeff[i]= p.coeff[i-1]/i;
  return res;}

double evalue(poly p, double x){
  double res= 0.0;
  for(int i = p.deg; i>=0; i--)
    res = res*x + p.coeff[i];
  return res;
}
int main(){
  poly p1= creerPoly();
  init(&p1);
  affiche(p1);
  poly p2 = derivee(p1);
  affiche(p2);
  poly p3 = primitive(p1);
  affiche(p3);
  float x;
  std::cout<<"donnez un réel\n";
  std::cin>>x;
  std::cout<<"evaluation en "<<x<< " = "<<evalue(p1,x)<<"\n";
  return 0;
}
