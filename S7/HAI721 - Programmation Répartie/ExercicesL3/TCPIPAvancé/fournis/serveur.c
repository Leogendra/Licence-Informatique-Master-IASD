...


#define MAX_BUFFER_SIZE 16000 // taille du buffer qui me permet de récupérer le contenu du fichier à recevoir bloc par bloc. Vous pouvez changer cette valeur.


...

int main(int argc, char *argv[])
{
  
 ....
  
 /* boucle pour le traitement itératif des clients */
  while(1){

  
    // je doit recevoir des données me permettant d'obtenur le nom d'un fichier
    ...
    
    char* file_name = malloc(...);

    ...
    printf("Serveur, je vais recevoir le fichier %s\n", file_name);

    // je construis le chemin vers le fichier à créer.
    char* filepath = malloc(name_size + 16); // ./reception/+nom fichier
    filepath[0] = '\0';
    strcat(filepath, "./reception/");
    strcat(filepath, file_name);
    free(file_name);

  // j'ouvre le fichier dans lequel je vais écrire
    FILE* file = fopen(filepath, "wb");
    if(file == NULL){  // servez vous de cet exemple pour le traitement des erreurs. 
      perror("Serveur : erreur ouverture fichier: \n");
      free(filepath);
      close (dsCv);
      continue; // passer au client suivant 
    }
    
    free(filepath); // je n'en ia plus besoin.

    //reception des données me permettant de recevoir correctement le contenu du fichier.
    int file_size;
  
    ...
      
    int contentReceived = 0; // Compte le nombre d'octets du fichier reçu

    // je reçois le contenu progressivement 
    while(contentReceived < file_size){
      
      char buffer[MAX_BUFFER_SIZE];
      rcv = recv(dsCv, buffer, MAX_BUFFER_SIZE, 0);  // /!\ ici appel
						     // direct de recv
						     // car recvTCP
						     // n'est pas
						     // adapté. Pourquoi ?

      ....
	
      // si pas d'erreurs, j'ai reçu rcv octets. Je dois les écire dans le fichier.
      size_t written = fwrite(buffer, sizeof(char), rcv, file);
      if(written < rcv){
	perror("Serveur : Erreur a l'ecriture du fichier \n");
	....
	  break; // je sors de la boucle d'écrture/réception.
      }

      contentReceived += ...;
    }

    // fermeture du fichier à la fin de son écriture ou si erreur s'est produite.
    
    fclose(file);
    printf("Serveur : fin du dialogue avec le client, nombre total d'octets recus : %d,  recus en %d appels a recv \n", ...);
    close (dsCv);
    //je passe au client suivant.
  }
  
  close (ds); // atteignable si on sort de la boucle infinie.
  printf("Serveur : je termine\n");
 }








