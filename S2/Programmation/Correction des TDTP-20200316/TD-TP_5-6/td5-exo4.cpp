#include <iostream>

void saisieNote(double *x){
  do {
    std::cout<<"Entrez une note entre 0 et 20: ";
    std::cin>>*x;
  } while(*x<0 || *x>20);
}

#define NB_NOTE 4
int main(){
  double note,notemax=0.;
  for (int i=0;i<NB_NOTE;i++){
    saisieNote(&note);
    if (note>notemax)
      notemax=note;
  }
  std::cout<<"la note max est: "<<notemax<<std::endl;

  return 0;
}
