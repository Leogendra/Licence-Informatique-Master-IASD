#include "jeuCartes.h"
#include <cstdlib>

using std::cerr;
using std::endl;

extern Carte NULL_CARTE;

JeuDeCartes::JeuDeCartes(int nb, bool trad) {
  // A Faire
}

JeuDeCartes::JeuDeCartes(const JeuDeCartes &j) {
  // A Faire
}


JeuDeCartes &JeuDeCartes::operator=(const JeuDeCartes &j) {
  // A Faire
}

JeuDeCartes::~JeuDeCartes() {
  // A Faire
}

bool JeuDeCartes::EstTrie() const {
  // A Faire
}

bool JeuDeCartes::EstValide() const {
  // A Faire
}


void JeuDeCartes::Melanger() {
  // A Faire
}

void JeuDeCartes::Couper() {
  // A Faire
}

Carte &JeuDeCartes::GetCarte(int pos) const {
  if ((pos < 0) || (pos >= nb_cartes)) {
    cerr << "Impossible de renvoyer la carte a la position " << pos
	 << " (pos doit etre compris entre 0 et "<< nb_cartes << ")" << endl;
    return NULL_CARTE;
  }
  return cartes[pos];
}

void JeuDeCartes::SetCarte(int pos, const Carte &c) {
  if ((pos < 0) || (pos >= nb_cartes)) {
    cerr << "Impossible de changer la carte a la position " << pos
	 << " (pos doit etre compris entre 0 et "<< nb_cartes << ")" << endl;
    return;
  }
  cartes[pos] = c;
}

int JeuDeCartes::NbCartes() const {
  return nb_cartes;
}
