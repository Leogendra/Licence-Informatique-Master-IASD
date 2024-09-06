
/*
 Programme ronde avec un thread bus et des threads visiteurs. 
 Les zones � compl�ter sont indiqu�es en commentaires.
 
 Les traces fournies sont suffisantes.
 
 Vous avez � votre disposition test/zoo qui est un ex�cutable vous illustrant le comportement attendu.
 
*/

#include <sys/types.h>
#include <pthread.h>
#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>
#include "simulation.h"


  // structure regroupant toutes le varables partag�es entre threads
struct varPartagees {
  int nbPlaces; // nombre total de places dans le bus
  int nbVisiteursPresents;
  int nbRestants;
  pthread_mutex_t * verrou_montee;
  pthread_cond_t * cond_plein;
  pthread_cond_t * cond_vide;
  pthread_cond_t * cond_ouvert;
  pthread_cond_t * cond_fin;
};


  // structure pour regrouper les param�tres d'un thread. 
struct params {
  int idThread; // un identifiant de visiteur, de 1 � N (N le nombre total de visiteurs)
  struct varPartagees * varP;
};

// pour le thread bus
void * bus (void * p) {

  struct params * args = (struct params *) p;
  struct varPartagees * varP  = args -> varP;
  
  while(1){



   if (varP->nbRestants == 0) {
     afficher('b', "Tous les visiteurs ont fini la visite !", 0);
      pthread_exit(NULL); 
   }
   else if (varP->nbRestants < varP->nbPlaces) {
     afficher('b', "Il n'y a pas assez de visiteurs pour une dernière visite", 0);
      pthread_exit(NULL); 
   }
  
   afficher('b', "embarquement immediat, soyez les bienvenus!", 0);
   

   // ... zone � compl�ter pour lancer l'embarquement
   pthread_cond_broadcast(varP->cond_ouvert);
   /* ... zone a compl�ter pour attendre que le bus soit plein
    cette zone doit inclure la ligne :
    
      afficher('b', "attente que le bus soit complet", 0);
   */
 pthread_cond_wait(varP->cond_plein, varP->verrou_montee);
   afficher('b',"bus complet, la visite commence !", 0);

   visite(2); // vous pouvez changer la valeur du param�tre (voir .h)
   
   afficher('b',"visite terminee, tout le monde descend !", 0);
   pthread_cond_broadcast(varP->cond_fin);
   
   // ... zone � compl�ter pour ordonner la descente du bus
   
   afficher('b', "attente que tout le monde descende", 0);
 pthread_cond_wait(varP->cond_vide, varP->verrou_montee);

 afficher('b', "Tout le monde est descendu", 0);
   /* ... zone a compl�ter pour attendre que le bus soit vide
    cette zone doit inclure la ligne :
    
      afficher('b', "attente que tout le monde descende", 0);
   */
   
  }

  pthread_exit(NULL); 
}


///////////////////////////////////////////////////////////////////////////////////////


