#include <string.h>
#include <stdio.h>
#include <sys/types.h>
#include <stdlib.h>
#include <unistd.h>
#include <pthread.h>
#include "calcul.h"

// Structure de paramètres
struct paramsFonctionThread {
    int numThread;
    // int tempsCalcul;
    int * varCommune;
    pthread_mutex_t * verrou_th;
    // S'il y a d'autres paramètres, les ajouter ici
};


// Fonction exécutée par les threads
void * fonctionThread (void * params){
    struct paramsFonctionThread * args = (struct paramsFonctionThread *) params;
    printf("[THREAD %i] Début du calcul\n", args->numThread);
    printf("[THREAD %i] Valeur du compteur avant incrémentation : %i\n", args->numThread, *(args->varCommune));
    if (args->numThread == 3) {
        printf("[THREAD %i] Ce thread va précipitamment s'arrêter...\n", args->numThread);
        pthread_exit(NULL);
        // exit(0);
    }
    for (int i = 0; i < 100000; i++) {
        pthread_mutex_lock(args->verrou_th);
        *(args->varCommune)+=1;
        pthread_mutex_unlock(args->verrou_th);

    }
    // (args->tempsCalcul);
    printf("[THREAD %i] Fin du calcul\n", args->numThread);

    return args;
}


// Fonction principale
int main(int argc, char * argv[]){
    
    if (argc < 2){
        printf("Utilisation : %s nombre_threads\n", argv[0]);
        return 1;
    }

    // Création d'une variable dans le processus
    int varProcess = 0;

    // Création et initialisation d'un verrou
    pthread_mutex_t verrou;
    pthread_mutex_init(&verrou, NULL);
    printf("[SERVEUR] Création du verrou\n");

    // Tableau threads de n identifiants de threads, n étant le nombre passé en argument
    pthread_t threads[atoi(argv[1])];
    
    printf("Le thread principal a l'identificateur %lu\n", pthread_self());

    /* // Saisie du temps du calcul que les threads feront
    int temps;
    printf("Saisissez le temps de calcul : ");
    scanf("%i", &temps); */
    
    // Tableau de structures d'arguments
    struct paramsFonctionThread * args = (struct paramsFonctionThread *)malloc(sizeof(struct paramsFonctionThread)*atoi(argv[1]));

    for (int i = 0; i < atoi(argv[1]); i++){
        // Remplissage de la structure d'arguments pour le thread i
        args[i].numThread = i;
        // couple_arg[i].tempsCalcul = temps;
        args[i].varCommune = &varProcess;
        args[i].verrou_th = &verrou;

        // Création du thread n°i
        if (pthread_create(&threads[i], NULL, fonctionThread, &args[i]) != 0){
            perror("[ERREUR] La création du thread n'a pas abouti");
            exit(1);
        }
        printf("[SERVEUR] Le thread %i a l'identificateur %lu\n", i, threads[i]);
    }

    printf("[SERVEUR] %d threads ont bien été créés\n", atoi(argv[1]));

    // Le thread principal attend chacun des threads secondaires
    for (int i = 0; i < atoi(argv[1]); i++){
        if (pthread_join(threads[i], NULL) != 0) {
                perror("[ERREUR] Le thread principal n'attend pas le thread");
                exit(1);
        }     
    }

    pthread_mutex_destroy(&verrou);
    printf("[SERVEUR] Destruction du verrou\n");


    printf("Après avoir incrémentée par tous les threads, la valeur du compteur est %i\n", varProcess);

    return 0;
}
 
