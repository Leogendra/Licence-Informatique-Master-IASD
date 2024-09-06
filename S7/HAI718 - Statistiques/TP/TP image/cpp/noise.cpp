// noise.cpp : Floute une image en niveau de gris

#include <stdio.h>
#include "image_ppm.h"

int main(int argc, char* argv[])
{
  char cNomImgLue[250], cNomImgBlur[250], cNomImgEcrite[250];
  int nH, nW, nTaille;
  int som;
  if (argc != 4) 
     {
       printf("Usage: ImageIn.pgm ImageBlured.pgm ImageOut.pgm \n"); 
       exit (1) ;
     }
   
   sscanf (argv[1],"%s",cNomImgLue);
   sscanf (argv[2],"%s",cNomImgBlur);
   sscanf (argv[3],"%s",cNomImgEcrite);

   OCTET *ImgIn, *ImgBlur, *ImgOut;
   
   lire_nb_lignes_colonnes_image_pgm(cNomImgLue, &nH, &nW);
   nTaille = nH * nW;
  
   allocation_tableau(ImgIn, OCTET, nTaille);
   lire_image_pgm(cNomImgLue, ImgIn, nH * nW);
   allocation_tableau(ImgBlur, OCTET, nTaille);
   lire_image_pgm(cNomImgBlur, ImgBlur, nH * nW);
   allocation_tableau(ImgOut, OCTET, nTaille);
	
   for (int i=0; i < nTaille; i++)
    ImgOut[i]= ImgIn[i];

  for (int i=0; i < nH; i++) {
    for (int j=0; j < nW; j++) {
      ImgOut[i*nW+j] = ImgBlur[i*nW+j] - ImgIn[i*nW+j] + 128;
    }
  }

   ecrire_image_pgm(cNomImgEcrite, ImgOut,  nH, nW);
   free(ImgIn);free(ImgOut);
   return 1;
}