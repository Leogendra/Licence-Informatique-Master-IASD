#include <sys/types.h>
#include <pthread.h>
#include <unistd.h>
#include "calcul.h"

// structure qui regroupe les variables partag�es entre les threads.
struct varPartagees {
  int nbZones;
  int * di; // le tableau indiqu� dans l'�nonc�

  ... //

};

// structure qui regroupe les param�tres d'un thread
struct params {

  int idThread; // cet entier correspond au num�ro de traitement associ� � un thread
  
  struct varPartagees * vPartage;


};

// fonction associ�e � chaque thread secondaire � cr�er.

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

      // dans cette partie, le traitement de la zone i est � faire en faisant une simulation d'un long calcul (appel a calcul(...)
    ...
    
      // a la fin du traitement d'une zone, le signaler pour qu'un thread en attente se r�veille. 
      
 
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
 
  // cr�ation des threards 
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

  // lib�rer les ressources avant terminaison 
  ...
  return 1;
}
 
