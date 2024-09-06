#include <stdlib.h>

// cette fonction simule des calculs d'une durée de sec secondes
void travail_hors_ou_dans_section_critique(int sec);

// envoi d'un message en TCP
int send_data(int socket, void *buffer, size_t length);

// la meme fonction, en ajoutant un compteur du nombre d'appels à send effectué pour l'envoi du message
int send_data_bis(int socket, void *buffer, size_t length, int * nbCallSend);

// reception d'un message en TCP
int recv_data(int socket, void *buffer, size_t length);
