/*
  Programme bus à compléter. Les zones à compléter sont indiquées et il n'est pas nécessaire d'ajouter de nouvelles traces d'exécution.
  Vous devez expliquer en commentaires : le sens donné au messages echangés et aux étiquettes.
*/

#include <sys/types.h>
#include <unistd.h>
#include <stdio.h>
#include <sys/ipc.h>
#include <sys/msg.h>
#include <stdlib.h>
#include "simulation.h"

int main(int argc, char * argv[]){

  if (argc!=4) {
    printf("Nbre d'args invalide, utilisation :\n");
    printf("%s nombre-places fichier-pour-cle-ipc entier-pour-cle-ipc\n", argv[0]);
    exit(0);
  }
	  
  
  int cle=ftok(argv[2], atoi(argv[3]));

  int idFile = msgget(cle, 0666);

  // j'utilise msgget de sorte a s'assurer que la file existe.
  if (idFile==-1){
    perror("erreur  msgget");
    exit(-1);
  }

  printf("Bus : Id File msg : %d \n", idFile);
  
  int nbPlaces = atoi(argv[1]);
  
  // ... zone à utiliser si nécessaire
     
  
  while(1){
     
     // les traces d'exécution sont à garder inchangées.
 
    printf("Bus : embarquement immediat, soyez les bienvenus! \n");
    
    /* ... zone à compléter pour mettre en place l'embarquement
    Elle doit inclure la ligne suivante :
    
      printf("Bus : attente que le bus soit complet \n");
    */
   
    printf("Bus : bus complet, la visite commence !\n");
    
    visite(2);  // simulation de la visite. voir .h pour le paramètre
    
    printf("Bus : visite terminee, tout le monde descend !\n"); 
    
    // ... zone à utiliser pour déclancher la descente du bus
    
    printf("Bus : attendre que le bus soit vide\n");
      
    // ... zone à utiliser pour attendre que le bus soit vide
   
  }
  return 0;
}

