#ifndef __LISTE_CARTES_H__
#define __LISTE_CARTES_H__

#include <iostream>
#include "carte.h"

class ListeDeCartes {
  struct CarteCell {
    Carte valeur;
    struct CarteCell * suivant; 
    struct CarteCell * precedent; 
  };
  struct CarteCell* tete;
  struct CarteCell* queue;

 public:

  typedef struct CarteCell* Place;

  ListeDeCartes();
  ListeDeCartes(const ListeDeCartes &l);
  ListeDeCartes &operator=(const ListeDeCartes &l);
  ~ListeDeCartes();

  void AjouteFin(const Carte &c);
  void AjouteDebut(const Carte &c);
  void AjouteAvant(Place p, const Carte &c);
  void AjouteApres(Place p, const Carte &c);
  Place Premier() const;
  Place Dernier() const;
  Place Suivant(Place p) const;
  Place Precedent(Place p) const;
  Carte &Valeur(Place p) const;
  bool IsNull(Place p) const;
  bool EstVide() const;

  void EnleveFin();
  void EnleveDebut();
  void Enleve(Place p);
  void Vider();

};

std::ostream &operator<<(std::ostream &os, const ListeDeCartes &l);
#endif
