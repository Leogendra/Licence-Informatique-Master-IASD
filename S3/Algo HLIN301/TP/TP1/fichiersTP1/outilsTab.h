#ifndef OUTILSTAB_H_INCLUDED
#define OUTILSTAB_H_INCLUDED

struct triplet {
    int deb, fin, somMax;} ;

int* genTab(int t);
//    Renvoie un tableau de taille t, dont les éléments sont des entiers aléatoires compris entre -100 et 100

int* genTab100(int t);
//    Renvoie un tableau de taille t, dont les éléments sont des entiers aléatoires compris entre 0 et 100

void afficheTab(int* T, int t);
//    Affiche les éléments de T, tableau d'entiers de taille t


void fichierTemps(const char* nomFic, int tMaxTab, int pasTaille, int (*fssTabSomMax)(int*, int));
//    Données nomFic une chaîne de caractères, tMaxTab et pasTaille 2 entiers positifs pasTaille < tMaxTab
//            fssTabSomMax nom d'une fonction dont les données sont 1 tableau d'entiers et la taille de ce tableau et renvoyant 1 entier
//    Resultat : crée un fichier de nom nomfic et pour chaque taille comprise entre pasTaille et tMaxTab (avec un pas de pasTaille),
//               génère un tableau de cette taille
//               execute la fonction ssTabSomMax sur ce tableau
//               ajoute au fichier de nom nomfic la taille du tableau et le temps d'execution de ssTabSomMax


int ssTabSomMax1(int* Tab, int n);
/*
    Données : Tab un tableau d'entiers de taille n
    Resultat : renvoie la somme max des sous-tableaux de tab, algo de complexite O(n^3)
*/

int ssTabSomMax2(int*, int);

/*
    Données : Tab un tableau d'entiers de taille n
    Resultat : renvoie la somme max des sous-tableaux de tab, algo de complexite O(n^2)
*/

int ssTabSomMax3(int* Tab, int n);
/*
    Données : Tab un tableau d'entiers de taille n
    Resultat : renvoie la somme max des sous-tableaux de tab, algo de complexite O(n log n)
*/

int ssTabSomMax4(int* Tab, int n);
/*
    Données : Tab un tableau d'entiers de taille n
    Resultat : renvoie la somme max des sous-tableaux de tab, algo de complexite O(n)
*/

struct triplet indSsTabSomMax(int* Tab,int n);
/*
    Données : Tab un tableau d'entiers de taille n
    Resultat : renvoie une structure contenant
                    la somme max des sous-tableaux de tab,
                    l'indice de début d'un sous-tableau de somme max
                    l'indice de fin d'un sous-tableau de somme max
                algo de complexite O(n)
*/


void rangerPairs(int* Tab,int n);
/*
    Données : Tab un tableau d'entiers de taille n
    Resultat : modifie le tableau Tab de sorte que tous les entiers impairs soient placés après  les entiers pairs
               algo de complexite O(n)
*/



#endif /* OUTILSTAB_H_INCLUDED */
