% oedipe-family-factbase.lp

% les caractères accentués ne sont pas admis par clingo
% ou alors il faut les mettre entre guillemets

% BASE DE FAITS 

% personnage est un prédicat unaire
% on utilise une écriture raccourcie permise par Clingo: 
% p(a;b). est un raccourci pour p(a). p(b). 
% Ne pas confondre p(a;b) et p(a,b) : écriture raccourcie pour prédicat unaire, et prédicat binaire !
personnage(agenor;cadmos;harmonie;ino;agave;polydore;labdacos;nicteis;penthe;oclasos).
personnage(menecee;jocaste;creon;hemon;eurydice;laios;oedipe).
personnage(antigone;ismene;eteocle;polynice;thersandre;ladamos).

% sexe des personnages
homme(agenor). % agénor
homme(cadmos).
femme(harmonie).
femme(ino).
femme(agave).	  % agavé
homme(polydore).
homme(labdacos).
femme(nicteis).   % nictéis
homme(penthe). % penthé
homme(oclasos). 
homme(menecee). % ménécée
femme(jocaste).
homme(creon).   % créon
homme(hemon). % hémon
femme(eurydice).
homme(laios).
homme(oedipe).
femme(antigone).
femme(ismene).   % ismène
homme(eteocle). % étéocle
homme(polynice).
homme(thersandre).
homme(ladamos).

% relations parent-enfant
aEnfant(agenor,cadmos).
aEnfant(cadmos,ino).
aEnfant(cadmos,agave).
aEnfant(cadmos,polydore).
aEnfant(harmonie,ino).
aEnfant(harmonie,agave).
aEnfant(harmonie,polydore).
aEnfant(polydore,labdacos).
aEnfant(nicteis,labdacos).
aEnfant(agave,penthe).
aEnfant(labdacos,laios).
aEnfant(penthe,oclasos).
aEnfant(oclasos,menecee).
aEnfant(menecee,jocaste).
aEnfant(menecee,creon).
aEnfant(creon,hemon).
aEnfant(eurydice,hemon). 
aEnfant(laios,oedipe).
aEnfant(jocaste,oedipe).
aEnfant(jocaste,antigone).
aEnfant(jocaste,eteocle).
aEnfant(jocaste,ismene).
aEnfant(jocaste,polynice).
aEnfant(oedipe,antigone).
aEnfant(oedipe,eteocle).
aEnfant(oedipe,ismene).
aEnfant(oedipe,polynice).
aEnfant(polynice, thersandre). 
aEnfant(eteocle,ladamos). 

% les rois
roi(agenor,tyr).
roi(cadmos,thebes).	% thèbes
roi(penthe,thebes).
roi(creon, thebes).
roi(oedipe,thebes).
roi(eteocle,thebes).
roi(ladamos,thebes). 

% BASE DE REGLES (A ECRIRE)
%1.
aPourParent(Y,X) :- aEnfant(X,Y).
aPourPere(Y,X) :- aEnfant(X,Y), homme(X).
aPourMere(Y,X) :- aEnfant(X,Y), femme(X).

%2.
roiDontPereEtaitDejaRoi(X) :- roi(X,V2), aPourPere(X,Y), roi(Y,V1).

%3.
roiDontPereEtaitDejaRoiDuMemeLieu(X) :- roi(X,V), aPourPere(X,Y), roi(Y,V).

%4.
aPourGrandParent(X,Z) :- aPourParent(X,Y), aPourParent(Y,Z).
grandParentEudipe(X) :- aPourGrandParent(oedipe,X).

%5.
aPourAncetre(X,Z) :- aPourGrandParent(X,Y), aPourParent(Y,Z).
aPourAncetre(X,Z) :- aPourAncetre(X,Y), aPourParent(Y,Z).
ancetreEudipe(Y) :- aPourAncetre(oedipe,Y).

%6.
sexeInconnu(X) :- personnage(X), not femme(X), not homme(X).

%7.
pereInconnu(X) :- personnage(X), not aPourPere(X,_).
mereInconnu(X) :- personnage(X), not aPourMere(X,_).
parentsInconnus(X) :- pereInconnu(X), mereInconnu(X).

%8.
unSeulParentInconnu(X) :- not pereInconnu(X), mereInconnu(X).
unSeulParentInconnu(X) :- pereInconnu(X), not mereInconnu(X).

%9.
sibling(Y,X) :- sibling(X,Y).
sibling(X,Y) :- aPourPere(X,P), aPourPere(Y,P), aPourMere(X,M), aPourMere(Y,M).
halfSibling(Y,X) :- halfSibling(X,Y).
halfSibling(X,Y) :- aPourPere(X,P), aPourPere(Y,P), aPourMere(X,M1), aPourMere(Y,M2), M1 != M2.
halfSibling(X,Y) :- aPourPere(X,P1), aPourPere(Y,P2), aPourMere(X,M), aPourMere(Y,M), P1 != P2.

%10.

% show
#show halfSibling/2.