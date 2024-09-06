#include <iostream>
#include <cmath>

//variables globales
int pieces[16];
int choixpiece;
int valid;
int cases[16];
int choixcase;
bool tour=true;
//true=j1; false=j2
int totaltour=0;
  //quand =16, fin jeu sur égalité



//regarde si la pièce est disponible
void checkpiece(int *choixpiece)
{while (pieces[*choixpiece-1]==0) 
{std::cout<<"Cette pièce est déjà utilisée ou non valide"<<std::endl;
  std::cin>>*choixpiece;};}


//regarde si la case n'est pas occupée
void checkcase(int *choixcase)
{while (cases[*choixcase-1]!=0) 
{std::cout<<"Cette case est déjà utilisée ou non valide"<<std::endl;
  std::cin>>*choixcase;};}


//converti un entier <16 en binaire
int conv(int a)
{a--;
int b=0;
        if (a/8==1) {b+=1000; a=a%8;};
        if (a/4==1) {b+=100; a=a%4;};
    if (a/2==1) {b+=10; a=a%2;};
        if (a/1==1) {b+=1;};
return b;}


//renvoie en binaire les particularités que les pièces ont en commun
int com(int a, int b)
{int c=0;
        if (a==0 || b==0) {return 0;};
a=conv(a);
b=conv(b);
        for (int i=4; i>0; i--) {
          if (a%2==b%2) {c+=pow(10,i);};
        a/=10;
        b/=10;};
return c;}


//fonction qui regarde si les pieces ont AU MOINS 1 particularité identique
bool uncom(int a, int b)
{if (a==0 || b==0) {return false;};
        for (int i=4; i>0; i--) {
        if (a%2==b%2) {return true;};
        a/=10;
        b/=10;};
return false;}


//check si une des 4 cases est vide
bool cvide(int cases[16], int a, int b, int c, int d)
{return (cases[a]!=0)&&(cases[b]!=0)&&(cases[c]!=0)&&(cases[d]!=0);}


//check de victoire sur les 10 alignements possibles
bool checkwin(int cases[16],int pieces[16])
{    //lignes
  if (cvide(cases,0,1,2,3) && uncom(com(cases[0],cases[1]),com(cases[2],cases[3]))) {return true;};
if (cvide(cases,4,5,6,7) && uncom(com(cases[4],cases[5]),com(cases[6],cases[7]))) {return true;};
if (cvide(cases,8,9,10,11) && uncom(com(cases[8],cases[9]),com(cases[10],cases[11]))) {return true;};
if (cvide(cases,12,13,14,15) && uncom(com(cases[12],cases[13]),com(cases[14],cases[15]))) {return true;};
        //colonnes
if (cvide(cases,0,4,8,12) && uncom(com(cases[0],cases[4]),com(cases[8],cases[12]))) {return true;};
if (cvide(cases,1,5,9,13) && uncom(com(cases[1],cases[5]),com(cases[9],cases[13]))) {return true;};
if (cvide(cases,2,6,10,14) && uncom(com(cases[2],cases[6]),com(cases[10],cases[14]))) {return true;};
if (cvide(cases,3,7,11,15) && uncom(com(cases[3],cases[7]),com(cases[11],cases[15]))) {return true;};
        //diagonnales
if (cvide(cases,0,5,10,15) && uncom(com(cases[0],cases[5]),com(cases[10],cases[15]))) {return true;};
if (cvide(cases,3,6,9,12) && uncom(com(cases[3],cases[6]),com(cases[9],cases[12]))) {return true;};
return false;}



//permet de dire à l'utilisateur la forme de la pièce
void forme(int p)
{if (p>8) {std::cout<<"noire, ";} else {std::cout<<"blanche, ";};
  if ((p<5)||((p>8)&&(p<13))) {std::cout<<"petite, ";} else {std::cout<<"grande, ";};
  if ((p==3)||(p==4)||(p==7)||(p==8)||(p==11)||(p==12)||(p==15)||(p==16)) {std::cout<<"carrée, ";} else {std::cout<<"ronde, ";};
  if (p%2==0)  {std::cout<<"et trouée."<<std::endl;} else {std::cout<<"et pleine."<<std::endl;};
}



int main()
//initialise les tableaux (cases vides et pièces disponibles)
{for (int i=0; i<16; i++) {pieces[i]=1;};
  for (int i=0; i<16; i++) {cases[i]=0;};


//déroulement de la partie tant que personne n'a gagné ou que le plateau n'est pas plein
  while (totaltour<16 && !checkwin(cases, pieces)) {
    std::cout<<"debut du tour "<<totaltour+1<<std::endl;
  //partie 1, choix de la pièce
  if (tour) {std::cout<<"Joueur 1, choissisez la pièce pour le joueur 2 : ";}
  else {std::cout<<"Joueur 2, choissisez la pièce pour le joueur 1 : ";};

  //validation de la pièce choisie
  std::cin>>choixpiece;
  valid=choixpiece;
  do {choixpiece=valid;
  checkpiece(&choixpiece);
  std::cout<<"Vous avez choisis la pièce ";
  forme(choixpiece);
  std::cout<<"Pour valider, entrez de nouveau le numéro choisis : ";
  std::cin>>valid;}
  while (valid!=choixpiece);
  pieces[choixpiece-1]=0;

  std::cout<<std::endl;
  tour=!tour;
  //partie 2, placement de la pièce
    if (tour) {std::cout<<"Joueur 1, choissisez la case où poser la pièce : ";}
    else {std::cout<<"Joueur 2, choissisez la case où poser la pièce : ";};
  std::cin>>choixcase;
  checkcase(&choixcase);
  cases[choixcase-1]=choixpiece;
  
  int yoyoyo = conv(cases[0]);
    std::cout<<yoyoyo<<std::endl;
  
  totaltour++;
  std::cout<<std::endl<<"fin tour"<<std::endl;
  for (int i=0; i<16; i++) {
    std::cout<<cases[i]<<"   "<<pieces[i]<<std::endl;};
  };
  
  
    //fin de la partie
    if (totaltour>15) {std::cout<<"Egalité ! Le plateau est plein."<<std::endl;}
    else if (tour) {std::cout<<"Le Joueur 1 remporte la partie";}
    else {std::cout<<"Le Joueur 2 remporte la partie";};
    std::cout<<std::endl<<"L'autre est une tarlouze"<<std::endl;
  }