// pour le thread visiteur
void * visiteur (void * p) {

  struct params * args = (struct params *) p;
  struct  varPartagees * varP = args -> varP;
  
   afficher('v', "je demande a monter", args -> idThread);

  pthread_mutex_trylock(varP->verrou_montee);
   //printf(" --- %i places ---\n", varP->nbPlaces);
   //printf(" --- %i presents ---\n", varP->nbVisiteursPresents);
   while (varP->nbPlaces <= varP->nbVisiteursPresents) {
      afficher('v', "pas de place, j'attends", args -> idThread);
      pthread_cond_wait(varP->cond_ouvert, varP->verrou_montee);
      pthread_mutex_trylock(varP->verrou_montee);
   }
   

   // simulation mont�e du visiteur
   afficher('v', "je monte ...", args -> idThread);
   varP->nbVisiteursPresents++;
    pthread_mutex_unlock(varP->verrou_montee);
   
   printf(" --- il reste %i places ---\n", varP->nbPlaces - varP->nbVisiteursPresents);
  
   monterOuDescendre();
   // .. zone qui pourrait �ventuellement vous �tre utile
   afficher('v', "je suis a bord et bien installe !", args -> idThread);

   if ((varP->nbPlaces - varP->nbVisiteursPresents) == 0) {
     pthread_cond_broadcast(varP->cond_plein);
   }
   else {
   pthread_cond_wait(varP->cond_plein, varP->verrou_montee);
   }
   
  // ... zone qui peut, en fonction de votre solution, �tre utile pour compl�ter la mise en place de la mont�e.
   
   // ici se produit automatiquement la visite qui est g�r�e par le bus
   
  afficher('v', "c'est parti pour la visite !", args -> idThread);
   /*
   ... zone � compl�ter pour la mise en place de la descente du passager.
   Elle doit inclure la ligne :
   
    afficher('v', "j'attends la fin de la visite", args -> idThread);
   */
  afficher('v', "j'attends la fin de la visite", args -> idThread);
  pthread_cond_wait(varP->cond_fin, varP->verrou_montee);
    
  
   varP->nbRestants--;
   afficher('v', "visite terminee, je descends ...", args -> idThread);
   pthread_mutex_trylock(varP->verrou_montee);
   varP->nbVisiteursPresents--;
   // .. zone qui pourrait �ventuellement vous �tre utile
   monterOuDescendre();
   // .. zone qui pourrait �ventuellement vous �tre utile
 
 printf(" --- il reste %i visiteurs à bord ---\n",varP->nbVisiteursPresents);
  if (varP->nbVisiteursPresents <= 0) {
     pthread_cond_broadcast(varP->cond_vide);
   }
   pthread_mutex_unlock(varP->verrou_montee);
   afficher('v', "je suis descendu !", args -> idThread);
   
  // ... zone qui peut, en fonction de votre solution, �tre utile pour compl�ter la mise en place de la descente.
   
  pthread_exit(NULL); 
}

///////////////////////////////////////////////////////////////////////////////////////

int main(int argc, char * argv[]){
  
  if (argc!=3) {
    printf(" argument requis \n %s [places_bus] [nombre_visiteurs]\n", argv[0]);
    exit(1);
  }

 initDefault(atoi(argv[2])); // ne pas modifier cet appel ni le d�placer.
 
 
  // initialisations 
  pthread_t threads[atoi(argv[2])+1];
  struct params tabParams[atoi(argv[2])+1];
 
  struct varPartagees varP;
  
  varP.nbPlaces = atoi(argv[1]);
  varP.nbVisiteursPresents = 0;
  varP.nbRestants = atoi(argv[2]);
  //... zone � compl�ter pour initialiser les tous les champs de varP

// Création et initialisation d'un verrou
    pthread_mutex_t montee;
    pthread_mutex_init(&montee, NULL);

    pthread_cond_t busPlein;
    pthread_cond_init(&busPlein, NULL);

    pthread_cond_t busVide;
    pthread_cond_init(&busVide, NULL);

    pthread_cond_t busOuvert;
    pthread_cond_init(&busOuvert, NULL);

    pthread_cond_t fini;
    pthread_cond_init(&fini, NULL);

    varP.verrou_montee = &montee;
    varP.cond_plein = &busPlein;
    varP.cond_vide = &busVide;
    varP.cond_ouvert = &busOuvert;
    varP.cond_fin = &fini;
 
  // cr�ation des threads
  tabParams[0].idThread = 0;
  tabParams[0].varP = &varP; 
  if (pthread_create(&(threads[0]), NULL, bus, &(tabParams[0])) != 0){
      perror("erreur creation thread bus");
      exit(1);
    }  
  for (int i = 1; i < atoi(argv[2])+1; i++){
    tabParams[i].idThread = i;
    tabParams[i].varP = &varP; 
    if (pthread_create(&threads[i], NULL, visiteur, &(tabParams[i])) != 0){
      perror("erreur creation thread visiteur");
      exit(1);
    }
  }
  

  // attente de la fin des  threads. 
  if (pthread_join(threads[0], NULL) != 0){
      perror("erreur attente du bus");
      exit(1);
    }
    
  // ... zone � compl�ter pour terminer proprement votre programme.
  printf("Fermeture du zoo\n");

  
  pthread_mutex_destroy(&montee); 
  pthread_cond_destroy(&busPlein);
  pthread_cond_destroy(&busVide);
  pthread_cond_destroy(&busOuvert);
  pthread_cond_destroy(&fini);
  
  exit(1);
}
 
