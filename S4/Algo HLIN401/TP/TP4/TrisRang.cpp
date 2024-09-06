#include <cstdlib>
#include "TrisRang.h"

using namespace std;


void fusion(int n1, int n2, int* T1, int* T2, int* T) {
  int a=0;
  int b=0;
  for (int i=0; i<(n1+n2); i++) {
    if (b>=n2 || (a<n1 && T1[a]<=T2[b])) {
      T[i]=T1[a];
      a++;
    }
    else {
      T[i]=T2[b];
      b++;
    }
  }
}



void trifusion(int n, int* T) {
  if (n>1) {
    int n1=n/2;
    int n2=n-n/2;
    int *T1 = new int[n1];
    int *T2 = new int[n2];

    for (int i=0; i<n; i++) {
      if (i<n/2) {T1[i]=T[i];}
      else {T2[i-(n/2)]=T[i];}
    }

    trifusion(n1,T1);
    trifusion(n2,T2);

    fusion(n1, n2, T1, T2, T);
    delete[] T1;
    delete[] T2;
  }
}



int pivot(int n, int* T, bool b) {
  return (b)?T[rand()%n]:T[0];
}



int rang(int k, int n, int* T, bool b) {
  if (n==1) {return T[0];}
  int p=pivot(n, T, b);
  int ni=0;
  int ne=0;
  for (int i=0;i<n; i++) {
    if (T[i]<p) {ni++;}
    else if (T[i]==p) {ne++;}
  }
  int ns=n-(ne+ni);

  if (k<=ni) {
    int a=0;
    int *Ti = new int[ni];
    for (int i=0; i<n; i++) {
      if (T[i]<p) {Ti[a]=T[i]; a++;}
    }
    return rang(k, ni, Ti, b);
  }
  else if (ni<k && k <=(ni+ne)) {return p;}
  else {
    int b=0;
    int *Ts = new int[ns];
    for (int i=0; i<n; i++) {
      if (T[i]>p) {Ts[b]=T[i]; b++;}
    }
    return rang(k-ni-ne, ns, Ts, b);
  }
}




void trirapide(int n, int* T, bool b) {
  if (n>1) {
  int p=pivot(n, T, b);
  int ni=0;
  int ne=0;
  int ns=0;
  for (int i=0;i<n; i++) {
    if (T[i]<p) {ni++;}
    else if (T[i]==p) {ne++;}
  }
  ns=n-(ne+ni);
  
  int *Ti = new int[ni];
  int *Te = new int[ne];  
  int *Ts = new int[ns];
  int a=0;
  int b=0;
  int c=0;
  for (int i=0;i<n; i++) {
    if (T[i]<p) {Ti[a]=T[i]; a++;}
    else if (T[i]==p) {Te[b]=T[i]; b++;}
    else {Ts[c]=T[i]; c++;}
  }
  trirapide(ns,Ts,b);
  trirapide(ni,Ti,b);
  
  int *T_intermediaire = new int[ni+ns];
  
  fusion(ns,ni,Ts,Ti,T_intermediaire);
  fusion(ne,ni+ns,Te,T_intermediaire,T);
  delete[] Ti;
  delete[] Te;
  delete[] Ts;
  delete[] T_intermediaire;
  }
}

