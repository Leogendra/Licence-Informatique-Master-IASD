#include "carte.h"

std::string CatLabel(Carte::Categorie c, bool article = true) {
  std::string res;
  switch (c) {
  case Carte::ATOUT:
    res = (article ? "d'" : "" );
    break;
  case Carte::TREFLE:
  case Carte::CARREAU:
  case Carte::COEUR:
  case Carte::PIQUE:
    res = (article ? "de " : "" );
    break;
  default:
    break;
  }
  switch (c) {
  case Carte::ATOUT:
    res += "atout";
    break;
  case Carte::TREFLE:
    res += "trefle";
    break;
  case Carte::CARREAU:
    res += "carreau";
    break;
  case Carte::COEUR:
    res += "coeur";
    break;
  case Carte::PIQUE:
    res += "pique";
    break;
  default:
    res += "[categorie non definie]";
    break;
  }
  return res;
}

Carte::Carte(int val, Carte::Categorie c) {
  // A faire
}

int Carte::GetValeur() const {
  return valeur;
}

Carte::Categorie Carte::GetCategorie() const {
  return categorie;
}

float Carte::GetScore() const {
  // A faire
}

void Carte::SetValeur(int v) {
  // A faire
}

void Carte::SetCategorie(Carte::Categorie c) {
  // A faire
}

bool Carte::operator<(const Carte &c) const {
  // A faire
}

bool Carte::operator<=(const Carte &c) const {
  return ((*this == c) || (*this < c));
}

bool Carte::operator>(const Carte &c) const {
  return (c < *this);
}

bool Carte::operator>=(const Carte &c) const {
  return (c <= *this);
}

bool Carte::operator==(const Carte &c) const {
  return ((categorie == c.categorie) && (valeur == c.valeur));
}

bool Carte::operator!=(const Carte &c) const {
  return !(*this == c);
}

bool Carte::EstValide() const {
  // A faire
}

std::ostream &operator<<(std::ostream &os, const Carte &c) {
  if (c.EstValide()) {
    if (c.GetCategorie() == Carte::ATOUT) {
      if (!c.GetValeur()) {
	os << "Excuse";
      } else {
	os << c.GetValeur() << " d'atout";
      }
    } else {
      if (c.GetValeur() == 1) {
	os << "As";
      } else {
	if (c.GetValeur() == 11) {
	  os << "Valet";
	} else {
	  if (c.GetValeur() == 12) {
	    os << "Cavalier";
	  } else {
	    if (c.GetValeur() == 13) {
	      os << "Dame";
	    } else {
	      if (c.GetValeur() == 14) {
		os << "Roi";
	      } else {
		os << c.GetValeur();
	      }
	    }
	  }
	}
      }
      os << " " << CatLabel(c.GetCategorie());
    }
  } else {
    os << "?????";
  }
  return os;
}

Carte NULL_CARTE;
