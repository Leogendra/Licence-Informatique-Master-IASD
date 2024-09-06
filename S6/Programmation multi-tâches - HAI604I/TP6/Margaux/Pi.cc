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
    if (argc!=5) {
        printf("Nombre d'arguments invalide, utilisation : %s fichier-pour-cle-ipc entier-pour-cle-ipc nbr-de-zone numero-du-processus\n", argv[0]);
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

    // Boucle de traitement
    for(int i = 0; i < atoi(argv[3]); i++){
        printf("[PROCESSUS]  i = %i \n",i);


        // Initialisation des structures des operation P (décrément) et Z (attente jusqu'a 0)
        struct sembuf op_P;
        op_P.sem_num = i; 
        op_P.sem_op = - (atoi(argv[4]) - 1); // opération P : décrémentation de -1
        op_P.sem_flg = 0;

        // On va attendre que le semaphore d'indice soit egal a 0
        struct sembuf op_V;
        op_V.sem_num = i; 
        op_V.sem_op = atoi(argv[4]); // opération V
        op_V.sem_flg = 0;

        if(atoi(argv[4]) == 1) {
            printf("[PROCESSUS] DEBUT du traitement %i de la zone %i \n",atoi(argv[4]),i);
        }
        else{
            printf("[PROCESSUS] ATTENTE du traitement %i de la zone %i \n",atoi(argv[4]),i);
            if (semop(id_sem, &op_P, 1) == -1) {
                perror("[ERREUR] Le sémaphore n'a pas pu être décrémenté 1");
                exit(3);
            }
            printf("[PROCESSUS] Le sémaphore a bien été décrémenté\n");
        }

        // Premier caclul d'un temps aléatoire
        int temps_calcul = (rand()%5);
        printf("[PROCESSUS] Le calcul va durer %i secondes\n", temps_calcul);
        calcul(temps_calcul);

        printf("[PROCESSUS] DEBUT du traitement %i de la zone %i \n",atoi(argv[4]),i);
        if (semop(id_sem, &op_V, 1) == -1) {
            perror("[ERREUR] On n'a pas pu incrémenter le sémaphore 2 ");
            exit(3);
        }
        printf("[PROCESSUS] FIN du traitement %i de la zone %i \n",atoi(argv[4]),i);
        printf("[PROCESSUS] Fin du traitement %i de la zone %i \n",atoi(argv[4]),i);
    }

    // Fin du processus
    printf("[PROCESSUS] L'ensemble des calculs ont été effectués, au revoir\n");
    return 0;
}
