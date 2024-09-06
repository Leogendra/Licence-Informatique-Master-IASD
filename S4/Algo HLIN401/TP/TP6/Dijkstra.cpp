#include <iostream>
#include <cmath>
#include "Dijkstra.h"

using namespace std;

coord* sommetsAleatoires(int n, int l, int h) {
	coord *sommets = new coord[n];
	for (int i = 0; i < n; i++) {
		sommets[i] = {(rand()%(l-19)) + 10, (rand()%(h-19)) + 10};
	}
	return sommets;
}


float distance(coord* sommets, int i, int j) {
  coord s1 = sommets[i];
  coord s2 = sommets[j];
  float val = sqrt((s2.x-s1.x)*(s2.x-s1.x)+(s2.y-s1.y)*(s2.y-s1.y));
  return val;
}



listeAdj* graphe(int n, coord* sommets, float dmax) {
  listeAdj* G = new listeAdj[n];
  for (int i=0; i<n; i++) {G[i] = NULL;}

  for (int i=0; i<n; i++) {
    for (int j=0; j<n; j++) {
      if (i!=j) {
        float d = distance(sommets,i,j);
        if (d<=dmax) {G[i] = new Voisin(j, d, G[i]);}
      }
    }
  }
  return G;
}



void dijkstra(int n, listeAdj* G, int s, float*& D, int*& P) {
  File* F = new File(n);
  D = new float[n];
  P = new int[n];
  for (int i=0; i<n; i++) {
    D[i] = INFINITY;
    P[i] = -1;
    }
  F->changer_priorite(s, 0);
  D[s]=0;
  while (!(F->est_vide())) {
    int u = F->extraire_min();
    Voisin* v = G[u];
    while (v!=NULL) {
      if ((D[u]+v->poids)<D[v->sommet]) {
        D[v->sommet]=D[u]+v->poids;
        P[v->sommet]=u;
        F->changer_priorite(v->sommet, D[v->sommet]);
      }
      v=v->suivant;
    }
  }
}


listeAdj chemin(int n, listeAdj* G, int* P, int v, int s) {
  listeAdj C = new Voisin(s, 0, NULL);
  while (s != v) {
    s = P[s];
    C = new Voisin(s, 0, C);
  }
  return C;
}



listeAdj* arbre(int n, listeAdj* G, int* P, int s) {
  listeAdj* T = new listeAdj[n];
  for (int i=0; i<n; i++) {T[i]=NULL;}
  for (int u=0; u<n; u++) {
    if (u!=s) {
      T[P[u]] = new Voisin(u, 0, T[P[u]]);
      T[u] = new Voisin(P[u], 0, T[u]);
    }
  }
  return T;
}



void a_etoile(int n, listeAdj* G, coord* sommets, int s, int t, float*& D, int*& P) {
  File* F = new File(n);
  D = new float[n];
  P = new int[n];
  for (int i=0; i<n; i++) {
    D[i] = INFINITY;
    P[i] = -1;
  }
  F->changer_priorite(s, 0);
  D[s]=0.0;
  bool stop=true;
  while (!(F->est_vide()) && stop) {
    int u = F->extraire_min();
    Voisin* v = G[u];
    while (v!=NULL) {
    if (v->sommet==t) {stop=false;}
      if ((D[u]+v->poids)<D[v->sommet]) {
        D[v->sommet]=D[u]+v->poids;
        P[v->sommet]=u;
        float dist = distance(sommets, v->sommet, t);
        F->changer_priorite(v->sommet, D[v->sommet]+dist);
      }
      v=v->suivant;
    }
  }
}