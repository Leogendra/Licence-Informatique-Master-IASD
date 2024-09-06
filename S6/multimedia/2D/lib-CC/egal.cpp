// Donne à un pixel la valeur la plus haute entre ses 8 voisins
#include <stdio.h>
#include "image_ppm.h"


float fonc(int* tab, int t, int n) {
  float rep = 0;
  for (int i=0; i<n; i++){
    rep += (float)tab[i]/(float)t;
  }
  return rep;
}

int main(int argc, char* argv[])
{
  char cNomImgLue[250], cNomImgEcrite[250];
  int nH, nW, nTaille, pix_min;
  float rep =0;
  
  if (argc != 3) 
     {
       printf("Usage: ImageIn.pgm ImageOut.pgm\n"); 
       exit (1) ;
     }
   
   sscanf (argv[1],"%s",cNomImgLue) ;
   sscanf (argv[2],"%s",cNomImgEcrite);

   OCTET *ImgIn, *ImgOut;
   
   lire_nb_lignes_colonnes_image_pgm(cNomImgLue, &nH, &nW);
   nTaille = nH * nW;
  
   allocation_tableau(ImgIn, OCTET, nTaille);
   lire_image_pgm(cNomImgLue, ImgIn, nH * nW);
   allocation_tableau(ImgOut, OCTET, nTaille);

  int TxtOut[256] = {0};
  for (int i=0; i < nH; i++) {
    for (int j=0; j < nW; j++){
          TxtOut[ImgIn[i*nW+j]]++;
    }
  }

    for (int i=0; i < nH; i++) {
      for (int j=0; j < nW; j++){
        ImgOut[i*nW+j]=floor(fonc(TxtOut, nTaille, ImgIn[i*nW+j])*255);
      }
    }

   ecrire_image_pgm(cNomImgEcrite, ImgOut,  nH, nW);
   free(ImgIn); free(ImgOut);

   return 1;
}