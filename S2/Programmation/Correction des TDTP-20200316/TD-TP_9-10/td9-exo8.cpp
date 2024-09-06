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
int * reverse(int * t,int taille){
  int * e = new int[taille];
  for(int i = 0;i<taille;i++)
    e[i]= t[taille-i-1];
  return e;
}


int main(){
  std::cout<<"donnez la taille du tableau\n";
  int n;
  std:: cin>>n;
  int * t = new int[n];
  for(int i=0;i<n;i++){
    std::cout<<"donnez un entier\n";
    std::cin>>t[i];
  }
  int * r = reverse(t,n);
  affichetab(t,n);
  affichetab(r,n);

  delete [] t;
  delete [] r;
  return 0;

}
