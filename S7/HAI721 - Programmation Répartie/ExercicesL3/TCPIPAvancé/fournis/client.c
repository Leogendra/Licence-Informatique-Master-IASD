

....



#define MAX_BUFFER_SIZE 16000  // taille du buffer temporaire pour la lecture de fichier. Vous pouvez définir une autre valeur.





int main(int argc, char *argv[]) {

  if (argc != 4){
    printf("utilisation : client ip_serveur port_serveur nom_fichier\n");
    exit(0);
  }


  .....


    // envoyer le nom de fichier (réfléchir aux données à envoyer pour que le nom soit corectement reçu)

  int name_size = strlen(argv[3]) + 1;

   ...
   
    // je construis le nom complet (chemin) du fichier que je dois lire
  char* filepath = malloc(strlen(argv[3]) + 16); // ./emission/+nom fichier +\0
  filepath[0] = '\0';
  strcat(filepath, "./emission/");
  strcat(filepath, argv[3]);

  printf("Client: je vais envoyer %s\n", filepath);

  // je récupère la taille du fichier. Pourquoi ?
  struct stat attributes;
  if(stat(filepath, &attributes) == -1){
    perror("Client : erreur stat");
    free(filepath);
    close(ds);
    exit(1);
  }

  int file_size = attributes.st_size;  // c'est bon, j'ai obtenu la taille du fichier. Refléchir ce que je dois en fair .


  ..... 

  // pour envoyer le contenu, je dois lire le fichier :
    FILE* file = fopen(filepath, "rb"); // ouverture en lecture
  if(file == NULL){
    perror("Client : erreur ouverture fichier \n");
    free(filepath);
    close(ds);
    exit(1);   
  }
  free(filepath); // je n'ai plus besoin de ce tableau dans la suite.

  // je fait une lecture par bloc.
  // je continue à lire tant que je n'ai pas lus le fichier en entier.
  int nbRead = 0 //
  while(nbRead < file_size){
    
    char buffer[MAX_BUFFER_SIZE];
    size_t read = fread(buffer, sizeof(char), MAX_BUFFER_SIZE, file);
    // je viens de tenter de lire un bloc d'au maximum MAX_BUFFER_SIZE octets
    if(read == 0){
      if(ferror(file) != 0){
	perror("Client : erreur lors de la lecture du fichier \n");
	fclose(file);
         ...
	exit(1);
      } // fin du fichier
      break; // plus rien à lire
    }
    // ici j'ai un bloc de read octets lus et stoqués dans buffer. Que faire de ce bloc d'octets ?
  
    ...
    
    nbRead += ...;
  }

  // fermeture du fichier
  int resc = fclose(file);
  ...
 
  printf("Client : j'ai envoye au total : %d octets,  envoyes en %d appels a send \n", ..., ...);  
  close (ds);
  printf("Client : je termine\n");
}
