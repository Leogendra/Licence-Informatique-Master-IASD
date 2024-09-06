#include <iostream>
#include <cstdlib>
#include "TrisRang.h"

using namespace std;


void tableauAleatoire(int n, int* T)
{
  for (int i=0; i < n; i++)
    T[i] = rand() % (4*n);
}

void tableauCroissant(int n, int* T)
{
  T[0] = rand() % 5;
  for (int i=1; i < n; i++)
    T[i] = T[i-1] + (rand() % 5);
}

void tableauDecroissant(int n, int* T)
{
  T[n-1] = rand() % 5;
  for (int i=n-2; i >= 0; i--)
    T[i] = T[i+1] + (rand() % 5);
}

void copier(int n, int* T, int* T2)
{
  for (int i = 0; i < n; i++) 
    T2[i] = T[i];
}

void afficher(int n, int* T)
{
  int m = (n > 25)?20:n;
  cout << "[";
  for(int i=0;i<m-1;i++) cout << T[i] << ",";
  if (m) cout << T[m-1];
  if (m < n) cout << ",... (+ " << n-m << " éléments)";
  cout << "]" << endl;
}

bool estTrie(int n, int* T)
{
  for (int i = 0; i < n-1; i++)
    if (T[i] > T[i+1])
      return false;
  return true;
}

int somme(int n, int* T)
{
  int s = 0;
  for (int i=0; i < n; i++) s += T[i];
  return s;
}

int main()
{
  srand(time(NULL));

  int question, type, n;
  int *T, *T1, *T2;

  while (true)
  {
    cout << "Question à tester (0 pour quitter) : "; 
    cin >> question;
    if (question <= 0) return 0;
    if (question > 4) continue;

    if (question == 1) 
    {
      int n1, n2;
      cout << "Taille du premier tableau : ";
      cin >> n1; if (n1 < 0) continue; 
      cout << "Taille du second tableau : ";
      cin >> n2; if (n2 < 0) continue;

      n = n1 + n2;
      if (n1) { T1 = new int[n1]; tableauCroissant(n1, T1); }
      if (n2) { T2 = new int[n2]; tableauCroissant(n2, T2); }
      if (n)  { T = new int[n]; }
      cout << "T1 : "; afficher(n1, T1);
      cout << "T2 : "; afficher(n2, T2);
      fusion(n1, n2, T1, T2, T);
      cout << "Fusion : "; afficher(n, T);
      if (estTrie(n, T) and somme(n, T) == somme(n1, T1) + somme(n2, T2))
        cout << "=> OK : le résultat semble correct (à vérifier !)" << endl;
      else 
        cout << "=> ERREUR !" << endl;
      cout << endl;
      
      if (n1) delete[] T1;
      if (n2) delete[] T2;
      if (n) delete[] T;
      continue;
    }

    cout << "Type de tableau (1. aléatoire, 2. croissant, 3. décroissant) : "; // << endl
    cin >> type;
    
    if (type < 1 or type > 3) continue;

    cout << "Taille du tableau : ";
    cin >> n;
    if (n < 0) continue;

    if (n) T = new int[n];
    switch (type)
    {
      case 1: tableauAleatoire(n, T); break;
      case 2: tableauCroissant(n, T); break;
      case 3: tableauDecroissant(n, T); break;
    }

    cout << "Tableau : "; afficher(n, T);

    clock_t t1, t2, t3;
    int r1, r2, k;

    switch (question)
    {
      case 2: 
        if (n) T1 = new int[n];
        copier(n, T, T1);
        t1 = clock();
        trifusion(n, T1);
        t2 = clock();
        cout << "Après tri : "; afficher(n, T1);
        if (estTrie(n, T1) and somme(n, T) == somme(n, T1))
          cout << "=> OK : le résultat semble correct (temps : " 
               << (double)(t2-t1)*1000000 / CLOCKS_PER_SEC << " µs)" << endl;
        else
        {
          cout << "=> ERREUR !" << endl; 
        }
        if (n) delete[] T1;
        break;

      case 3:
        k = 0;
        while (k < 1 or k > n)
        {
          cout << "Choisir un rang entre 1 et " << n << " : ";
          cin >> k;
        }
        t1 = clock();
        r1 = rang(k, n, T, false);
        t2 = clock();
        r2 = rang(k, n, T, true);
        t3 = clock();

        if (r1 != r2) 
          cout << "=> ERREUR : résultat " << r1 << " avec pivot fixe et " << r2 << " avec pivot aléatoire" << endl;

        else
        {
          cout << "=> A VERIFIER : l'élément de rang " << k << " calculé est " << r1 << endl
               << "    temps avec pivot fixe :      " << (double)(t2-t1)*1000000 / CLOCKS_PER_SEC << "µs" << endl
               << "    temps avec pivot aléatoire : " << (double)(t3-t2)*1000000 / CLOCKS_PER_SEC << "µs" << endl;
        }

        break;

      case 4:
        if (n) T1 = new int[n];
        if (n) T2 = new int[n];
        copier(n, T, T1);
        copier(n, T, T2);

        t1 = clock();
        trirapide(n, T1, false);
        t2 = clock();
        trirapide(n, T2, true);
        t3 = clock();

        cout << "Après tri (pivot fixe) : "; afficher(n, T1);
        cout << "Après tri (pivot aléatoire) : "; afficher(n, T2);

        bool b1 = estTrie(n, T1), b2 = estTrie(n, T2);
        int s = somme(n, T), s1 = somme(n, T1), s2 = somme(n, T2);

        if (not b1 or s != s1) cout << "=> ERREUR avec pivot fixe !" << endl;
        if (not b2 or s != s2) cout << "=> ERREUR avec pivot aléatoire !" << endl;

        if (b1 and b2)
        {
          cout << "=> OK : le résultat semble correct" << endl
               << "Temps de calcul :" << endl
               << "  avec pivot fixe : " << (double)(t2-t1)*1000000 / CLOCKS_PER_SEC << "µs" << endl
               << "  avec pivot aléatoire : " << (double)(t3-t2)*1000000 / CLOCKS_PER_SEC << "µs" << endl;
        }
        
        if (n) delete[] T1;
        if (n) delete[] T2;
        break;

      }

    if (n) delete[] T;
    cout << endl;
  }
  
  return 0;
}
