#include <stdio.h> 
#include <unistd.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netdb.h>
#include <stdlib.h>
#include<arpa/inet.h>
#include<string.h>

/* Rôle du client : envoyer une demande de connexion à un serveur,
   envoyer une chaîne de caractères à ce serveur (la chaîne de
   caractère est à saisir au clavier), recevoir un entier et comparer
   cet entier avec le nombre d'octets envoyés. L'idée est de mettre en
   place les bases qui vous permettront par la suite une mise en
   oeuvre correcte des communications en TCP et de vérifier les
   échanges effectués */

int main(int argc, char *argv[]) {

  /* je passe en paramètre l'adresse de la socket d'écoute du serveur
     (IP et numéro de port). Je teste donc le passage de parametres */

  if (argc != 3){
    printf("utilisation : %s ip_serveur port_serveur\n", argv[0]);
    exit(0);
  }

  /* Etape 1 : créer une socket */   
  int ds = socket(...);

  /* /!\ : Il est indispensable de tester les valeurs de retour de
     toutes les fonctions et agir en fonction des valeurs possibles.*/
  if (ds == -1){
    printf("Client : pb creation socket\n");
    exit(1); // je choisis ici d'arrêter le programme car le reste
	     // dépendent de la réussite de la création de la socket.
  }
  
  /* J'alimente le programme avec des traces d'exécution pour observer ce
     qui se produit à l'exécution et pour mieux localiser les erreurs. */
  printf("Client: creation de la socket : ok\n");
  
  // Je peux déjà tester l'exécution de cette étape avant de passer à la suite.
  
  /* Etape 2 : designer la socket du serveur : avoir une structure qui
     contient l'adresse de cette socket (IP + numéro de port. */
  struct sockaddr_in adrServ;
  adrServ.sin_addr.s_addr = ... ;
  adrServ.sin_family = ....;
  adrServ.sin_port = ...;
    
  int lgAdr = sizeof(struct sockaddr_in);
    
  /* Etape 3 : envoyer une demande de connexion au serveur.*/
  int conn = connect(....);
  // je traite les valeurs de retour
  if (conn <0){
    perror ("Client: pb au connect :");
    close (ds); // je libère les ressources avant de terminer.
    exit (1); // je choisi de quitter le programme : la suite dépend
	      // de la réussite de l'envoi de la demande de connexion.
  }

  // je continue à alimenter le programme avec traces d'exécution
  printf("Client : demande de connexion reussie \n");

  // Je peux tester l'exécution de cette étape avant de passer à la suite.
  
  /* Etape 4 : envoyer un message au serveur. Ce message est une chaîne de caractères saisie au clavier. Vous pouvez utiliser une autre fonction pour la saisie. */

  printf("saisir un message à envoyer (moins de 200 caracteres) \n");
  char m[202]; 
  fgets(m, sizeof(m), stdin); // copie dans m la chaîne saisie que
			      // clavier (incluant les esaces et le
			      // saut de ligne à la fin).
  m[strlen(m)-1]  = '\0'; // je retire le saut de ligne                                      

  /* Pour cet exercice, demander à envoyer la chaîne saisie avec un
     seul appel à send(..). /!\ Envoyer uniquement les données
     utiles. Par exemple, si je saisis la chaîne "bonjour Toto", je n'ai
     pas à envoyer le tableau de 202 octets, mais seulement la chaîne
     saisie (information utile)(refléchir à la raison). Remarque : le
     serveur s'attends à recevoir une chaine de caractères y compris le
     caractère de fin */
  
  int snd = send(...);
  /* Traiter TOUTES les valeurs de retour (voir le cours ou la documentation). */
 ...



  /* Afficher le nombre d'octets EFFECTIVEMENT déposés dans le buffer
     d'envoi de la socket cliente. : /!\ Faire la différence entre le
     nombre d'octet qu'on demande à déposer / envoyer et le nombre
     d'octets qu'on a effectivement déposés.*/
  
  printf("Client : j'ai déposé %d octets \n", ...);


  // Je peux tester l'exécution de cette étape avant de passer à la suite. 
  
  /* Etape 5 : reception d'une réponse du serveur. La réponse est un
     entier représentant le nombre d'octets du message que le serveur
     a effectivement reçu à l'étape précédente. Pour cet exercice,
     faire un seul appel de la fonction recv(..). */

  /* /!\j'alimente toujours le programme avec traces d'exécution. A noter
     qu'il est aussi important d'afficher un message avant une action
     qui peut être bloquante. Vous saurez ainsi à quelle étape de
     l'exécution le blocage se produit. */

  printf("Client : envoi fait, j'attends la reponse du serveur \n");
  
  int reponse;
  int rcv = recv (...) ;
  /* Traiter TOUTES les valeurs de retour (voir le cours ou la documentation). */
 

  /* Etape 6 : je compare le nombre d'octets déposés (envoyés) avec
     la valeur reçue. L'objectif est d'avoir la même valeur. */

  printf("Client : j'ai envoyé %d octets et le serveur me répond qu'il a reçu : %d octets \n", ..., ...) ;


  /* Etape 7 : je termine proprement. */

  close (ds);
  printf("Client : je termine\n");
}
