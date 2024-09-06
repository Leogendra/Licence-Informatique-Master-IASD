#include <stdlib.h>
#include <sys/types.h>
#include <iostream>
#include <sys/ipc.h>
#include <sys/msg.h>
#include <stdio.h>
#include <sys/sem.h>
#include <sys/shm.h>
using namespace std;

int main(int argc, char * argv[]) {
    // Contrôle du nombre d'arguments
    if (argc!=4) {
        printf("Nombre d'arguments invalide, utilisation : %s fichier-pour-cle-ipc entier-pour-clé-ipc nombre_zone \n", argv[0]);
        exit(0);
    }

    int nbrZone = atoi(argv[3]);

    // Création de la clé à partir du fichier et de l'entier passés en argument
    key_t cle = ftok(argv[1], atoi(argv[2]));

    // Gestion des erreurs
    if (cle == -1) {
        perror("[ERREUR] La clé n'a pas pu être créée : ");
        exit(2);
    }

    printf("[CONTRÔLE] La clé a bien été créée\n");

    int id_sem = semget(cle, nbrZone, IPC_CREAT | IPC_EXCL | 0600);
    
    if (id_sem == -1) {
        perror("[ERREUR] Le tableau de sémaphores n'a pas pu être créée : ");
        exit(2);
    }

    printf("[CONTRÔLE] Le tableau de sémaphores a bien été créée, son ID est %i\n", id_sem);

    // Initialisation des sémaphores à la valeur passée en paramètres
    ushort tab_init[nbrZone];
    for (int i = 0; i < nbrZone; i++) {
        tab_init[i] = 0;
    }

    union semun {
        int val;
        struct semid_ds * buf;
        ushort * array;
    } valinit;
    
    valinit.array = tab_init;

    if (semctl(id_sem, nbrZone, SETALL, valinit) == -1){
        perror("[ERREUR] Le tableau de sémaphores n'a pas pu être copié dans l'union : ");
        exit(2);
    }

    printf("[CONTRÔLE] Le tableau de sémaphores a bien été copié dans l'union\n");

    // Test de l'affichage des sémaphores
    valinit.array = (ushort*)malloc(nbrZone * sizeof(ushort)); // On récupère un nouveau tableau

    if (semctl(id_sem, nbrZone, GETALL, valinit) == -1){
        perror("[ERREUR] Le tableau de sémaphores n'a pas été correctement rempli : ");
        exit(2);
    }
    printf("[CONTRÔLE] Le tableau de sémaphores a bien été rempli\n");
    
    printf("[CONTRÔLE] Valeurs des sémpahores après initialisation :\n["); 
    for(int i=0; i < nbrZone-1; i++){
        printf("%d, ", valinit.array[i]);
    }
    printf("%d] \n", valinit.array[nbrZone]);

    free(valinit.array);

    return 0;
}
