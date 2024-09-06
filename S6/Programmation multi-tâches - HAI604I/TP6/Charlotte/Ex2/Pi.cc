#include <stdlib.h>
#include <sys/types.h>
#include <iostream>
#include <sys/ipc.h>
#include <sys/msg.h>
#include <stdio.h>
#include <unistd.h>
#include "calcul.h"
#include <sys/sem.h>
using namespace std;

int main(int argc, char * argv[]){

    // Contrôle du nombre d'arguments
    if (argc!=3) {
        printf("Nombre d'arguments invalide, utilisation : %s [fichier-pour-cle-ipc] [entier-pour-cle-ipc]\n", argv[0]);
        exit(0);
    }
	
    // Création de la clé à partir du fichier et de l'entier passés en argument
    key_t cle = ftok(argv[1], atoi(argv[2]));

    // Gestion des erreurs
    if (cle == -1) {
        perror("[ERREUR] La clé n'a pas pu être créée : ");
        exit(2);
    }

    printf("[PROCESSUS] La clé a bien été créée\n");

    // Récupération de l'ID du tableau
    int id_sem = semget(cle, 1, 0600);
    
    if (id_sem == -1) {
        perror("[ERREUR] L'ID du tableau de sémaphores n'a pas pu être récupéré : ");
        exit(2);
    }

    printf("[PROCESSUS] L'ID du tableau de sémaphores a bien été récupéré\n");

    // Initialisation de la graine pour avoir des temps de calcul aléatoires
    srand(getpid());
    
    // Premier caclul d'un temps aléatoire
    int temps_calcul = 5+(rand()%5);
    printf("[PROCESSUS] Le calcul va durer %i secondes\n", temps_calcul);
    sleep(temps_calcul);

    struct sembuf op_P;
    op_P.sem_num = 0; // premier et seul sémaphore
    op_P.sem_op = -1; // opération P : décrémentation de -1
    op_P.sem_flg = 0;

    struct sembuf op_Z;
    op_Z.sem_num = 0; // premier et seul sémaphore
    op_Z.sem_op = 0; // opération Z
    op_Z.sem_flg = 0;
    
    if (semop(id_sem, &op_P, 1) == -1) {
        perror("[ERREUR] Le sémaphore n'a pas pu être décrémenté : ");
        exit(3);
    }

    printf("[PROCESSUS] Le sémaphore a bien été décrémenté\n");

    if (semop(id_sem, &op_Z, 1) == -1) {
        perror("[ERREUR] On n'a pas pu vérifier la nullité du sémaphore : ");
        exit(3);
    }

    printf("[PROCESSUS] La nullité du sémaphore a bien été vérifiée, les calcules peuvent continuer \n");


    // Second calcul d'un temps aléatoire
    temps_calcul = 5+(rand()%5);
    printf("[PROCESSUS] Le calcul va durer %i secondes\n", temps_calcul);
    sleep(temps_calcul);

    // Fin du processus
    printf("[PROCESSUS] L'ensemble des calculs ont été effectués, au revoir\n");
    return 0;
}