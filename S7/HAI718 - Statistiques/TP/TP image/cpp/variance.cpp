// test_couleur.cpp : Seuille une image en niveau de gris
#include <stdio.h>
#include "image_ppm.h"
using namespace std;

int main(int argc, char *argv[])
{
	char cNomImgLue[250], cNomTxtEcrite[250];
	int nH, nW, nTaille;
	float moyenne = 0;
	float variance = 0;

	if (argc != 3)
	{
		printf("Usage: ImageIn.pgm Resultat.dat\n");
		exit(1);
	}

	sscanf(argv[1], "%s", cNomImgLue);
	sscanf(argv[2], "%s", cNomTxtEcrite);

	OCTET * ImgIn;

	lire_nb_lignes_colonnes_image_pgm(cNomImgLue, &nH, &nW);
	nTaille = nH * nW;

	allocation_tableau(ImgIn, OCTET, nTaille);
	lire_image_pgm(cNomImgLue, ImgIn, nH *nW);

	int TxtOut[256] = { 0 };
	float Gaussienne[256] = { 0. };

	FILE * file_output;
	if ((file_output = fopen(cNomTxtEcrite, "w")) == NULL) {
		exit(EXIT_FAILURE);
	};

	for (int i = 0; i < nH; i++) {
		for (int j = 0; j < nW; j++) {
			TxtOut[ImgIn[i*nW+j]]++;
			moyenne += ImgIn[i*nW+j];
		}
	}

	moyenne /= nTaille;

	for (int i = 0; i < nH; i++) {
		for (int j = 0; j < nW; j++) {
			variance += (ImgIn[i *nW + j] - moyenne) * (ImgIn[i *nW + j] - moyenne);
		}
	}
	variance /= nTaille;

	for (int i = 0; i < 256; i++) {
		Gaussienne[i] = (float) exp(-1.*(i-moyenne)*(i-moyenne)/(2*variance)) / sqrt(variance*2*M_PI);
	}

	printf("moyenne = %f, variance = %f, equart-type = %f\n", moyenne, variance, sqrt(variance));

	for (int i = 0; i < 256; i++) {
		fprintf(file_output, "%d\t%f\t%f\n", i, ((float)TxtOut[i]/(float)nTaille), (float)Gaussienne[i]);
		//fprintf(file_output, "%d\t%f\n", i, (float)Gaussienne[i]);
	}

	fclose(file_output);
	free(ImgIn);

	return 1;
}