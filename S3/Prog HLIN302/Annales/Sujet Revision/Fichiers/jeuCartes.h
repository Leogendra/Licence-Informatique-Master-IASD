#ifndef __JEU_CARTES_H__
#define __JEU_CARTES_H__

#include "carte.h"

class JeuDeCartes {
 private:
  Carte *cartes;
  int nb_cartes;

 public:
  JeuDeCartes(int nb = 0, bool trad = false);
  JeuDeCartes(const JeuDeCartes &j);
  ~JeuDeCartes();

  JeuDeCartes &operator=(const JeuDeCartes &j);

  bool EstTrie() const;
  bool EstValide() const;

  void Melanger();
  void Couper();

  Carte &GetCarte(int pos) const;
  void SetCarte(int pos, const Carte &c);
  int NbCartes() const;
};

#endif
