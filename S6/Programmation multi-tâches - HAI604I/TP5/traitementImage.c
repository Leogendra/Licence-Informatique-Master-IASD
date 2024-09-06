#include <sys/types.h>
#include <pthread.h>
#include <unistd.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include "calcul.h"

// structure qui regroupe les variables partagées entre les threads.
struct varPartagees {
  int nbZones;
  int * di; //représentation des zones
  pthread_cond_t cond; // Tableau des conditions deux à deux
  pthread_mutex_t lock;

};

// structure qui regroupe les paramétres d'un thread
struct params {
  int idThread;
  struct varPartagees * vPartage;

};

// fonction associée à chaque thread secondaire à créer.
void * traitement (void * p) {

  struct params * args = (struct params *) p;
  struct  varPartagees *  vPartage = args -> vPartage;

  printf("Début traitement n°%i\n",args->idThread);

  for(int i = 0; i < vPartage->nbZones; i++){
   pthread_mutex_lock(&vPartage->lock);
    //printf("Traitement n°%i de la zone %i \n",args->idThread, i);
    
    if(args->idThread != 0){ // le premier traitement n'attent personne
      while(vPartage->di[i] != args->idThread){
        printf("Traitement %i attends la zone %i\n", args->idThread,i);
        pthread_cond_wait(&vPartage->cond, &vPartage->lock);
        //printf("Traitement %i se réveille et vérouille le verrou\n", args->idThread);
      }
    }
    pthread_mutex_unlock(&vPartage->lock);
    

    // dans cette partie, le traitement de la zone i est à faire en faisant une simulation d'un long calcul (appel a calcul(...)
    printf("Traitement n°%i de la zone %i \n",args->idThread, i);
    calcul(args->idThread*3 + 2);
    printf("Fin du traitement n°%i de la zone %i \n",args->idThread, i);
    pthread_mutex_lock(&vPartage->lock);
    vPartage->di[i] = args->idThread;

    // a la fin du traitement d'une zone, le signaler pour qu'un thread en attente se réveille. 
    pthread_cond_broadcast(&vPartage->cond);
    pthread_mutex_unlock(&vPartage->lock);
      
  }

  pthread_exit(NULL);
}

int main(int argc, char * argv[]){
  
  if (argc!=3) {
    printf("Utilisation: %s nombre_traitements nombre_zones\n", argv[0]);
    exit(EXIT_FAILURE);
  }

  // initialisations 
  pthread_t threads[atoi(argv[1])];
  struct params tabParams[atoi(argv[1])];
  struct varPartagees vPartage;
  vPartage.nbZones =  atoi(argv[2]);
  vPartage.di = malloc(atoi(argv[2])*sizeof(int));

  pthread_cond_t cond;
  pthread_cond_init(&cond, NULL);
  vPartage.cond = cond;  
  
  srand(atoi(argv[1]));  // initialisation de rand pour la simulation de longs calculs
 
  // création des threards 
  for (int i = 0; i < atoi(argv[1]); i++){
    tabParams[i].idThread = i;
    tabParams[i].vPartage = &vPartage; 
    if (pthread_create(&threads[i], NULL, traitement, &tabParams[i]) != 0){
      perror("erreur creation thread");
      exit(1);
    }
  }

  
  // attente de la fin des  threards. Partie obligatoire 
  for (int i = 0; i < atoi(argv[1]); i++){
    pthread_join(threads[i], NULL);
  }
  printf("Thread principal : fin de tous les threads secondaires.\n");


  // libérer les ressources avant terminaison 
  for (int i = 0; i < atoi(argv[1]); i++){
    pthread_cond_destroy(&vPartage.cond);
  }
  pthread_mutex_destroy(&vPartage.lock);
  free(vPartage.di);
  return 0;
}
 
