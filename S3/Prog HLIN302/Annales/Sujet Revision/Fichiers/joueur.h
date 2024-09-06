#ifndef __JOUEUR_H__
#define __JOUEUR_H__

#include <iostream>
#include <string>
#include "carte.h"
#include "listeCartes.h"

class Joueur {
 private:
  std::string pseudo;
  ListeDeCartes main;
  ListeDeCartes gains;
  int score;

 public:
  Joueur(const std::string &pseudo = "???");

  const std::string &GetPseudo() const;
  const ListeDeCartes &GetMain() const;
  const ListeDeCartes &GetGains() const;
  int GetScore() const;

  void SetPseudo(const std::string &pseudo);
  void SetScore(int score);

  void AjouteMain(const Carte &c);
  void AjouteGain(const Carte &c);

  void EffaceMain();
  void EffaceGains();

  Carte Joue();
};

std::ostream &operator<<(std::ostream &os, const Joueur &j);

#endif
