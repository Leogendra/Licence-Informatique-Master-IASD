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


int max(int a, int b){
  if (a>b)
    return a;
  else return b;
}

int maximum(int * t, int taille){
  if (taille ==1)
    return t[0];
  else 
    if (taille==2)
      return max(t[0], t[1]);
    else {
      int * t1= new int[taille/2];
      for(int i=0;i<taille/2;i++)
	t1[i]=t[i];
      int * t2= new int [taille/2];

      if (taille%2==0){  
	for(int i=0;i<taille/2;i++)
	  t2[i]= t[i+taille/2];
	return max(maximum(t1, taille/2), maximum(t2, taille/2));
      }
      else {
	for(int i=0;i<taille/2;i++)
	  t2[i]= t[i+1+taille/2];
	return max(t[taille/2], 
		   max(maximum(t1, taille/2), maximum(t2, taille/2)));
      }
    }
}

int maximum2(int * t, int taille, int indice){
  if (taille ==1)
    return t[indice];
  else 
    if (taille==2)
      return max(t[indice], t[indice+1]);
    else {
      if (taille%2==0){  
	return max(maximum2(t,taille/2,indice), 
                   maximum2(t,taille/2, indice+taille/2));
      }
      else {
	return max(t[taille/2], 
	           max(maximum2(t, taille/2, indice), 
                       maximum2(t, taille/2, indice+taille/2)));
      }
    }
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

 
  affichetab(t,n);
  std::cout<<"maximum : "<<maximum(t, n)<<"\n";
  std::cout<<"maximum2 : "<<maximum2(t,n,0)<<"\n";
  delete [] t;
 
  return 0;
}
