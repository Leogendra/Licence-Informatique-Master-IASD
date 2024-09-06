#include <string.h>
#include <stdio.h>
#include <sys/types.h>
#include <stdlib.h>
#include <unistd.h>
#include <pthread.h>
#include "calcul.h"

// Structure de variables partagées
struct varPartagees {
    int nbZones;
    int * di;
    pthread_mutex_t * verrou_di;
    pthread_cond_t * cond_di;
};


// Structure de paramètres
struct paramsFonctionThread {
    int numThread;
    int tempsCalcul;
    struct varPartagees * varCommunes;
    // S'il y a d'autres paramètres, les ajouter ici
};


// Fonction exécutée par les threads
void * traitement (void * params){
    struct paramsFonctionThread * args = (struct paramsFonctionThread *) params;
    struct  varPartagees * varCommunes = args->varCommunes;

    printf("\033[%im[THREAD %i] Ce traitement a un temps de calcul de %i secondes\033[0m\n", (args->numThread%10)+31, args->numThread, args->tempsCalcul);

    for(int i = 0; i < varCommunes->nbZones; i++) {
        // Tous les traitements doivent vérifier que la zone suivante est libre avant d'y rentrer, sauf le premier
        if (args -> numThread != 0) {
            // Attente de fin du traitement de la zone i
            while(varCommunes->di[args->numThread] >= varCommunes->di[args->numThread - 1]) {
                //printf("%i, %i\n", varCommunes->di[args->numThread], varCommunes->di[args->numThread - 1]);
                printf("\033[%im[THREAD %i] J'attends la fin du traitement de la zone %i\033[0m\n", (args->numThread%10)+31, args->numThread, varCommunes->di[args->numThread]);
                pthread_cond_wait(varCommunes->cond_di, varCommunes->verrou_di);
            }
            //printf("%i, %i\n", varCommunes->di[args->numThread], varCommunes->di[args->numThread - 1]);

            printf("\033[%im[THREAD %i] Début du traitement de la zone %i\033[0m\n", (args->numThread%10)+31, args->numThread, varCommunes->di[args->numThread]);
            calcul(args->tempsCalcul);
            pthread_mutex_trylock(varCommunes->verrou_di);
            // Début du traitement par le traitement 1, il est actuellement sur la case 0
            printf("\033[%im[THREAD %i] Fin du traitement de la zone %i\033[0m\n", (args->numThread%10)+31, args->numThread, varCommunes->di[args->numThread]);
            varCommunes->di[args->numThread]++;
            pthread_mutex_unlock(varCommunes->verrou_di);

        }
        // Dans le cas du premier traitement
        else {
            printf("\033[%im[THREAD %i] Premier traitement de la zone %i\033[0m\n", (args->numThread%10)+31, i);

            pthread_mutex_lock(varCommunes->verrou_di);
            varCommunes->di[args->numThread] = varCommunes->di[args->numThread]<0?0:varCommunes->di[args->numThread];
            pthread_mutex_unlock(varCommunes->verrou_di);

            printf("\033[%im[THREAD %i] Début du traitement de la zone %i\033[0m\n", (args->numThread%10)+31, args->numThread, varCommunes->di[args->numThread]);
            calcul(args->tempsCalcul);
            pthread_mutex_lock(varCommunes->verrou_di);
            // Début du traitement par le traitement 1, il est actuellement sur la case 0
            printf("\033[%im[THREAD %i] Fin du traitement de la zone %i\033[0m\n", (args->numThread%10)+31, args->numThread, varCommunes->di[args->numThread]);
            varCommunes->di[args->numThread]++;
            pthread_mutex_unlock(varCommunes->verrou_di);
        }
      
        // À la fin du traitement d'une zone, un signal réveille les trheads
        pthread_cond_broadcast(varCommunes->cond_di);
        printf("\033[%im[THREAD %i] Réveil des threads !\033[0m\n", args->numThread);
    }
    return args;
    pthread_exit(NULL); 
}




int main(int argc, char * argv[]){
    
    if (argc < 3){
        printf("Utilisation : %s nombre_traitements nombre_zones\n", argv[0]);
        return 1;
    }
 
    // Initialisations 
    pthread_t threads[atoi(argv[1])];
    struct varPartagees vPartage;
    // Tableau de structures d'arguments
    struct paramsFonctionThread * args = (struct paramsFonctionThread *)malloc(sizeof(struct paramsFonctionThread)*atoi(argv[1]));

    // Initialisation de srand qui est utilisé dans la fonction participan
    srand(atoi(argv[1]));
    
    // Remplissage de la structure de variables partagées
    vPartage.nbZones = atoi(argv[2]);

    int ZonesParTraitement[atoi(argv[1])];
    for (int i = 1; i<atoi(argv[1]); i++) {
        ZonesParTraitement[i] = 0;
    }
    ZonesParTraitement[0] = -1;
    vPartage.di = ZonesParTraitement;

    pthread_mutex_t verrou_ZonesParTraitement;
    pthread_mutex_init(&verrou_ZonesParTraitement, NULL);

    pthread_cond_t cond_ZonesParTraitement;
    pthread_cond_init(&cond_ZonesParTraitement, NULL);

    vPartage.verrou_di = &verrou_ZonesParTraitement;
    vPartage.cond_di = &cond_ZonesParTraitement;



    // Création des threads
    for (int i = 0; i < atoi(argv[1]); i++) {
        // Remplissage de la structure d'arguments pour le thread i
        args[i].numThread = i;
        args[i].tempsCalcul = (rand()%2); // Chaque traitement prend entre une et dix secondes
        args[i].varCommunes = &vPartage;
                
        // Création du thread n°i
        if (pthread_create(&threads[i], NULL, traitement, &args[i]) != 0) {
            perror("[ERREUR] La création du thread n'a pas abouti");
            exit(1);
        }

        printf("[THREAD PRINCIPAL] Le thread %i a l'identificateur %lu\n", i, threads[i]);

    }

    
    // Le thread principal attend chacun des threads secondaires
    for (int i = 0; i < atoi(argv[1]); i++){
        if (pthread_join(threads[i], NULL) != 0) {
                perror("[ERREUR] Le thread principal n'attend pas le thread");
                exit(1);
        }     
    }
    printf("[THREAD PRINCIPAL] Fin de tous les threads secondaires\n");

    // Libération des ressources
    free(args);

    return 0;
}
 
