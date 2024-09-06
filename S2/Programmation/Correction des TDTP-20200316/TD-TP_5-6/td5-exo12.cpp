#include <iostream>

void onePGCDStep(int *a, int *b){
  int t;
  t=*b;
  *b=*a % t;
  *a=t;
}


int PGCD(int a, int b){
  int u0,u1;
  u0=(a>b?a:b);
  u1=(a>b?b:a);
  while(u1!=0){
    onePGCDStep(&u0,&u1);
  }
  return u0;
}


int main(){
  int x,y;
  std::cout<<"Entrez deux entiers: "<<std::endl;
  std::cin>>x>>y;

  std::cout<<"leur pgcd est: "<<PGCD(x,y)<<std::endl;
  return 0;
}
