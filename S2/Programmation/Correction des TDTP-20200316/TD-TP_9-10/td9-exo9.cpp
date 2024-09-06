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
int * ajoutT(int * t, int taille, int x){
  int * r = new int [taille+1];
  int i=0;
  while(i<taille && t[i]<x){
    r[i]=t[i]; // recopie du début du tableau t dans r
    i++;
  }
  r[i]=x;
  // puis recopie de la fin du tableau
 
  for (int j=i+1;j<taille+1;j++)
    r[j]=t[j-1];
  return r;
}


int * copie(int * t, int taille){
  int * r = new int[taille];
  for(int i=0;i<taille;i++)
    r[i]=t[i];
  return r;
}


int * fusionT(int * t1,int taille1, int * t2, int taille2){
  if (taille1>taille2)
    return fusionT(t2, taille2, t1, taille1);
  else{
    int * r = copie(t2,taille2);
    for(int i = 0;i<taille1;i++){
      r=ajoutT(r, taille2+i,t1[i]); 
    }
    return r;
  }
}
   
  


int main(){
  /* std::cout<<"donnez la taille du tableau\n";
     int n;
     std:: cin>>n;
     int * t = new int[n];
     for(int i=0;i<n;i++){
     std::cout<<"donnez un entier\n";
     std::cin>>t[i];
     }

     int x;
     std::cout<<"donnez l'entier à ajouter\n";
     std::cin>>x;
     int * r = ajoutT(t,n,x);
     affichetab(t,n);
     affichetab(r,n+1);
  */
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

  int * r = fusionT(t1,n1, t2,n2);
  affichetab(t1,n1);
  affichetab(t2,n2);
  affichetab(r,n1+n2);

  delete [] t1;
  delete [] t2;
  delete [] r;
  return 0;
}
