#ifndef __CARTE_H__
#define __CARTE_H__

#include <iostream>

class Carte {
 public:
  enum Categorie {
    TREFLE,
    CARREAU,
    COEUR,
    PIQUE,
    ATOUT,
    NB_CATEGORIES
  };

 private:
  Categorie categorie;
  int valeur;

 public:

  Carte(int val = -1, Categorie c = NB_CATEGORIES);

  int GetValeur() const;
  Categorie GetCategorie() const;
  float GetScore() const;
  bool EstValide() const;

  void SetValeur(int v);
  void SetCategorie(Categorie c);

  bool operator<(const Carte &c) const;
  bool operator<=(const Carte &c) const;
  bool operator>(const Carte &c) const;
  bool operator>=(const Carte &c) const;
  bool operator==(const Carte &c) const;
  bool operator!=(const Carte &c) const;

};

std::ostream &operator<<(std::ostream &os, const Carte &c);
#endif
