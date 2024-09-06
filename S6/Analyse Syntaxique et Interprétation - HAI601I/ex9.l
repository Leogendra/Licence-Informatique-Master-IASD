%{
int niveau = 0;
int tab[10];
%}
%option noyywrap
%s DANSLISTE
%%
^\n[ \t]*[-*] {
	BEGIN(DANSLISTE);
	niveau = 0;
	tab[niveau] = yyleng-1;
	puts("<ul><li>");
}

<DANSLISTE>^[ \t]*[*-]
	puts("</li>");
	if (diff == 0 || diff == 1) {puts("<li>")}
	else if (diff > 1) {
		puts("<ul><li>")
		niveau++;
	}
	else {
		do {
			puts("</ul>");
			niveau--;
		} while (niveau > 0 && tab[niveau] > yyleng-1);
	}



%%
int main(int argc, char** argv) {

}