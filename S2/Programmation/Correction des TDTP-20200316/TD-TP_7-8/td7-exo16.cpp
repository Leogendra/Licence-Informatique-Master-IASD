#include <iostream>
#include <cstdlib>

void afficheMot(char* C, int n){
  for (int i=0;i<n;i++)
    std::cout<<C[i];
  std::cout<<std::endl;
}


bool areEqual(char *T1, int n1, char *T2, int n2){
  if (n1!=n2) return false;
  int i=0;
  while(i<n1 && T1[i]==T2[i]) {i++;}
  return i==n1;
}


bool pendu(char *hidden, int n){
  int t=0;
  char l;
  char *user=new char[n];
  for (int i=0;i<n;i++) user[i]='-';
  while (t<5 && !areEqual(user,n,hidden,n)){
    system("clear");
    std::cout<<"mot en cours: ";
    afficheMot(user,n);
    //afficheMot(hidden,n);
    std::cout<<"************"<<std::endl;
    std::cout<<"Entrer un lettre: ";
    std::cin>>l;
    bool match=false;
    for (int i=0;i<n;i++){
      if (hidden[i]==l){
	user[i]=l;
	match=true;
      }
    }
    if (!match) t++;

  }
  return t<5;
}

int main(){
  char *T; int n;
  std::cout<<"Entrer la taille du mot à deviner : ";  
  std::cin>>n;
  T=new char[n];
  std::cout<<"Entrer le mot à deviner : ";
  std::cin>>T;

  if (pendu(T,n))
    std::cout<<"bravo, vous avez gagné !!!";
  else
    std::cout<<"dommage, vous avez perdu !!!";

  std::cout<<std::endl<<" le mot été : ";
  afficheMot(T,n);
  std::cout<<std::endl;
  return 0;
}
