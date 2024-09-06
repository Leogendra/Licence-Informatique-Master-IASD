#include <stdio.h> 
#include <unistd.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netdb.h>
#include <stdlib.h>
#include <arpa/inet.h>
#include <string.h>

/* Programme client */

int main(int argc, char *argv[]) {

  if (argc != 4){
    printf("utilisation : %s ip_serveur port_serveur port_client\n", argv[0]);
    exit(1);
  }

  /* Etape 1 : créer une socket */   
  int ds = socket(PF_INET, SOCK_DGRAM, 0);

  if (ds == -1){
    perror("[Client] : pb creation socket :");
    exit(1);
  }
  
  printf("[Client] : creation de la socket réussie \n");
  
  /* Etape 2 : Nommer la socket du client */
   struct sockaddr_in ad;
   socklen_t len = sizeof(ad);
   ad.sin_family = AF_INET;            // IPv4
   ad.sin_addr.s_addr = INADDR_ANY;
   ad.sin_port = htons(atoi(argv[3])); 
   int res = bind(ds, (struct sockaddr *)&ad, len);
   if (res == -1){
      perror("[Client] : erreur nommage socket :");
      exit(1);
  }
  /* Etape 3 : Désigner la socket du serveur */
   struct sockaddr_in srv;
   srv.sin_family = AF_INET;
   srv.sin_addr.s_addr = inet_addr(argv[1]); //91.174.102.81:32768
   srv.sin_port = htons(atoi(argv[2]));
   
  /* Etape 4 : envoyer un message au serveur*/
   char msgUser[100];
   while(1) {
      printf("Entrer un message : ");
      scanf("%s",msgUser);
      ssize_t msg = sendto(ds, msgUser, strlen(msgUser)+1, 0, (struct sockaddr*)&srv,  sizeof(srv));

      if (msg == -1){
         perror("[Client] : pb envoi message");
         exit(1);
      }
      printf("Message bien envoyé...\n");
   
      /* Etape 5 : recevoir un message du serveur*/
      socklen_t servAdr = sizeof(srv);
      char msgServ[100];
      ssize_t servRes = recvfrom(ds, msgServ, 100, 0, (struct sockaddr*)&srv, &servAdr);

      if (servRes == -1) {
         perror("[Client] Erreur lors de la réception du message du serveur");
         exit(5);
      }

      printf("[Serveur] %s\n", msgServ);
   }

   /* Etape 6 : fermer la socket (lorsqu'elle n'est plus utilisée)*/
   shutdown(ds, SHUT_RDWR); // free(msgUser);

   printf("[Client] Sortie.\n");
  return 0;
}
