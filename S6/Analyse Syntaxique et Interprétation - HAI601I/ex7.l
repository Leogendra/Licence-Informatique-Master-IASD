%{#include <stdio.h>%}

/* Alias */
%option noyywrap
%%
^[\t ]*\n {//filtre ligne blances}
^[\t ]+ {//filtre début ligne blanches}
[\t ]+S {//filtre fin ligne blanches}
[\t ]+ {int c;
		 if ((c = input())!=EOF && c!=0) {
			unput(c);
			putchar('_');
		 }
		}
%%
/* ZONE DES FONCTIONS C */
main(int argc, char** argv) {
	if (argc != 3) {
		printf("%s fichier_in fichier_out\n",argv[0]);
	}
	/*
	yyin = fopen(argv[1],"r");
	yyout = fopen(argv[2],"w");
	*/
	int j, nbLignes, nbMot, nbChar;

	while ((j==yylex) != 0) {printf("%s\n",yylex);}
}