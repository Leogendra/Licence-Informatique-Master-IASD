#ifndef __JOUEURS_ELAST_H__
#define  __JOUEURS_ELAST_H__

#include "joueur.h"

class JoueursElast {
 private:
  Joueur *joueurs;
  int nb_joueurs;

 public:
  JoueursElast(int nb_joueurs = 0, const Joueur &j = Joueur());
  JoueursElast(const JoueursElast &j);
  ~JoueursElast();
  JoueursElast &operator=(const JoueursElast &j);

  Joueur &GetJoueur(int pos) const;
  int NbJoueurs() const;
  void SetJoueur(int pos, Joueur &j);
};

#endif
