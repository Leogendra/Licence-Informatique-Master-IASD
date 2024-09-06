#include <stdio.h>//perror
#include <sys/types.h>
#include <netdb.h>
#include <arpa/inet.h>
#include <sys/socket.h>
#include <unistd.h>//close
#include <stdlib.h>
#include <string.h>


#define MAX_BUFFER_SIZE 146980


int main(int argc, char *argv[])
{
  /* etape 0 : gestion des paramètres si vous souhaitez en passer */

  if (argc != 2){
        printf("Utilisation : %s [port_serveur]\n", argv[0]);
        exit(1);

  }
  /* etape 1 : creer une socket d'écoute des demandes de connexions*/
  
  int ds = socket(PF_INET, SOCK_STREAM, 0);
  if (ds == -1) {
    perror("[Serveur] Erreur lors de la création de la socket");
    exit(1);
  }
  
  /* etape 2 : nommage de la socket */
 
  struct sockaddr_in ad;
  socklen_t longueur = sizeof(ad);
  ad.sin_family = AF_INET;
  ad.sin_addr.s_addr = INADDR_ANY;

  ad.sin_port = htons(atoi(argv[1]));

  int resultat = bind(ds, (struct sockaddr *)&ad, longueur);
  if (resultat == -1) {
    perror("[Serveur] Erreur lors du nommage de la socket");
    exit(2);
  }

  if (getsockname(ds, (struct sockaddr *)&ad, &longueur) == -1) {
    perror("[Serveur] Errreur lors du nommage d'la socket");
    exit(4);
  }

  printf("[Serveur] En cours d'exécution : %s:%d\n", inet_ntoa(ad.sin_addr),ntohs(ad.sin_port));

  /* etape 3 : mise en ecoute des demandes de connexions */
 
  int adEcoute = listen(ds,7);
  if (adEcoute == -1) {
    perror("[Serveur] Erreur lors de la mise en écoute d'la socket");
    exit(1);
  }

  printf("[Serveur] Mise en écoute réussie\nEn attente de connexion...\n");

  struct sockaddr_in sockClient;
  socklen_t longueurAddr = sizeof(sockClient);
  int newCo = accept(ds, (struct sockaddr *)&sockClient, &longueurAddr);

  if (newCo == -1) {
    perror("[Serveur] Erreur lors d'une connexion entrantrante");
    exit(3);
  }

  printf("[Serveur] Nouvelle connexion établie\n");

  /* etape 4 : plus qu'a attendre la demadne d'un client */
 
 
  int totalRecv = 0; // un compteur du nombre total d'octets recus d'un client
  
  /* le protocol d'echange avec un client pour recevoir un fichier est à définir. Ici seul un exemple de code pour l'écriture dans un fichier est founi*/
   
  char* filepath = "./reception/toto.txt"; // cette ligne n'est bien-sur qu'un exemple et doit être modifiée : le nom du fichier doit être reçu.
   
  // On ouvre le fichier dans lequel on va écrire
  FILE* file = fopen(filepath, "wb");
  if(file == NULL){
    perror("Serveur : erreur ouverture fichier: \n");
    exit(1);  
  }

  char * buffer ="ceci est un exemple de contenu a ecrire dans un fichier\n";
  size_t written = fwrite(buffer, sizeof(char), strlen(buffer)+1, file);
  if(written < strlen(buffer)+1){  // cette ligne est valide uniquement pour ce simple exemple
    perror("Serveur : Erreur a l'ecriture du fichier \n");
    fclose(file); 
  }

  printf("Serveur : ecriture dans fichier reussie. Vous pouvez vérifier la création du fichier et son contenu.\n");
  // fermeture du fichier
  fclose(file);
    
  printf("Serveur : c'est fini\n");
}








