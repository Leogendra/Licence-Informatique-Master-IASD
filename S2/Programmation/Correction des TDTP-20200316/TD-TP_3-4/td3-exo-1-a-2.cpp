#include <iostream>
#include <iomanip>

bool admis(int numero, double moyenne);
double moyennePonderee(double noteCC, int coeffCC, double noteExam, int coeffExam);
void tableMult(int n); // ou bien void tableMult(unsigned_int n);

void tablesMult();
bool premier(int n); // ou bien premier (unsigned_int n);
int nombreDiviseurs(int n);// ou bien nombreDiviseurs(unsigned_int n);
// Remarque, on peut omettre le nom des paramètres mais il faut préciser leur rôle


bool admis(int numero, double moyenne){
  return true;}

double moyennePonderee(double noteCC, int coeffCC, double noteExam, int coeffExam){
  return 0;}

void tableMult(int n){
  std::cout<<"mult "<<n<<std::endl;
}
void tablesMult(){
 std::cout<<"tables mult "<<std::endl;
}
bool premier(int n){return true;}

int nombreDiviseurs(int n){
  return 0;
}

int main() {
  admis(20156708, 12.6);
  bool b =   admis(20156708, 12.6);
  std::cout<<std:: boolalpha<< admis(20156708, 12.6)<<std::endl;

  moyennePonderee(12,3,13,5);
  double x=   moyennePonderee(12,3,13,5);
  std::cout<<"moyenne pondéree = "<<x<< std::endl;
  std::cout<<"moyenne pondéree = "<<moyennePonderee(12,3,13,5)<< std::endl;


  admis(20156708, moyennePonderee(15,2,8,5));
  bool c= admis(20156708, moyennePonderee(15,2,8,5));
  std::cout<<c<<std::endl; 
  std::cout<<admis(20156708, moyennePonderee(15,2,8,5))<<std::endl; 

  tableMult(5);

  tablesMult();

  premier(567);
  bool d = premier(567);
  std::cout<< premier(567)<<std::endl;

  int z= 8;
  nombreDiviseurs(z);
  int s =  nombreDiviseurs(z);
  std::cout<< "nombre de diviseurs de "<<z<<" = "<<s<<std::endl;

  return 0;
}
