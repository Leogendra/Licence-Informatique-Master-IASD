#include <string.h>
#include <stdio.h>//perror
#include <sys/types.h>
#include <stdlib.h>
#include <unistd.h>
#include <pthread.h>


struct paramsFonctionThread {

  int idThread; //reconnaitre les thread avec un entier
  int* cpt_glob;

  // si d'autres paramètres, les ajouter ici.

};


void * fonctionThread (void * params){

  //struct paramsFonctionThread * args = (struct paramsFonctionThread *) params;
  //modification de la var globale
  int *cpt_gb = params;
  *cpt_gb++;
  printf("cpt global : %i\n",*cpt_gb);

  pthread_t id = pthread_self();
  printf("Thread %li, proc %i\n",id,getpid());
  int cpt = 0;
  sleep(3); // calcul qui dure longtemps.
  printf("Thread %li : fin\n",id);
  pthread_exit(NULL);
}


int main(int argc, char * argv[]){

  if (argc < 2){
    printf("utilisation: %s [nombre_threads]\n", argv[0]);
    return 1;
  }

  
  pthread_t threads[atoi(argv[1])];

  int cpt_global = 10;
 
  // création des threards 
  for (int i = 0; i < atoi(argv[1]); i++){
    
    // compléter pour initialiser les paramètres
    if (pthread_create(&threads[i], NULL, fonctionThread, &cpt_global) != 0){
      perror("erreur creation thread");
      exit(1);
    }
  }


// garder cette saisie et modifier le code en temps venu.
  char c[3]; 
  printf("saisir un caractère \n");
  fgets(c, 1, stdin);

  for(int j=0; j<9999999999999999; j++) {} //wait
// ... compléter

  return 0;
 
}
 
