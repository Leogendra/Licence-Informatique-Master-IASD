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

int * extractPairs(int * t, int taille, int * nbpairs){
  // on cherche le nombre de nbre pairs de t
  * nbpairs=0;int i;
  for(i=0;i<taille;i++)
    if (t[i]%2==0)
      (* nbpairs)++;
  int * r = new int [*nbpairs];
  int j=0;
  for(i=0;i<taille;i++)
    if ( t[i]%2==0){
      r[j]=t[i];
      j++;}
  return r;
}

void extract(int * t, int taille, int * nbp, int * nbi, int ** tp, int ** ti){
  // calcul du nbre de nbres pairs et du nbre de nbres impairs;
  int i,j=0,k=0;
  for(i=0;i<taille;i++)
    if (t[i]%2==0)
      j++;
    else k++;
  *nbp=j; 
  *nbi=k;

  // on place les nombres pairs dans t1 et les nombres impairs dans t2
  int * t1, *t2;
  t1=new int[j];
  t2= new int[k];
  j=0, k=0;
  for(i=0;i<taille;i++)
    if (t[i]%2==0){
      t1[j]=t[i]; j++;}
    else{
      t2[k]=t[i]; k++;}
  *tp=t1;
  *ti=t2;
}



int main(){
  std::cout<<"donnez la taille du tableau \n";
  int n;
  std:: cin>>n;
  int * t = new int[n];
  for(int i=0;i<n;i++){
    std::cout<<"donnez un entier\n";
    std::cin>>t[i];
  }
  int x,y;
  int * p = extractPairs(t,n,&x);
  affichetab(t,n);
  affichetab(p,x);
  int * pairs, * impairs;
  extract(t,n, &x,&y, &pairs, &impairs);
  affichetab(pairs,x);
  affichetab(impairs, y);

  return 0;
}
