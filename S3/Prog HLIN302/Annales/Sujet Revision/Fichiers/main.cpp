#include <iostream>
#include <cstdlib>
#include "carte.h"
#include "jeuCartes.h"
#include "listeCartes.h"
#include "joueur.h"
#include "joueursElast.h"

using namespace std;

int main(int argc, char** argv) {
  
  //JeuDeCartes jeu(32, true);
  //JeuDeCartes jeu(54, true);
  JeuDeCartes jeu(78, true);
  const int max_tours = 50;
  const int max_points = 1000;

  // Initialisation du generateur de nombre aleatoire
  srand(0); // Toujours la même suite de nombre aleatoires (pratique pour debuguer)
  // srand(time(NULL)); // Initialisation en fontion du temps systeme (change donc toutes les secondes)

  if (!jeu.EstValide()) { // le jeu contient des cartes incoherentes
    cerr << "Erreur : le jeu contient des cartes non definies." << endl;
    return 1;
  }

  int val;
  do {
    cout << "Combien de joueurs sont attendus (entre 2 et " << jeu.NbCartes() << ") ? ";
    cin >> val;
  } while ((val < 2) || (val > jeu.NbCartes()));

  JoueursElast joueurs(val);

  for (int i = 0; i < joueurs.NbJoueurs(); i++) {
    string pseudo;
    cout << "Saisir un pseudo pour le joueur " << i << " : ";
    cin >> pseudo;
    joueurs.GetJoueur(i).SetPseudo(pseudo);
  }

  val = 0;
  int dernier_gagnant;
  int score_max;
  int score_max_cpt;
  do {

    // Melanger les cartes (cela ne se fait pas au tarot, sauf si le jeu est trie)
    if (jeu.EstTrie()) { jeu.Melanger(); }

    // Couper les cartes
    jeu.Couper();

    // Distribuer equitablement (en nombre) les cartes aux joueurs
    int nb_cartes_par_joueur = jeu.NbCartes() / joueurs.NbJoueurs();
    for (int i = 0; i < joueurs.NbJoueurs(); i++) {
      joueurs.GetJoueur(i).EffaceMain();
      joueurs.GetJoueur(i).EffaceGains();
      for (int j = 0; j < nb_cartes_par_joueur; j++) {
        joueurs.GetJoueur(i).AjouteMain(jeu.GetCarte(i*nb_cartes_par_joueur+j));
      }
    }

    // Tant que les joueurs ont encore des cartes
    bool fini = false;
    dernier_gagnant = val % joueurs.NbJoueurs();
    while (!fini) {
      // Chaque joueur joue une carte en commencant par le gagnant du precedent tour
      int gagnant_courant, joueur_courant = gagnant_courant = dernier_gagnant;
      ListeDeCartes lc;
      Carte meilleur_carte;
      do {
        Carte c = joueurs.GetJoueur(joueur_courant).Joue();
        if (joueur_courant == dernier_gagnant) { // Premier joueur du tour
          meilleur_carte = c;
        } else {
          if (c > meilleur_carte) {
            gagnant_courant = joueur_courant;
            meilleur_carte = c;
          }
        }
        cout << "Le joueur " << joueurs.GetJoueur(joueur_courant).GetPseudo()
	     << " joue la carte " << c
	     << endl;
        lc.AjouteFin(c);
        joueur_courant++;
        joueur_courant %= joueurs.NbJoueurs();
      } while (joueur_courant != dernier_gagnant);

      dernier_gagnant = gagnant_courant;
      cout << "Le joueur " << joueurs.GetJoueur(dernier_gagnant).GetPseudo() << " gagne." << endl;
      // Le gagnant du tour place les cartes jouees dans son gain;
      ListeDeCartes::Place p = lc.Premier();
      while (!lc.IsNull(p)) {
        joueurs.GetJoueur(dernier_gagnant).AjouteGain(lc.Valeur(p));
        p = lc.Suivant(p);
      }
      fini = joueurs.GetJoueur(dernier_gagnant).GetMain().EstVide();
    }

    // Compter les points de chaque joueur et arrondir au multiple de 5 le plus proche
    score_max = 0;
    score_max_cpt = 0;
    int pos = 0;
    cout << "Tour " << ++val << "/" << max_tours << " : " << endl;
    for (int i = 0; i < joueurs.NbJoueurs(); i++) {
      const ListeDeCartes &gain = joueurs.GetJoueur(i).GetGains();
      ListeDeCartes::Place p = gain.Premier();
      float score = 0; 
      while (!gain.IsNull(p)) {
        score += gain.Valeur(p).GetScore();
        // Rassembler les cartes des gains de chaque joueur et du talon.
        jeu.SetCarte(pos++, gain.Valeur(p));
        p = gain.Suivant(p);
      }
      joueurs.GetJoueur(i).EffaceGains();
      score = ((((int) score) / 5) + (((int) score) % 5 > 2)) * 5;
      joueurs.GetJoueur(i).SetScore(joueurs.GetJoueur(i).GetScore()+(int) score);
      cout << "- " << joueurs.GetJoueur(i).GetPseudo()
	   << " gagne " << score << " points, ce qui lui fait un total de "
	   << joueurs.GetJoueur(i).GetScore() << endl;
      if (score_max < joueurs.GetJoueur(i).GetScore()) {
        score_max = joueurs.GetJoueur(i).GetScore();
	score_max_cpt = 1;
        dernier_gagnant = i;
      } else {
	if (score_max == joueurs.GetJoueur(i).GetScore()) {
	  score_max_cpt++;
	}
      }
    }

  } while ((val < max_tours) && (score_max < max_points));
  // on s'arrete a 'max_tours' parties ou lorsqu'un joueur a atteint 'max_points' points
  if (score_max_cpt > 1) {
    cout << "Les gagnants sont :" << endl;
    for (int i = 0; i < joueurs.NbJoueurs(); i++) {
      if (joueurs.GetJoueur(i).GetScore() == score_max) {
	cout << "- " << joueurs.GetJoueur(i).GetPseudo() << endl;
      }
    }
  } else {
    cout << "Le gagnant est " << joueurs.GetJoueur(dernier_gagnant).GetPseudo();
  }
  cout << " avec un score de " << score_max << endl;
  return 0;
}
