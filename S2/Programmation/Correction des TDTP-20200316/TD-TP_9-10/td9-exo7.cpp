#include <iostream>
#include <iomanip>

void affichetab(int * t, int taille){
  if (taille >=1){
    std::cout<<"[";
    for(int i=0;i<taille-1;i++){
      std::cout<<std::setw(2)<<t[i]<<",";
    }
    std::cout<<std::setw(2)<<t[taille-1]<<"]\n";
  }
}
int main(){
  std::cout<<"donnez la taille du tableau\n";
  int n;
  std:: cin>>n;
  int * t = new int[n];
  for(int i=0;i<n;i++)
    t[i]=i*i;;

  affichetab(t,n);
  delete [] t;
  return 0;
}
