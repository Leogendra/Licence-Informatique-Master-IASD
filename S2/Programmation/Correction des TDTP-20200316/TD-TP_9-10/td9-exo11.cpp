#include <iostream>
#include <iomanip>

void affichetab(int * t, int taille){
  if (taille==0)
    std::cout<<"[]\n";
  else 
    if (taille >=1){
      std::cout<<"[";
      for(int i=0;i<taille-1;i++){
	std::cout<<std::setw(2)<<t[i]<<",";
      }
      std::cout<<std::setw(2)<<t[taille-1]<<"]\n";
    }
}


int * concat(int * t1, int taille1, int * t2, int taille2){
  int * r= new int [taille1+taille2];int i;
  for(i=0;i<taille1;i++)
    r[i]=t1[i];
  for(i=taille1; i<taille1+taille2; i++)
    r[i]= t2[i-taille1];
  return r;
}


int main(){
  std::cout<<"donnez la taille du tableau1\n";
  int n1;
  std:: cin>>n1;
  int * t1 = new int[n1];
  for(int i=0;i<n1;i++){
    std::cout<<"donnez un entier\n";
    std::cin>>t1[i];
  }
  std::cout<<"donnez la taille du tableau 2\n";
  int n2;
  std:: cin>>n2;
  int * t2 = new int[n2];
  for(int i=0;i<n2;i++){
    std::cout<<"donnez un entier\n";
    std::cin>>t2[i];
  }

  int * r = concat(t1,n1, t2,n2);
  affichetab(t1,n1);
  affichetab(t2,n2);
  affichetab(r,n1+n2);

  delete [] t1;
  delete [] t2;
  delete [] r;
  return 0;
}

