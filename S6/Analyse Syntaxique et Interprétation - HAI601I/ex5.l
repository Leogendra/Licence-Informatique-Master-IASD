%{ /* analflex.l */
/* ZONE DE DEFINITION (OPTIONNELLE) */
/* ZONE DES REGLES apres le double pourcent (OBLIGATOIRE) */
%}
/* Alias */
chiffre ([0-9])
lettre ([a-zA-Z])
%%
if {return 300;}
then {return 301;}
else {return 302;}
throw {return 303;}
0[0-7]+ {return 310;}
0x[0-9A-Fa-f]+ {return 320;}
[1-9]{chiffre}* {return 330;}
{lettre}({lettre}|{chiffre}|_)* {return 340;}
({chiffre}+\.{chiffre}*|\.{chiffre}+)([eE][-+]?{chiffre}+)? {return 350;}
[\t\n]+ {return 380;}
"//".*\n {return 390;}
"/*"([^*]|"*"+[^*/])*"*"+"/" {return 391;}
. {return 999;}
%%
/* ZONE DES FONCTIONS C */
main() {
	int j; 
	char *invite="Saisissez un(des) mot(s) SVP :";
	printf(invite);
	while ((j=yylex())!=0) {
		printf("\nJeton : %i; de lexeme %s\n%s",j,yytext,invite);
	}
}