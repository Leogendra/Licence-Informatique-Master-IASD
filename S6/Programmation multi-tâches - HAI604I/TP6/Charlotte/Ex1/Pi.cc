#include <stdlib.h>
#include <sys/types.h>
#include <iostream>
#include <sys/ipc.h>
#include <sys/msg.h>
#include <stdio.h>
#include <unistd.h>
#include "calcul.h"
using namespace std;

#define DEMANDE 1000
#define FIN 1001
#define ACCES 1002

int main(int argc, char * argv[]) {
    // Contrôle du nombre d'arguments
    if (argc!=3) {
        printf("Nombre d'arguments invalide, utilisation : %s fichier-pour-cle-ipc entier-pour-cle-ipc\n", argv[0]);
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

    // Récupération de l'identifiant de la file de messages 
    int id_msg = msgget(cle, 0666);

    // Gestion des erreurs
    if(id_msg == -1) {
        perror("[ERREUR] L'identifiant de la file de messages n'a pu être récupéré : ");
        exit(2);
    }

    printf("[PROCESSUS] L'identifiant de la file de messages a bien été récupéré\n");

    // Initialisation de la graine pour avoir des temps de calcul aléatoires
    srand(getpid());
    
    // Premier caclul d'un temps aléatoire
    int temps_calcul = (rand()%5);
    printf("[PROCESSUS %i] Le calcul va durer %i secondes\n", getpid(), temps_calcul);
    sleep(temps_calcul);

    // Dépôt d'un message de demande
    struct msgbuf {
        long etiquette;
        pid_t id_processus;
    };

    struct msgbuf premier_message;
    premier_message.etiquette = DEMANDE;
    premier_message.id_processus = getpid();

    if (msgsnd(id_msg, &premier_message, sizeof(premier_message), 0) == -1) {
        perror("[ERREUR] Le message de demande n'a pas pu être déposé : ");
        exit(2);
    }

    printf("[PROCESSUS] Le message de demande a bien été déposé\n");

    // Attente de l'accès à la ressource
    int acces_accorde = 0;
        
    struct msgbuf reponse_acces;
    reponse_acces.etiquette = 0;
    reponse_acces.id_processus = 0;

    if (msgrcv(id_msg, &reponse_acces, sizeof(reponse_acces), ACCES, 0) == -1) {
        perror("[ERREUR] Le message d'accès n'a pas pu être extrait : ");
        exit(2);
    }

        printf("[PROCESSUS] Le message d'accès a bien été extrait\n");
    
    // Si le processus auquel l'accès est accordé est le bon, alors on peut sortir de la boucle
    if (reponse_acces.id_processus == getpid()) {
        acces_accorde = 1;
    }

    // Second caclul d'un temps aléatoire (dans la ressource)
    temps_calcul = (rand()%5);
    printf("[PROCESSUS] Le calcul dans la ressource va durer %i secondes\n", temps_calcul);
    sleep(temps_calcul);

    // Libération de la ressource
    struct msgbuf fin_utilisation;
    fin_utilisation.etiquette = FIN;
    fin_utilisation.id_processus = getpid();

    if (msgsnd(id_msg, &fin_utilisation, sizeof(fin_utilisation), 0) == -1) {
        perror("[ERREUR] Le message de fin d'utilisation n'a pas pu être déposé : ");
        exit(2);
    }

    printf("[PROCESSUS] Le message de fin d'utilisation a bien été déposé\n");

    // Dernier caclul d'un temps aléatoire
    temps_calcul = (rand()%5);
    printf("[PROCESSUS] Le calcul va durer %i secondes\n", temps_calcul);
    sleep(temps_calcul);

    // Fin du processus
    printf("[PROCESSUS] L'ensemble des calculs ont été effectués, au revoir\n");
    return 0;
}
