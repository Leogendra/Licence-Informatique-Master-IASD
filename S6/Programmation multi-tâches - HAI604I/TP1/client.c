#include <stdio.h> 
#include <unistd.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netdb.h>
#include <stdlib.h>
#include <arpa/inet.h>
#include <string.h>
#include <errno.h>

/* Programme client */

//serveur fac : 91.174.102.81:32768

extern int errno;

// enrobage des messages pour qu'il contienne le type
typedef struct {
   char type;
   char contenu[17];
} enrobage;

int main(int argc, char *argv[]) {

   /* je passe en paramètre l'adresse de la socket du serveur (IP et
      numéro de port) et un numéro de port à donner à la socket créée plus loin.*/
   if (argc != 4){
      printf("utilisation : %s ip_serveur port_serveur port_client\n", argv[0]);
      exit(1);
   }

   /* Etape 1 : créer une socket */   
   int ds = socket(PF_INET, SOCK_DGRAM, 0);

   /* /!\ : Il est indispensable de tester les valeurs de retour de
      toutes les fonctions et agir en fonction des valeurs
      possibles. Voici un exemple */
   if (ds == -1){
      perror("Client : pb creation socket :");
      exit(1); // je choisis ici d'arrêter le programme car le reste
         // dépendent de la réussite de la création de la socket.
   }
   
   /* J'ajoute des traces pour comprendre l'exécution et savoir
      localiser des éventuelles erreurs */
   printf("Client : creation de la socket réussie \n");
   
   /* Etape 2 : Nommer la socket du client */
   struct sockaddr_in ad;
   ad.sin_family = AF_INET;
   ad.sin_addr.s_addr = INADDR_ANY;
   ad.sin_port = htons((short) atoi(argv[3]));
   int res;
   res = bind(ds, (struct sockaddr*)&ad, sizeof(ad));
   if (res == 0) {
      printf("Socket nommée avec succès\n");
   } else {
      printf("Socket non nommée : %i \n", res);
      printf("errno: %d , %s\n", errno, strerror(errno));
      exit(1);
   }

   socklen_t size = sizeof(ad);
   getsockname(ds, (struct sockaddr*) &ad, &size);
   printf("%s:%i\n", inet_ntoa(ad.sin_addr), ntohs((short) ad.sin_port));

   /* Etape 3 : Désigner la socket du serveur */
   struct sockaddr_in sockServ;
   sockServ.sin_family = AF_INET;
   sockServ.sin_addr.s_addr = inet_addr(argv[1]);
   sockServ.sin_port = htons((short)atoi(argv[2]));
   socklen_t lgAdr = sizeof(struct sockaddr_in);

   /* Etape 4 : envoyer un message au serveur  (voir sujet pour plus de détails)*/
   int choix = -1;
   char option[10];
   ssize_t resultat;
   while(choix != 0) {
      choix = -1;
      printf("\n-------------------\nChoisir une option:\n");
      printf("0 - Quitter\n");
      printf("1 - Envoyer un message\n");
      printf("2 - Envoyer un tableau d'entiers\n");
      printf("3 - Envoyer une socket\n");
      printf("4 - Envoyer un tableau de doubles\n");
      printf("Votre choix: ");
      fgets(option, sizeof(option), stdin);
      switch(option[0]) {
         case '1':
            choix = 1;
            break;
         case '2':
            choix = 2;
            break;
         case '3':
            choix = 3;
            break;
         case '4':
            choix = 4;
            break;
         default:
         case '0':
            choix = 0;
            break;
      }
      if(choix != 0 && choix != -1) {
         enrobage *paquet = (enrobage*)malloc(sizeof(enrobage));
         int taille=0;
         // message
         if(choix == 1) {
            char msgEnvoi[200];
            printf("Envoyer un message: ");
            fgets(msgEnvoi, sizeof(msgEnvoi), stdin);
            //caster dans un enrobage
            paquet->type = 1;
            memcpy(paquet->contenu, msgEnvoi, strlen(msgEnvoi)+1);
            taille = strlen(msgEnvoi) +2; // +1 '\0' ; +1 type
         }
         // tableau
         if(choix == 2) {
            int tab[10];
            int sizeTab = 10;
            for(int i=0; i<sizeTab; i++) tab[i] = i;
            //caster dans un enrobage
            paquet->type = 2;
            memcpy(paquet->contenu, tab, sizeof(int)*sizeTab);
            taille = sizeof(int)*sizeTab +1; // +1 type
         }
         // socket
         if(choix == 3) {
            paquet->type = 3;
            memcpy(paquet->contenu, &sockServ, sizeof(struct sockaddr_in));
            taille = sizeof(struct sockaddr_in);
         }
         // tableau de doubles 
         if(choix == 4) {
            double tab[1000];
            int sizeTab = 1000;
            for(int i=0; i<sizeTab; i++) tab[i] = (double)i;
            //caster dans un enrobage
            paquet->type = 4;
            memcpy(paquet->contenu, tab, sizeof(double)*sizeTab);
            taille = sizeof(double)*sizeTab +1; // +1 type
         }
         // envoi au serveur
         resultat = sendto(ds, paquet, taille, 0, (struct sockaddr *)&sockServ, lgAdr);
         printf("Message envoyé à l'adresse %s / port %i / longueur %li\n",inet_ntoa(sockServ.sin_addr),ntohs(sockServ.sin_port),resultat);
         // reponse du serveur
         char message[200];
         char str[INET_ADDRSTRLEN];
         res=-1;
         while(res == -1) {
            res=recvfrom(ds, message, sizeof(message), 0, (struct sockaddr*)&sockServ, &lgAdr);
            inet_ntop(AF_INET, &sockServ.sin_addr, str, INET_ADDRSTRLEN);
            printf("Message de longueur %i reçu de l'adresse %s\nMessage: \"%s\"\n",res,str,message);
         }
         free(paquet);
      }
   }

   /* Etape 6 : fermer la socket (lorsqu'elle n'est plus utilisée)*/
   close(ds);
   printf("Client : je termine\n");
   return 0;
}
