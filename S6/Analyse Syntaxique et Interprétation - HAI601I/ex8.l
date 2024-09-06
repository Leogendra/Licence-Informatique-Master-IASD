%{
int nbcar = 0;
int nbmot = 0;
int nbligne = 0;
}%
%%
[^\n\t ]+ {nbmot++; nbcar+=yyleng;}
. {nbcar++;}
\n {nbcar++; nbligne++;}
%%
int main(int argc, char** argv) {
	if (argc == 2) {
		yyin = fopen(argv[1],"r");
		while (yylex() != 0) {};
		printf("\nnb char = %d ; nb mots = %d ; nb lignes = %d",nbcar,nbmot,nbligne);
		fclose(yyin);
	}
}