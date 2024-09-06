#include <iostream>
#include <sys/types.h>
#include <pthread.h>
#include <unistd.h>
#include "calcul.h"

// structure qui regroupe les variables partagées entre les threads.
struct varPartagees {
  int nbZones;
  int * di; // le tableau indiqué dans l'énoncé

  ... //

};

// structure qui regroupe les paramètres d'un thread
struct params {

  int idThread; // cet entier correspond au numéro de traitement associé à un thread
  
  struct varPartagees * vPartage;


};

// fonction associée à chaque thread secondaire à créer.

void * traitement (void * p) {

  struct params * args = (struct params *) p;
  struct  varPartagees *  vPartage = args -> vPartage;

  ...

  for(int i = 1; i <= vPartage -> nbZones; i++){

    ...
    
    if( args -> idThread != 1){ // le premier traitement n'attent personne
      
      ...
      while(...){
   	// attente fin traitement sur la zone i 
      }
    }

    ...

      // dans cette partie, le traitement de la zone i est à faire en faisant une simulation d'un long calcul (appel a calcul(...)
    ...
    
      // a la fin du traitement d'une zone, le signaler pour qu'un thread en attente se réveille. 
      
 
  pthread_exit(NULL); 
}




int main(int argc, char * argv[]){
  
  if (argc!=3) {
    cout << " argument requis " << endl;
    cout << "./prog nombre_Traitements nombre_Zones" << endl;
    exit(1);
  }

 
   // initialisations 
  pthread_t threads[atoi(argv[1])];
  struct params tabParams[atoi(argv[1])];

  struct varPartagees vPartage;

  ...
  vPartage.nbZones =  atoi(argv[2]);
  ...
  
  srand(atoi(argv[1]));  // initialisation de rand pour la simulation de longs calculs
 
  // création des threards 
  for (int i = 0; i < atoi(argv[1]); i++){
    tabParams[i].idThread = ...
    tabParams[i].vPartage = ... 
    if (pthread_create(&threads[i], NULL, ...) != 0){
      perror("erreur creation thread");
      exit(1);
    }
  }

  
  // attente de la fin des  threards. Partie obligatoire 
  for (int i = 0; i < atoi(argv[1]); i++){

    ...
  }
  cout << "thread principal : fin de tous les threads secondaires" << endl;

  // libérer les ressources avant terminaison 
  ...
  return 1;
}
 
