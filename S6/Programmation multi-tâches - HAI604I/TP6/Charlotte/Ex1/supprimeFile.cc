#include <stdlib.h>
#include <sys/types.h>
#include <iostream>
#include <sys/ipc.h>
#include <sys/msg.h>
#include <stdio.h>
using namespace std;

// Ce programme supprime la file dont la clé a été passée en paramètre
// (On aurait aussi ou utiliser ipcrm -q <id_file> après avoir obtenue l'identifiant de la file via la commande ipcs)

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

    // Récupération de l'identifiant de la file de messages 
    int id_msg = msgget(cle, 0666);

    // Gestion des erreurs
    if(id_msg == -1) {
        perror("[ERREUR] L'indentifiant de la file de messages n'a pu être récupéré : ");
        exit(2);
    }
    
    printf("[CONTRÔLE] L'identifiant de la file de messages a bien été récupéré\n");

    // Suppression de la file
    if (msgctl(id_msg, IPC_RMID, NULL) == -1)
        perror("[ERREUR] La file de message n'a pas pu être supprimée : ");

    printf("[CONTRÔLE] La file de messages a bien été supprimée\n");
    
    return 0;
}
