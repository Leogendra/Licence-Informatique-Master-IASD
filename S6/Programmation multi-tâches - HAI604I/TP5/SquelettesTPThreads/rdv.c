#include <sys/types.h>
#include <pthread.h>
#include <unistd.h>
#include "calcul.h"

struct predicatRdv {

// regrouoes les donn�e partag�es entres les threads participants aux RdV :

  ...
};

struct params {

  // structure pour regrouper les param�tres d'un thread. 

  int idThread; // un identifiant de thread, de 1 � N (N le nombre
		// total de theads secondaires
  struct predicatRdv * varPartagee;


};

// fonction associ�e a chaque thread participant au RdV.

void * participant (void * p){ 

  struct params * args = (struct params *) p;
  struct predicatRdv * predicat = args -> varPartagee;
  ...

  // simulation d'un long calcul pour le travail avant RdV
  calcul (args -> idThread + rand() % 10); // c'est un exemple.

  // RdV 
  ...
  while (...) {  // attention : le dernier arriv� ne doit pas attendre. Il doit r�veiller tous les autres.
   attente
  }


  ...
  calcul ( ...); // reprise et poursuite de l'execution.


  ...
  pthread_exit(NULL); // fortement recommand�.
}




int main(int argc, char * argv[]){
  
  if (argc!=2) {
    cout << " argument requis " << endl;
    cout << "./prog nombre_Threads" << endl;
    exit(1);
  }

 
  // initialisations 
  pthread_t threads[atoi(argv[1])];
  struct params tabParams[atoi(argv[1])];

 ...

  srand(atoi(argv[1]));  // initialisation de rand pour la simulation de longs calculs
 
  // cr�ation des threards 
  for (int i = 0; i < atoi(argv[1]); i++){
    tabParams[i].idThread = ...;
    tabParams[i].varPartagee = ...; 

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

  // terminer "proprement". 
  ...
 
}
 
