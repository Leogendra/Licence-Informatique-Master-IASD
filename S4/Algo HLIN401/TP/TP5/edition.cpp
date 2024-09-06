#include <iostream>
#include <string>

using namespace std;

int** matriceDistances(string s1, string s2) {
  int t1 = s1.size()+1;
  int t2 = s2.size()+1;
  int** M = new int*[t1];
  for (int i=0; i<t1; i++) {
    M[i] = new int[t2];
  }

  for (int i=0; i<t1; i++) {M[i][0] = i;}
  for (int i=0; i<t2; i++) {M[0][i] = i;}

  int e=0;
  for (int i=1; i<t1; i++) {
    for (int j=1; j<t2; j++) {
      e=0;
      if (s1[i-1]!=s2[j-1]) {e=1;}
      M[i][j]=min(M[i-1][j]+1,min(M[i][j-1]+1,M[i-1][j-1]+e));
    }
  }
  return M;
}




int alignement(string& s1, string& s2) {
  int** M = matriceDistances(s1, s2);
  int i = s1.size();
  int j = s2.size();
  while (i>0 && j>0) {
    if (M[i][j]==M[i-1][j-1] && s1[i-1]==s2[j-1]) {i--; j--;}
    else if (M[i][j]==M[i-1][j-1]+1) {i--; j--;}
    else if (M[i][j]==M[i-1][j]+1) {s2.insert(j,"_"); i--;}
    else if (M[i][j]==M[i][j-1]+1) {s1.insert(i,"_"); j--;}
  }
  while (j>0) {s1.insert(0,"_"); j--;}
  while (i>0) {s2.insert(0,"_"); i--;}

  int cpt=0;
  for (int k=0; k<s1.size(); k++) {
    if (s1[k]!=s2[k]) {cpt++;}
  }

  for (int w=0; w<=i; w++) {delete[] M[w];}
  delete[] M;

  return cpt;
}




int distanceEdition(string s1, string s2) {
  int m=s1.size()+1;
  int n=s2.size()+1; 

  int* P = new int[n];
  int* C = new int[n];

  for (int i=0; i<n; i++) {P[i] = i;}

  int e=0;
  for (int i=1; i<m; i++) {
    C[0]=i;
    for (int j=1; j<n; j++) {
      e=0;
      if (s1[i-1]!=s2[j-1]) {e=1;}
      C[j] = min(P[j]+1,min(C[j-1]+1,P[j-1]+e));
    }
    for (int j=0; j<n; j++) {P[j]=C[j];}
  }
  int res = C[n-1];
  delete[] C;
  delete[] P;
  return res;
}
