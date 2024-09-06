#include "joueur.h"

Joueur NULL_JOUEUR;
extern Carte NULL_CARTE;

using namespace std;

Joueur::Joueur(const std::string &pseudo) {
  // A Faire
}

const string &Joueur::GetPseudo() const {
  return pseudo;
}

const ListeDeCartes &Joueur::GetMain() const {
  return main;
}

const ListeDeCartes &Joueur::GetGains() const {
  return gains;
}

int Joueur::GetScore() const {
  return score;
}


void Joueur::SetPseudo(const std::string &pseudo) {
  // A Faire
}

void Joueur::SetScore(int score) {
  // A Faire
}

void Joueur::AjouteMain(const Carte &c) {
  main.AjouteFin(c);
}

void Joueur::AjouteGain(const Carte &c) {
  gains.AjouteFin(c);
}


void Joueur::EffaceMain() {
  main.Vider();
}

void Joueur::EffaceGains() {
  gains.Vider();
}

Carte Joueur::Joue() {
  if (main.EstVide()) {
    cerr << "Le joueur " << pseudo << " n'a plus de carte et donc ne plus jouer." << endl;
    return NULL_CARTE;
  }
  Carte c = main.Valeur(main.Premier());
  main.EnleveDebut();
  return c;
}

ostream &operator<<(ostream &os, const Joueur &j) {
  os << "=== Joueur " << j.GetPseudo() <<  " === ";
  os << "Score : " << j.GetScore() << endl;
  os << "Main : " << j.GetMain() << endl;
  os << "Gains en cours : " << j.GetGains() << endl;
  os << "===" << endl;
  return os;
}


