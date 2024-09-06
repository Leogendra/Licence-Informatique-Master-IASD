#include <netinet/in.h>
#include <stdio.h> 
#include <unistd.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netdb.h>
#include <stdlib.h>
#include <arpa/inet.h>
#include <string.h>

/* Programme serveur TCP */

int main(int argc, char *argv[]) {
    /* Je passe en paramètre le numéro de port qui sera donné à la socket créée plus loin.*/

    /* Je teste le passage de parametres. Le nombre et la nature des
        paramètres sont à adapter en fonction des besoins. Sans ces
        paramètres, l'exécution doit être arrétée, autrement, elle
        aboutira à des erreurs.*/
    if (argc != 2){
        printf("Utilisation : %s [port_serveur]\n", argv[0]);
        exit(1);
    }

    /* Etape 1 : créer une socket */   
    int ds = socket(PF_INET, SOCK_STREAM, 0);

    /* /!\ : Il est indispensable de tester les valeurs de retour de
        toutes les fonctions et agir en fonction des valeurs
        possibles. Voici un exemple */
    if (ds == -1) {
        perror("[SERVEUR] Erreur lors de la création de la socket ");
        exit(1); // je choisis ici d'arrêter le programme car le reste
        // dépendent de la réussite de la création de la socket.
    }

    /* J'ajoute des traces pour comprendre l'exécution et savoir
        localiser des éventuelles erreurs */
    printf("[SERVEUR] Création de la socket réussie.\n");

    // Je peux tester l'exécution de cette étape avant de passer à la
    // suite. Faire de même pour la suite : n'attendez pas de tout faire
    // avant de tester.

    /* Etape 2 : Désignation de la socket du serveur */
    struct sockaddr_in ad;
    socklen_t len = sizeof(ad);
    ad.sin_family = AF_INET;            // IPv4
    ad.sin_addr.s_addr = INADDR_ANY;

    // Etape 3 : Nommage manuel de la socket serveur
    ad.sin_port = htons(atoi(argv[1]));

    int res = bind(ds, (struct sockaddr *)&ad, sizeof(ad));
    if (res == -1) {
        perror("[SERVEUR] Erreur lors du nommage de la socket ");
        exit(1);
    }

    // Récupération de l'adresse et du numéro de port
    if (getsockname(ds, (struct sockaddr *)&ad, &len) == -1) {
        perror("[SERVEUR] Erreur lors du nommage de la socket ");
        exit(1);
    }

    printf("[SERVEUR] En cours d'exécution : %s:%d\n", inet_ntoa(ad.sin_addr), ntohs(ad.sin_port));
        
    // Etape 4 : Mise en écoute de la socket serveur
    int adListen = listen(ds,7);
    if(adListen == -1) {
        perror("[SERVEUR] Erreur lors de la mise en écoute de la socket serveur");
        exit(1);
    }
    printf("Mise en écoute réussie ! La socket est en attente de connexion...\n");

    struct sockaddr_in sockClient;
    socklen_t lgAdr = sizeof(sockClient);
    int newConnetion = accept(ds, (struct sockaddr *)&sockClient, &lgAdr);
    if(newConnetion == -1) {
        perror("[SERVEUR] Erreur lors d'une connexion entrante");
        exit(1);
    }
    printf("Nouvelle connexion établie !\n");
    while (1) {

        /* Etape 5 : recevoir un message du client (voir sujet pour plus de détails)*/
        int msgSize = 4000;
        char msg[4000];
        ssize_t res = recv(newConnetion,msg,msgSize,0);
        if (res == -1) {
            perror("[SERVEUR] Erreur lors de la réception du message ");
            exit(1);
        }
        msg[res] = '\0';
        printf("[SERVEUR] Message reçu : %s, nombre d'octets :%li\n", msg, res);
        printf("[SERVEUR] Adresse du client : %s:%i\n", inet_ntoa(sockClient.sin_addr), ntohs(sockClient.sin_port));
        
        if (res == 0)
        {
            perror("[SERVEUR] Le client a fermé la connexion");
            exit(1);
        }
        
        /* Etape 6 : envoyer un message au serveur (voir sujet pour plus de détails) */
        char len[100];
        sprintf(len, "Taille du message reçu par le serveur : %zu\n", strlen(msg));
        if (sendto(newConnetion, len, strlen(len) + 1, 0, (const struct sockaddr*)&sockClient, lgAdr) == -1) {
            perror("[SERVEUR] Erreur lors du retour au client ");
            exit(5);
        }
    }

    /* Etape 7 : fermer la socket (lorsqu'elle n'est plus utilisée)*/
    shutdown(ds, SHUT_RDWR);

    printf("[SERVEUR] Sortie.\n");
    return 0;
}
