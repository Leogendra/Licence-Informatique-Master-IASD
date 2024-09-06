#include <stdio.h>
#include "image_ppm.h"
using namespace std;

int main(int argc, char* argv[]) {
    char cNomImgLue[250], cNomTxtEcrite[250];
    int nH, nW, nTaille;
  
    if (argc != 3) {
        printf("Usage: ImageIn.pgm ImageOut.dat\n"); 
        exit(1);
    }
  
    sscanf (argv[1],"%s",cNomImgLue) ;
    sscanf (argv[2],"%s",cNomTxtEcrite);


    OCTET *ImgIn;

    lire_nb_lignes_colonnes_image_pgm(cNomImgLue, &nH, &nW);
    nTaille = nH * nW;
  
    allocation_tableau(ImgIn, OCTET, nTaille);
    lire_image_pgm(cNomImgLue, ImgIn, nH * nW);
  
    int tabout[256] = {0};
    FILE* file_output;
    if((file_output = fopen(cNomTxtEcrite,"w")) == NULL) {
        exit(EXIT_FAILURE);
    };

    for (int i=0; i < nH; i++){  
        for (int j=0; j < nW; j++) {
            tabout[ImgIn[i*nW+j]]++;
        }
    }

    int division = 2;
    int tabDiv[256] = {0};
    int nH_div = nH/division;
    int nW_div = nH/division;
    int nTaille_Div = nH_div*nW_div;
    for (int i=0; i < division; i++) {
        for (int j=0; j < division; j++) {
            for (int k=0; k < nH_div; k++) {
                for (int l=0; l < nW_div; l++) {
                    tabDiv[k*nW/division + l] = tabout[i*nW/division + j*nH/division + k*nW/division + l];
                }
            }

            float moyenne = 0;
            for (int i = 0; i < 256; i++) {
                    moyenne += tabout[i]*i;
            }
            moyenne /= nTaille_Div;

            float variance = 0;
            for (int i=0; i < nH_div; i++){
                for (int j=0; j < nW_div; j++){
                variance += (tabDiv[i*nW_div + j] - moyenne) * (tabDiv[i*nW_div + j] - moyenne);
                }
            }
            variance = variance / (float)nTaille_Div;

            float laplacienne[256] = {0.};
            float b = sqrt(variance/2);
            for(int i = 0; i < 256; i++){
                laplacienne[i] = (1./(2*b)) * exp(-abs(i-moyenne)/b); 
            }   

            printf("Bloc (%i, %i) :\n",i,j);
            printf("Moyenne = %f\n", moyenne);
            printf("Variance = %f\n ", variance);
            printf("EcartType = %f\n ", sqrt(variance));
            for (int i = 0; i < 256; i++) {
                fprintf(file_output, "%d\t%f\t%f\n", i, (float)tabDiv[i]/(float)nTaille_Div, laplacienne[i]);
            }
            fprintf(file_output, "\n");
        }
    } 
    
    fclose(file_output);
    free(ImgIn);

    return 1;
}