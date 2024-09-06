#include <stdlib.h>
#include <sys/types.h>
#include <iostream>
#include <sys/ipc.h>
#include <sys/msg.h>
#include <stdio.h>
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

    printf("[CONTRÔLE] La clé a bien été créée\n");

    // Création de la file de messages 
    int id_msg = msgget(cle, IPC_CREAT|0666);

    // Gestion des erreurs
    if(id_msg == -1) {
        perror("[ERREUR] La file de message n'a pas pu être créée : ");
        exit(2);
    }
    
    printf("[CONTRÔLE] La file de message a bien été créée\n");

    // Création de la structure d'un message
    struct msgbuf {
        long etiquette;
        pid_t id_processus;
    };

    int ressource_libre = 1;


    while(1) {
        // Si la ressource est disponible
        if (ressource_libre) {
            ressource_libre = 0;
            // On extrait un message de demande
            struct msgbuf reception;
            reception.etiquette = 0;
            reception.id_processus = 0;
            
            if (msgrcv(id_msg, &reception, sizeof(reception), DEMANDE, 0) == -1) {
            perror("[ERREUR] Le message de demande n'a pas pu être extrait : ");
            exit(2);
            }

            printf("[CONTRÔLE] Le message de demande %i a bien été extrait\n",reception.id_processus);

            // On envoie un message d'accès
            struct msgbuf donne_acces;
            donne_acces.etiquette = ACCES;
            donne_acces.id_processus = reception.id_processus;

            if (msgsnd(id_msg, &donne_acces, sizeof(donne_acces), 0) == -1) {
            perror("[ERREUR] Le message d'accès n'a pas pu être déposé : ");
            exit(2);
            }

            printf("[CONTRÔLE] Le message d'accès à %i a bien été déposé\n", reception.id_processus);

            // On attend un message de fin d'acces
            struct msgbuf fin_acces;
            fin_acces.etiquette = 0;
            fin_acces.id_processus = 0;

            if (msgrcv(id_msg, &fin_acces, sizeof(fin_acces), FIN, 0) == -1) {
            perror("[ERREUR] Le message de fin d'accès n'a pas pu être extrait : ");
            exit(2);
            }

            printf("[CONTRÔLE] Le message de fin d'accès à %i a bien été extrait\n", fin_acces.id_processus);

            ressource_libre = 1;
        }
    }

    return 0;
}
