#include <string.h>
#include <stdio.h>
#include <sys/types.h>
#include <stdlib.h>
#include <unistd.h>
#include <pthread.h>
#include "calcul.h"

// Structure des données partagées entre les threads participant au rdv
struct predicatRdv {
};

// Structure de paramètres
struct paramsFonctionThread {
    int nbTotalThread;
    int numThread;
    int * nbThreadArrive;
    int dernier;
    pthread_mutex_t * verrou_th;
    pthread_cond_t * attente_th;
    struct predicatRdv * varPartagee;

    // S'il y a d'autres paramètres, les ajouter ici
};




void * participant (void * params){ 
    struct paramsFonctionThread * args = (struct paramsFonctionThread *) params;
    // struct predicatRdv * predicat = args->varPartagee;

    // Première partie de l'exécution
    int tempsCalcul = 1+rand()%3;
    printf("\033[%im[THREAD %i] Début de la première partie, le calcul va prendre %i secondes\033[0m\n", (args->numThread%10)+31, args->numThread, tempsCalcul);
    calcul (tempsCalcul);
    
    printf("\033[%im[THREAD %i] Fin de la première partie - %i/%i\033[0m\n", (args->numThread%10)+31, args->numThread, *args->nbThreadArrive+1, args->nbTotalThread);
    pthread_mutex_lock(args->verrou_th);
    *(args->nbThreadArrive) += 1;

    // Tous les threads arrivent et se mettent en attente, sauf le dernier qui doit réveiller les autres
    while (*(args->nbThreadArrive) <= args->nbTotalThread) {        
        if (*(args->nbThreadArrive) == args->nbTotalThread) {
            pthread_cond_broadcast(args->attente_th);
            *(args->nbThreadArrive) += 1;
            printf("\033[%im[THREAD %i] Je réveille tout le monde !\033[0m\n", (args->numThread%10)+31, args->numThread);
        }
        else {
            pthread_cond_wait(args->attente_th, args->verrou_th);
        }
    }
    pthread_mutex_unlock(args->verrou_th);
    printf("\033[%im[THREAD %i] Fin de l'attente\033[0m\n", (args->numThread%10)+31, args->numThread);

    printf("\033[%im[THREAD %i] Début de la deuxième partie\033[0m\n", (args->numThread%10)+31, args->numThread);
    
    // Deuxième partie de l'exécution
    calcul(1);

    printf("\033[%im[THREAD %i] Fin de la deuxième partie\033[0m\n", (args->numThread%10)+31, args->numThread);

    pthread_exit(NULL);
}





// Fonction principale
int main(int argc, char * argv[]){
    
    if (argc < 2){
        printf("Utilisation %s nombre_threads\n", argv[0]);
        return 1;
    }

    // Création et initialisation d'un verrou
    pthread_mutex_t verrou;
    pthread_mutex_init(&verrou, NULL);
    printf("[SERVEUR] Création du verrou principal\n");

    // Création et initialisation d'une condition
    pthread_cond_t attente;
    pthread_cond_init(&attente, NULL);
    printf("[SERVEUR] Création de la condition associée\n");

    // Initialisation du nombre de thread ayant fini la première partie de l'exécution
    int nbFin = 0;

    // Initialisation de l'identifiant du thread arrivé en dernier
    int dernierArrive = -1;

    // Tableau threads de n identifiants de threads, n étant le nombre passé en argument
    pthread_t threads[atoi(argv[1])];
    
    printf("Le thread principal a l'identificateur %lu\n", pthread_self());
    
    // Tableau de structures d'arguments
    struct paramsFonctionThread * args = (struct paramsFonctionThread *)malloc(sizeof(struct paramsFonctionThread)*atoi(argv[1]));

    // Initialisation de srand qui est utilisé dans la fonction participan
    srand(atoi(argv[1]));

    for (int i = 0; i < atoi(argv[1]); i++){
        // Remplissage de la structure d'arguments pour le thread i
        args[i].nbTotalThread = atoi(argv[1]);
        args[i].numThread = i;
        args[i].nbThreadArrive = &nbFin;
        args[i].varPartagee = NULL;
        args[i].verrou_th = &verrou;
        args[i].attente_th = &attente;
        args[i].dernier = dernierArrive;

        // Création du thread n°i
        if (pthread_create(&threads[i], NULL, participant, &args[i]) != 0){
            perror("[ERREUR] La création du thread n'a pas abouti");
            exit(1);
        }
        printf("[THREAD PRINCIPAL] Le thread %i a l'identificateur %lu\n", i, threads[i]);
    }

    printf("[THREAD PRINCIPAL] %d threads ont bien été créés\n", atoi(argv[1]));

    // Le thread principal attend chacun des threads secondaires
    for (int i = 0; i < atoi(argv[1]); i++){
        if (pthread_join(threads[i], NULL) != 0) {
                perror("[ERREUR] Le thread principal n'attend pas le thread");
                exit(1);
        }     
    }
    printf("[THREAD PRINCIPAL] Fin de tous les threads secondaires\n");
    return 0;
}