#include <cstdlib>
#include <iostream>
#include <unistd.h>
#include <string>
#include "ABR.h"

using namespace std;

//------------------------------------------------------
// Inserer un noeud z dans l'arbre A
//------------------------------------------------------

noeud* ABR::inserer(int k) {
  noeud* n=this->racine;
  noeud* nv=new noeud(k);
  noeud* tmp=NULL;

  while (n!=NULL) {
    tmp = n;
    if(n->val>k) {n=n->filsG;}
    else {n=n->filsD;}
  }

  if(tmp==NULL) {racine=nv;}
  else if (k<tmp->val) {
    tmp->filsG=nv;
    nv->pere=tmp;
    }
  else {
    tmp->filsD=nv;
    nv->pere=tmp;
    }
  delete n;
  delete tmp;
  return nv;
}

//------------------------------------------------------
// Parcours infixe
//------------------------------------------------------

void ABR::parcoursInfixe(noeud* x) {
  if (x!=NULL) {
    parcoursInfixe(x->filsG);
    std::cout<<x->val<<", ";
    parcoursInfixe(x->filsD);
  }
}

//------------------------------------------------------
// Noeud de valeur minimale dans l'arbre
//------------------------------------------------------

noeud* ABR::minimum(noeud* x) {
  if (x==NULL) {return x;}
  while (x->filsG!=NULL) {x=x->filsG;}
  return x;
}

//------------------------------------------------------
// Recherche d'un noeud de valeur k
//------------------------------------------------------

noeud* ABR::rechercher(int k) {
  noeud* n = racine;
  while (n!=NULL && n->val!=k) {
    if (n->val<k) {n=n->filsD;}
    else {n=n->filsG;}
  }
  return n;
}

//------------------------------------------------------
// Recherche du successeur du noeud x
//------------------------------------------------------

noeud* ABR::successeur(noeud *x) {
  int k=x->val;
  noeud* n=NULL;
  //calcul du noeud de valeur maximale
  noeud* maxi=racine;
  while (maxi->filsD!=NULL) {maxi=maxi->filsD;}

//recherche un noeud de valeur supérieure au paramètre si ce derneir n'est pas de valeur max
  do {
    k++;
    n=rechercher(k);
  }
  while (n==NULL && k<=maxi->val);
  
  delete maxi;
  return n;
}

//------------------------------------------------------
// Suppression d'un noeud
//------------------------------------------------------

void ABR::supprimer(noeud* z) {
  if (z->filsG==NULL) {remplacer(z,z->filsD);}
  else if (z->filsD==NULL) {remplacer(z,z->filsG);}
  else {
    noeud* y=successeur(z);
    remplacer(y,y->filsD);
    y->filsD=z->filsD;
    z->filsD=NULL;
    y->filsG=z->filsG;
    z->filsG=NULL;
    if (y->filsD!=NULL) {y->filsD->pere=y;}
    if (y->filsG!=NULL) {y->filsG->pere=y;}
    remplacer(z,y);
  }
  delete z;
}


//------------------------------------------------------
// Rotations
//------------------------------------------------------

void ABR::rotationGauche(noeud* x) {
  if (x!=NULL) {
    noeud* y=x->filsD;
    if (y!=NULL) {
      y->pere=x->pere;
      x->pere=y;
      x->filsD=y->filsG;
      y->filsG=x;

      if (x->filsD!=NULL) {x->filsD->pere=x;}
      if (y->pere!=NULL) {
        if (y->pere->val < y->val) {y->pere->filsD=y;}
        else  {y->pere->filsG=y;}
          }
    }
  }
}

//même fonction que précedemment mais en inversant les côtés (et pas oublier le "inférieur" à changer aussi)
void ABR::rotationDroite(noeud* x) {
  if (x!=NULL) {
    noeud* y=x->filsG;
    if (y!=NULL) {
      y->pere=x->pere;
      x->pere=y;
      x->filsG=y->filsD;
      y->filsD=x;

      if (x->filsG!=NULL) {x->filsG->pere=x;}
      if (y->pere!=NULL) {
        if (y->pere->val > y->val) {y->pere->filsG=y;}
        else  {y->pere->filsD=y;}
          }
    }
  }
}
