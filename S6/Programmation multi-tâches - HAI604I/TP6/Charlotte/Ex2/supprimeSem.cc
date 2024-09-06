#include <sys/types.h>
#include <unistd.h>
#include <stdio.h>
#include <sys/ipc.h>
#include <sys/sem.h>
#include <stdlib.h>

int main(int argc, char * argv[]){

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

    int id_sem = semget(cle, 1, 0600);
    
    if (id_sem == -1) {
        perror("[ERREUR] L'ID du tableau de sémaphores n'a pas pu être récupéré : ");
        exit(2);
    }

    printf("[CONTRÔLE] L'ID du tableau de sémaphores a bien été récupéré\n");

    // On s'assure de l'existence du tableau qu'on veut détruire
    if (id_sem == -1){
        perror("[ERREUR] Le tableau qu'on veut supprimer ne semble pas exister : ");
        exit(-1);
    }

    printf("[CONTRÔLE] Le tableau qu'on veut supprimer existe bien, son ID est %i\n", id_sem);

    // Destruction
    if (semctl(id_sem, 0, IPC_RMID, NULL)==-1) {
        perror("[ERREUR] Le tableau n'a pas pu être détruit : ");
        exit(0);
    }

    printf("[CONTRÔLE] Le tableau a bien été détruit\n");

    return 0;
}