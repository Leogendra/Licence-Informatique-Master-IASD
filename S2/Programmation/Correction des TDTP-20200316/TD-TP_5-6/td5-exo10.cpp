#include <iostream>

void saisieDate(int* j, int* m, int* a){
  char c1,c2;
  do {
    std::cout<<"Entrez une date au format jj/mm/aaaa"<<std::endl;
    std::cin>>*j>>c1>>*m>>c2>>*a;  
  } while(c1!='/'||c2!='/'|| *m>12 ||* m<1 || *j<1  || *j>31);  
}

int main(){
  int j,m,a;
  saisieDate(&j,&m,&a);
  std::cout<<"vous avez saisi: "<<j<<"/"<<m<<"/"<<a<<std::endl;
  
  return 0;
}
