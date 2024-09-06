#include <iostream>

int somme(int * t, int taille){
  int s = 0;
  for(int i=0;i<taille;i++)
    s+= t[i];
  return s;
}
int main(){
  std::cout<<"donnez la taille du tableau\n";
  int n;
  std:: cin>>n;
  int * t = new int[n];
  for(int i=0;i<n;i++)
    t[i]=i;
  for(int i=0;i<n;i++)
    std::cout<< t[i]<<" ";
  std::cout<<std::endl;
  std::cout<<"somme = "<<somme(t,n)<<"\n";
  delete [] t;
  return 0;
}
