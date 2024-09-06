#include <iostream>


int saisieAnnee(){
  int a;
  std::cout<<"Donnez l'année de votre choix\n";
  std::cin>>a;
  return a;
}
int saisieMois(){
  int m;
  do{
    std::cout<<"Donnez le mois de votre choix\n";
    std::cin>>m;}
  while(m<1||m>12);
  return m;
}
int saisieJour(int mois, int annee){
  int j;bool b;
  do{
    b=false;
    std::cout<<"Donnez le jour de votre choix\n";
    std::cin>>j;
    switch(mois){
    case 1: b=(j>=1&&j<=31);break;
    case 2: if (annee%4==0) b=(j>=1&&j<=29); else b=(j>=1&&j<=28);break;
    case 3: b=(j>=1&&j<=31);break;
    case 4: b=(j>=1&&j<=30);break;
    case 5: b=(j>=1&&j<=31);break;
    case 6: b=(j>=1&&j<=30);break;
    case 7: b=(j>=1&&j<=31);break;
    case 8: b=(j>=1&&j<=31);break;
    case 9: b=(j>=1&&j<=30);break;
    case 10:b=(j>=1&&j<=31);break;
    case 11: b=(j>=1&&j<=30);break;
    case 12: b=(j>=1&&j<=31);break;}
  }
  while(!b);
  return j;
}


void afficheJour(int j){
  switch(j){
  case 1: std::cout<<"lundi\n"; break;
  case 2: std::cout<<"mardi\n"; break;
  case 3: std::cout<<"mercredi\n"; break;
  case 4: std::cout<<"jeudi\n"; break;
  case 5: std::cout<<"vendredi\n"; break;
  case 6: std::cout<<"samedi\n"; break;
  case 0: std::cout<<"dimanche\n"; break;
  }
}

int compteJours(int m){

  // on compte les jours écoulés depuis le début de l'année a jusqu'à la fin du mois m 
  //sans tenir compte du fait que a est bissextile ou non
  int nb =0;
  switch(m){
  case 1: nb = 31;break;
  case 2: nb = 31+28;break;
  case 3 :nb = 31+28+31;break;
  case 4 : nb = 31+28+31+30;break;
  case 5 : nb = 31+28+31+30+31;break;
  case 6 : nb = 31+28+31+30+31+30;break;
  case 7 : nb = 31+28+31+30+31+30+31;break;
  case 8 : nb = 31+28+31+30+31+30+31+31;break;
  case 9 : nb = 31+28+31+30+31+30+31+31+30;break;
  case 10 : nb = 31+28+31+30+31+30+31+31+30+31;break;
  case 11 : nb = 31+28+31+30+31+30+31+31+30+31+30;break;
  case 12 : nb = 31+28+31+30+31+30+31+31+30+31+30+31;break;
  }

  return nb;
}

int compteNombreAnneesBissextiles(int a){
  return (a/4+a/400-a/100);
}

int codeJourAnnee(int j, int m, int a){
  int x;
  if (m<3)
    x= j+(a-1)+compteNombreAnneesBissextiles(a-1)+compteJours(m-1);
  else 
    x= j+(a-1)+compteNombreAnneesBissextiles(a)+compteJours(m-1);
  x=x%7;


  return x;
}




bool bissextile(int annee){
  return (annee%4==0||annee%400==0)&&(annee%100 !=0);
}


int main(){
  int a = saisieAnnee();
  int m = saisieMois();
  int j = saisieJour(m,a);
  std::cout<<j<<"/"<<m<<"/"<<a<<"\n";
  std::cout<<"jour de la semaine = ";
  afficheJour(codeJourAnnee(j,m,a));
  return 0;
}
