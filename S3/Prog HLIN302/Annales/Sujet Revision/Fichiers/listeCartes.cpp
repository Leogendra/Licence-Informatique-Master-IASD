#include "listeCartes.h"
using namespace std;

extern Carte NULL_CARTE;

ListeDeCartes::ListeDeCartes():tete(NULL), queue(NULL) {
}

ListeDeCartes::ListeDeCartes(const ListeDeCartes &l):tete(NULL), queue(NULL) {
  Place p = l.Premier();
  while (!l.IsNull(p)) {
    AjouteFin(l.Valeur(p));
    p = l.Suivant(p);
  }
}

ListeDeCartes &ListeDeCartes::operator=(const ListeDeCartes &l) {
  if (this != &l) {
    Vider();
    Place p = l.Premier();
    while (!l.IsNull(p)) {
      AjouteFin(l.Valeur(p));
      p = l.Suivant(p);
    }
  }
  return *this;
}

ListeDeCartes::~ListeDeCartes() {
  Vider();
}

void ListeDeCartes::AjouteFin(const Carte &c) {
  AjouteApres(queue, c);
}

void ListeDeCartes::AjouteDebut(const Carte &c) {
  AjouteAvant(tete, c);
}

void ListeDeCartes::AjouteAvant(ListeDeCartes::Place p, const Carte &c) {
  CarteCell *elt = new CarteCell;
  elt->valeur = c;
  if (p) {
    elt->suivant = p;
    elt->precedent = p->precedent;
    p->precedent = elt;
    if (elt->precedent) {
      elt->precedent->suivant = elt;
    } else {
      tete = elt;
    }
  } else {
    if (!tete) {
      elt->suivant = NULL;
      elt->precedent = NULL;
      tete = queue = elt;
    } else {
      cerr << "Avertissement: La place p est nulle mais la liste n'est pas vide."
	   << "               La carte sera inseree au debut de la liste"
	   << endl;
      elt->suivant = tete;
      tete->precedent = elt;
      elt->precedent = NULL;
      tete = elt;
    }
  }
}

void ListeDeCartes::AjouteApres(ListeDeCartes::Place p, const Carte &c) {
  CarteCell *elt = new CarteCell;
  elt->valeur = c;
  if (p) {
    elt->suivant = p->suivant;
    elt->precedent = p;
    p->suivant = elt;
    if (elt->suivant) {
      elt->suivant->precedent = elt;
    } else {
      queue = elt;
    }
  } else {
    if (!queue) {
      elt->suivant = NULL;
      elt->precedent = NULL;
      tete = queue = elt;
    } else {
      cerr << "Avertissement: La place p est nulle mais la liste n'est pas vide."
	   << "               La carte sera inseree a la fin de la liste"
	   << endl;
      elt->precedent = queue;
      queue->suivant = elt;
      elt->suivant = NULL;
      queue = elt;
    }
  }
}

ListeDeCartes::Place ListeDeCartes::Premier() const {
  return tete;
}

ListeDeCartes::Place ListeDeCartes::Dernier() const {
  return queue;
}

ListeDeCartes::Place ListeDeCartes::Suivant(ListeDeCartes::Place p) const {
  if (p) {
    return p->suivant;
  } else {
    cerr << "Avertissement: La place p n'existe pas et n'a donc pas de suivant."
	 << endl;
    return NULL;
  }
}

ListeDeCartes::Place ListeDeCartes::Precedent(ListeDeCartes::Place p) const {
  if (p) {
    return p->precedent;
  } else {
    cerr << "Avertissement: La place p n'existe pas et n'a donc pas de precedent."
	 << endl;
    return NULL;
  }
}

Carte &ListeDeCartes::Valeur(ListeDeCartes::Place p) const {
  if (p) {
    return p->valeur;
  } else {
    cerr << "Avertissement: La place p n'existe pas et n'a donc pas de valeur."
	 << endl;
    return NULL_CARTE;
  }
}

bool ListeDeCartes::IsNull(ListeDeCartes::Place p) const {
  return !p;
}

bool ListeDeCartes::EstVide() const {
  return (tete == NULL);
}

void ListeDeCartes::EnleveFin() {
  Enleve(queue);
}
void ListeDeCartes::EnleveDebut() {
  Enleve(tete);
}

void ListeDeCartes::Enleve(ListeDeCartes::Place p) {
  if (!p) {
    cerr << "Avertissement: La place p est nulle. Rien ne sera enleve."
	 << endl;
    return;
  }
  if (p == tete) {
    if (p == queue) {
      queue = tete = NULL;
    } else {
      tete = tete->suivant;
      tete->precedent = NULL;
    }
  } else {
    if (p == queue) {
      queue = queue->precedent;
      queue->suivant = NULL;
    } else {
      p->precedent->suivant = p->suivant;
      p->suivant->precedent = p->precedent;
    }
  }
  delete p;
}

void ListeDeCartes::Vider() {
  while (tete) {
    EnleveDebut();
  }
}

std::ostream &operator<<(std::ostream &os, const ListeDeCartes &l) {
  ListeDeCartes::Place p = l.Premier();
  os << "[";
  while (!l.IsNull(p)) {
    os << l.Valeur(p);
    p = l.Suivant(p);
    if (!l.IsNull(p)) {
      os << ", ";
    }
  }
  os << "]";
  return os;
}
