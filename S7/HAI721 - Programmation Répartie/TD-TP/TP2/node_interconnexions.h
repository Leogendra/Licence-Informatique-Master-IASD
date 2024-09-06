int taille_reseau;

// fonction qui met un noeud en lien avec ses voisins. Elle renvoi un tableau de N descripteurs de sockets correspondantes a celles connectées aux autres processus. N est la taille du réseau. Pour un processus Pi, son descrpiteur se trouve à l'indice i-1 et si i correspond à l'indice du processus actuel, la valeur à l'indice i-1 est égale à 0 (jamais utilisé) 
int * configuration(int argc, char *argv[]);

// ce code est juste pour simuler des calculs (elle n'est pas à faire par les étudiants, je donnerai le binaire aux étudiants.
void travail_hors_ou_dans_section_critique(int sec);
