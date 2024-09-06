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

int * duplicata(int * t, int taille){
  int * r = new int[2*taille];
  for (int i=0;i<taille;i++){
    r[i]=t[i];
    r[2*taille-i-1]=t[i];
  }
  return r;
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

  int * r1 = duplicata(t,n);
  affichetab(t,n);
  affichetab(r1,2*n);
  int * r2 = duplicata(r1,2*n);
  affichetab(r2,4*n);
  int * r3 = duplicata(r2,4*n);
  affichetab(r3,8*n);

  delete [] t;
  delete [] r1;
  delete [] r2;
  delete [] r3;
  return 0;
}


