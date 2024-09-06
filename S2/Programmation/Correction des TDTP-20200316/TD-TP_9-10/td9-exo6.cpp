#include <iostream>

int main(){
  std::cout<<"donnez la taille du tableau\n";
  int n;
  std:: cin>>n;
  float * t = new float[n];
  for(int i=0;i<n;i++)
    t[i]=1.0/((1+i)*(1+i));
  float s=0;
  for(int i=0;i<n;i++)
    s+=t[i];
  std::cout<<"somme " <<s<<std::endl;
  delete [] t;
  return 0;
}
