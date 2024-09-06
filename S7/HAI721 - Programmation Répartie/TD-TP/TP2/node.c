#include <stdio.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <pthread.h>
#include <netdb.h>
#include <stdlib.h>
#include<arpa/inet.h>
#include<string.h>
#include "common.h"
#include "node_interconnexions.h"



int main(int argc, char *argv[]) { 
  
  // cet appel cronstruit le réseau complet
  int * lesAutresNoeuds = configuration(argc, argv);
  
  
  // algo exclusion mutuelle :
  int indice = atoi(argv[3]);
  int etat = 0;
  int entreeEnSectionCritique = 0;
  int reponse = 0;
  int demandes = 0;
  
  while(1){
    // travailler hors section critique
    int tempsHorsSectionCritique = rand()%10;
    sleep(tempsHorsSectionCritique);
    // demander l'entrée en section critique
    demandes++;
    entreeEnSectionCritique = 1;
    etat = 1;
    printf("[%d] je demande l'entrée en section critique\n", indice);
    // envoyer les demandes aux autres nodes
    for(int i = 0; i < nbNodes; i++){
      if (lesAutresNoeuds[i] != -1){
        send_message(lesAutresNoeuds[i], demande_sc);
      }
    }
    // attendre la réponse de tous les autres nodes
    reponse = 0;
    while(reponse < nbNodes-1){
      // recevoir la réponse d'un node
      int reponse = receive_message();
      if (reponse == 1){
        etat = 0;
        entreeEnSectionCritique = 0;
        printf("[%d] refusée\n", indice);
        // envoyer le refus aux autres nodes
        for(int i = 0; i < nbNodes; i++){
          if (lesAutresNoeuds[i] != -1){
            send_message(lesAutresNoeuds[i], refus_sc);
          }
        }
        break;
      }
      else if (reponse == 2){
        reponse++;
      }
    }
    
    // entrer en section critique
    if (entreeEnSectionCritique == 1){
      printf("[%d] entrée en section critique\n", indice);
    }
    // travailler en section critique
    int tempsEnSectionCritique = rand()%10;
    sleep(tempsEnSectionCritique);
    
    // sortir de la section critique
    printf("[%d] sortie de la section critique\n", indice);
    // envoyer les messages de libération aux autres nodes
    for(int i = 0; i < nbNodes; i++){
      if (lesAutresNoeuds[i] != -1){
        send_message(lesAutresNoeuds[i], libere_sc);
      }
    }
   }
  
  
  
  return 0;
}