#include <iostream>

bool Identique(int *p1, int *p2){
  return p1==p2;
}

bool ValeurIdentique(int *p1, int *p2){
  return *p1==*p2;
}

int main(){
  int x=3,y=4,z=3;
  std::cout<<std::boolalpha;
  std::cout<<"addr(x)==addr(y) ? "<<Identique(&x,&y)<<std::endl;
  std::cout<<"val(x) == val(y) ? "<<ValeurIdentique(&x,&y)<<std::endl;
  std::cout<<"addr(x)==addr(z) ? "<<Identique(&x,&z)<<std::endl;
  std::cout<<"val(x) == val(z) ? "<<ValeurIdentique(&x,&z)<<std::endl;
  std::cout<<"addr(x)==addr(x) ? "<<Identique(&x,&x)<<std::endl;
  std::cout<<"val(x) == val(x) ? "<<ValeurIdentique(&x,&x)<<std::endl;
  return 0;
}
