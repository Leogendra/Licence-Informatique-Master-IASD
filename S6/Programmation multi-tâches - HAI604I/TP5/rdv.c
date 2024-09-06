#include <stdio.h>
#include <sys/types.h>
#include <pthread.h>
#include <unistd.h>
#include <stdlib.h>
#include <string.h>

struct predicatRdv {
    //données partagées entres les threads participants aux RdV :
    pthread_mutex_t lock;
    pthread_cond_t cond;
    int finis;
};

struct params {
    //paramètres d'un thread. 
    int idThread;
    int n;
    struct predicatRdv *varPartagee;
};


void * participant (void *p) {
    struct params *args = (struct params *) p;

    int wait = rand() % (args->idThread + 5);
    printf("Thread %i : %i secondes d'attente\n", args->idThread, wait);
    sleep(wait);

    // RDV
    pthread_mutex_lock(&args->varPartagee->lock);
    args->varPartagee->finis += 1;
    printf("Thread %i : %i/%i.\n", args->idThread, args->varPartagee->finis, args->n);
    if (args->varPartagee->finis < args->n) {
        pthread_cond_wait(&args->varPartagee->cond, &args->varPartagee->lock);
    }
    //si le dernier arrive, on réveille tout le monde
    else if (args->varPartagee->finis == args->n) {
        printf("Thread %i : Wake up Threads !\n", args->idThread);
        pthread_cond_broadcast(&args->varPartagee->cond);
    }
    pthread_mutex_unlock(&args->varPartagee->lock);

    wait = args->idThread;
    printf("Thread %i : Suite des calculs, %i secondes d'attente\n", args->idThread, wait);
    sleep(wait);

    //recommendé
    pthread_exit(NULL);
}

int main(int argc, char *argv[]){
    if (argc != 2) {
        printf("Utilisation: %s nombre_threads\n", argv[0]);
        exit(1);
    }

    // Initialisations 
    pthread_t threads[atoi(argv[1])];
    struct params tabParams[atoi(argv[1])];
    struct predicatRdv predicat;
    predicat.finis = 0;

    int err;
    if ((err = pthread_mutex_init(&predicat.lock, NULL)) != 0) {
        printf("Erreur : %s\n", strerror(err));
        exit(EXIT_FAILURE);
    }
    if ((err = pthread_cond_init(&predicat.cond, NULL)) != 0) {
        printf("Erreur : %s\n", strerror(err));
        exit(EXIT_FAILURE);
    }

    
    // Création des threards 
    for (int i = 0; i < atoi(argv[1]); i++){
        tabParams[i].idThread = i + 1;
        tabParams[i].n = atoi(argv[1]);
        tabParams[i].varPartagee = &predicat; 

        if (pthread_create(&threads[i], NULL, participant, (void*)&tabParams[i]) != 0) {
            perror("Erreur : problème lors de la création du thread ");
            exit(1);
        }
    }

    // Attente de la fin des  threards. Partie obligatoire 
    for (int i = 0; i < atoi(argv[1]); i++) {
        pthread_join(threads[i], NULL);
    }
    printf("Thread principal : fin de tous les threads secondaires.\n");

    // terminer "proprement". 
    pthread_cond_destroy(&predicat.cond);
    pthread_mutex_destroy(&predicat.lock);
    return 0;
}
 
