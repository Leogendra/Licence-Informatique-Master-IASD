// blur.cpp : Floute une image en niveau de gris

#include <stdio.h>
#include "image_ppm.h"
#include <iostream>
using namespace std;
int main(int argc, char* argv[])
{
  char cNomImgLue[250];
  int nH, nW, nTaille;
  int som;
  if (argc != 2) 
     {
       printf("Usage: ImageIn.pgm \n"); 
       exit (1) ;
     }
   
   sscanf (argv[1],"%s",cNomImgLue) ;

   OCTET *ImgIn;
   
   lire_nb_lignes_colonnes_image_pgm(cNomImgLue, &nH, &nW);
   nTaille = nH * nW;
  
   allocation_tableau(ImgIn, OCTET, nTaille);
   lire_image_pgm(cNomImgLue, ImgIn, nH * nW);
 	
    int tab[4];
    tab[0] = 4; tab[1] = 16; tab[2] = 64; tab[3] = 256;
    for(int k = 0; k < 4; k++){

        cout << "-------------------------------------" << tab[k] << "---------------" <<endl;

        float moy[tab[k]];   // tableau à nb_cadrans cases
        float variance[tab[k]];
        for(int i = 0; i < tab[k]; i++){
            moy[i] = 0;
            variance[i] = 0;
        }

        for(int i = 0; i < nTaille/tab[k]; i++) {            //boucle de 0 à taille_cadran
            for(int j = 0; j < tab[k]; j++) {                //boucle de 0 à nb_cadrans
                    moy[j] += ImgIn[i+j*(nTaille/tab[k])];   //moy[j] += ImgIn[i+j*(taille_cadran)]
            }    
        }

        for(int i = 0; i < tab[k]; i++){
            moy[i]/=(nTaille/tab[k]);                        //moy[j] /= taille_cadran
        }

        for(int i = 0; i < nTaille/tab[k]; i++) {
            for(int j = 0; j < tab[k]; j++) {
                float val =ImgIn[i+j*(nTaille/tab[k])];
                variance[j] += (val-moy[j])*(val-moy[j]);
            }
        }

        for(int i = 0; i < tab[k]; i++){
            variance[i]/=(nTaille/tab[k]);
        }
        
        for(int i = 0; i < tab[k]; i++){
            cout << moy[i] << " " << variance[i] << endl;
        }
    }
   free(ImgIn);
   return 1;
}
