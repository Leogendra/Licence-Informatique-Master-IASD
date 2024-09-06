#include <stdlib.h>
#include <sys/types.h>
#include <sys/wait.h>
#include <stdio.h> //perror
#include <unistd.h>

// une base pour commencer à mettre en place des arboressances de processus avec fork()

int  main (int argc, char *argv[]){

  if (argc != 2) {
    printf ("Utilisation : %s nombre_de_processus_fils\n", argv[0]);
    exit(1);
  }

  int nbProc = atoi(argv[1]);

  int parent = getpid();
  printf("Avant fork : pid parent racine : %d \n", parent);

  int resFork = fork();
  if (resFork == -1) {
	perror("Erreur fork : "); 
	exit(1);
  }
  if (parent == getpid()){ // je suis dans le processus racine
     printf("Après fork : je suis le processus : %d et je commence mon travail\n", parent);
     // je fait une simulation d'un calcul en parralèle avec le fils
     sleep (5); // s'endormir pendant 5 secondes     	
  }
  
  if (parent != getpid()){
    printf("Après fork : je suis le processus : %d et je commence mon travail \n", getpid());
    sleep(10);
    printf("Je suis le processus : %d et j'ai terminé mon travail\n", getpid());
   exit(0);
  }
  
  // la suite ici n'est faite que par le processus racince
    printf("Je suis le processus : %d. J'ai terminé mon travail et doit attendre la fin du processus fils que j'ai créé\n", getpid());  
   
    while(wait(0)!=-1);  // cette instruction permet d'attendre la fin de l'execution de tous mes fils.
     printf("Je suis le processus : %d, mon fils s'est terminé, je peux terminer à mon tour\n", getpid()); 
  
  return 0;
}







