#include "joueursElast.h"

extern Joueur NULL_JOUEUR;
using std::cerr;
using std::endl;

JoueursElast::JoueursElast(int nb_joueurs, const Joueur &j) {
  // A Faire
}

JoueursElast::JoueursElast(const JoueursElast &j) {
  // A Faire
}

JoueursElast::~JoueursElast() {
  // A Faire
}

JoueursElast &JoueursElast::operator=(const JoueursElast &j) {
  // A Faire
}

Joueur &JoueursElast::GetJoueur(int pos) const {
  if ((pos < 0) || (pos >= nb_joueurs)) {
    cerr << "Impossible de renvoyer le joueur " << pos
	 << " (il doit etre compris entre 0 et "<< nb_joueurs << ")" << endl;
    return NULL_JOUEUR;
  }
  return joueurs[pos];
}

int JoueursElast::NbJoueurs() const {
  return nb_joueurs;
}

void JoueursElast::SetJoueur(int pos, Joueur &j) {
  if ((pos < 0) || (pos >= nb_joueurs)) {
    cerr << "Impossible d'assigner le joueur " << pos
	 << " (il doit etre compris entre 0 et "<< nb_joueurs << ")" << endl;
    return;
  }
  joueurs[pos] = j;
}

