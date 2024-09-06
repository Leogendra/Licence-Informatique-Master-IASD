#include <stdio.h> 
#include <unistd.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netdb.h>
#include <stdlib.h>
#include <arpa/inet.h>
#include <string.h>
#include <errno.h>

/* Programme serveur */

extern int errno;

int main(int argc, char *argv[]) {

   /* Je passe en paramètre le numéro de port qui sera donné à la socket créée plus loin.*/

   /* Je teste le passage de parametres. Le nombre et la nature des
      paramètres sont à adapter en fonction des besoins. Sans ces
      paramètres, l'exécution doit être arrétée, autrement, elle
      aboutira à des erreurs.*/
   if (argc != 2){
      printf("utilisation : %s port_serveur\n", argv[0]);
      exit(1);
   }

   /* Etape 1 : créer une socket */   
   int ds = socket(PF_INET, SOCK_DGRAM, 0);

   /* /!\ : Il est indispensable de tester les valeurs de retour de
      toutes les fonctions et agir en fonction des valeurs
      possibles. Voici un exemple */
   if (ds == -1){
      perror("Serveur : pb creation socket :");
      exit(1); // je choisis ici d'arrêter le programme car le reste
         // dépendent de la réussite de la création de la socket.
   }
   
   /* J'ajoute des traces pour comprendre l'exécution et savoir
      localiser des éventuelles erreurs */
   printf("Serveur : creation de la socket réussie \n");
   
   // Je peux tester l'exécution de cette étape avant de passer à la
   // suite. Faire de même pour la suite : n'attendez pas de tout faire
   // avant de tester.
   
   /* Etape 2 : Nommer la socket du serveur */
   struct sockaddr_in ad;
   ad.sin_family = AF_INET;
   ad.sin_addr.s_addr = htonl(INADDR_ANY);
   if (atoi(argv[1]) != -1) {
      ad.sin_port = htons((short) atoi(argv[1]));
   }
   int res = bind(ds, (struct sockaddr*)&ad, sizeof(ad));
   if (res == 0) {
      printf("Socket nommée avec succès\n");
      socklen_t sizeAd = sizeof(ad);
      getsockname(ds, (struct sockaddr*) &ad, &sizeAd);
      printf("port: %i\n",ntohs(ad.sin_port));
   } else {
      printf("Socket non nommée : %i \n", res);
      printf("errno: %d , %s\n", errno, strerror(errno));
      exit(1);
   }
   /* Etape 4 : recevoir un message du client (voir sujet pour plus de détails)*/
   struct sockaddr_in sockClient;
   socklen_t lgAdr;
   char str[INET_ADDRSTRLEN];
   char *msgRenvoi = "Merci pour ton message!";
   char buffer[1000];
   int sizeBuffer = 1000;
   res=-1;
   while (1) {
      //recevoir un message générique
      res=recvfrom(ds, &buffer, sizeBuffer, 0, (struct sockaddr*)&sockClient, &lgAdr);
      inet_ntop(AF_INET, &sockClient.sin_addr, str, INET_ADDRSTRLEN);
      printf("--- Message de longueur %i reçu de l'adresse %s\n",res,str);
      //mettre ce message générique dans le paquet
      char type = buffer[0];
      printf("Type du paquet: %i\n", (int)type);
      //reconnaitre le type de données
      if(type == 1) {
         //message string
         char *msgStr = malloc(res);
         memcpy(msgStr, buffer+1, res);
         msgStr[res-1] = '\0';
         printf("Message: %s\n", msgStr);
         free(msgStr);
      } else if(type == 2) {
         //message tableau
         int* T = (int*)malloc(res-1);
         memcpy(T, buffer+1, res-1);
         printf("Tableau: [");
         for(int i=0; i<(res-1)/sizeof(int); i++) { 
            printf(" %i ", T[i]);
         }
         printf("]\n");
         free(T);
      } else if(type == 3) {
         //message socket
         struct sockaddr_in *sock = (struct sockaddr_in*)malloc(sizeof(struct sockaddr_in));
         memcpy(sock, buffer+1, res-1);
         printf("Socket reçue contenant l'adresse %s et le port %i\n",inet_ntoa(sock->sin_addr),ntohs((short)sock->sin_port));
         free(sock);
      } else if(type == 4) {
         //message double
         double* T = (double*)malloc(res-1);
         memcpy(T, buffer+1, res-1);
         printf("Tableau: [");
         for(int i=0; i<50; i++) {
            printf(" %f ", T[i]);
         }
         printf("]\n");
         free(T);
      }

      //renvoyer un message
      res = sendto(ds, msgRenvoi, strlen(msgRenvoi)+1, 0, (struct sockaddr *)&sockClient, lgAdr);
   }

   /* Etape 6 : fermer la socket (lorsqu'elle n'est plus utilisée)*/
   close(ds);
   printf("Serveur : je termine\n");
   return 0;
}
