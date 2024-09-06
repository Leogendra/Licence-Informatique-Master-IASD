% BF
parent(a,c).
parent(a,d).
parent(a,e).
parent(b,c).
parent(b,d).
parent(b,e).

% BR
sibling(X,Y) :- parent(Z,X), parent(Z,Y), X!=Y, not sibling(Y,X).

%#show sibling/2. % restriction affichage pour lisibilité



% Coloration de graphes
node(1..7).

edge(1,2).
edge(1,3).
edge(2,3).
edge(2,4).
edge(3,4).
edge(3,5).
edge(3,6).
edge(4,5).
edge(5,6).

color(r).
color(g).
color(b).

% règles
:- node(X1), node(X2), color(C), edge(X1,X2), colored(X1,C), colored(X2,C).
colored(X,Y1) :- node(X), color(Y1), color(Y2), color(Y3), Y1!=Y2, Y2!=Y3, Y3!=Y1, not colored(X,Y2), not colored(X,Y3).

#show colored/2. % restriction affichage pour lisibilité




% Puzzle du Zebre (Enigme d'Enstein)

% BASE DE FAITS
maison(1..5).

% BASE DE REGLES

% L'idée est d'avoir 5 prédicats binaires liant une maison à ses propriétés : 
% aCouleur(M,C), aBoisson(M,B), aAnimal(M,A), aNationalite(M,N), aCigarette(M,C)

% 1. Règles générant toutes les affectations possibles

% Toute maison a une couleur parmi : red, green, blue, yellow ivory (5 règles)
aCouleur(MAISON, red) :- maison(MAISON), not aCouleur(MAISON, green), not aCouleur(MAISON, yellow), not aCouleur(MAISON, ivory), not aCouleur(MAISON, blue).
aCouleur(MAISON, green) :- maison(MAISON), not aCouleur(MAISON, yellow), not aCouleur(MAISON, ivory), not aCouleur(MAISON, blue), not aCouleur(MAISON, red).
aCouleur(MAISON, yellow) :- maison(MAISON), not aCouleur(MAISON, ivory), not aCouleur(MAISON, blue), not aCouleur(MAISON, red), not aCouleur(MAISON, green).
aCouleur(MAISON, ivory) :- maison(MAISON), not aCouleur(MAISON, blue), not aCouleur(MAISON, red), not aCouleur(MAISON, green), not aCouleur(MAISON, yellow).
aCouleur(MAISON, blue) :- maison(MAISON), not aCouleur(MAISON, red), not aCouleur(MAISON, green), not aCouleur(MAISON, yellow), not aCouleur(MAISON, ivory).

% Toute maison a une nationalite parmi : english, japanese, norwegian, spannish, ukrainian (5 règles)
aNationalite(MAISON, english) :- maison(MAISON), not aNationalite(MAISON, japanese), not aNationalite(MAISON, norwegian), not aNationalite(MAISON, spanish), not aNationalite(MAISON, ukrainian).
aNationalite(MAISON, japanese) :- maison(MAISON), not aNationalite(MAISON, norwegian), not aNationalite(MAISON, spanish), not aNationalite(MAISON, ukrainian), not aNationalite(MAISON, english).
aNationalite(MAISON, norwegian) :- maison(MAISON), not aNationalite(MAISON, spanish), not aNationalite(MAISON, ukrainian), not aNationalite(MAISON, english), not aNationalite(MAISON, japanese).
aNationalite(MAISON, spanish) :- maison(MAISON), not aNationalite(MAISON, ukrainian), not aNationalite(MAISON, english), not aNationalite(MAISON, japanese), not aNationalite(MAISON, norwegian).
aNationalite(MAISON, ukrainian) :- maison(MAISON), not aNationalite(MAISON, english), not aNationalite(MAISON, japanese), not aNationalite(MAISON, norwegian), not aNationalite(MAISON, spanish).

% Toute maison a une boisson parmi : coffee, milk, orange, tea, water (5 règles)
aBoisson(MAISON, coffee) :- maison(MAISON), not aBoisson(MAISON, milk), not aBoisson(MAISON, orange), not aBoisson(MAISON, tea), not aBoisson(MAISON, water).
aBoisson(MAISON, milk) :- maison(MAISON), not aBoisson(MAISON, orange), not aBoisson(MAISON, tea), not aBoisson(MAISON, water), not aBoisson(MAISON, coffee).
aBoisson(MAISON, orange) :- maison(MAISON), not aBoisson(MAISON, tea), not aBoisson(MAISON, water), not aBoisson(MAISON, coffee), not aBoisson(MAISON, milk).
aBoisson(MAISON, tea) :- maison(MAISON), not aBoisson(MAISON, water), not aBoisson(MAISON, coffee), not aBoisson(MAISON, milk), not aBoisson(MAISON, orange).
aBoisson(MAISON, water) :- maison(MAISON), not aBoisson(MAISON, coffee), not aBoisson(MAISON, milk), not aBoisson(MAISON, orange), not aBoisson(MAISON, tea).

% Toute maison a un animal parmi : dog, fox, horse, snail, zebra (5 règles)
aAnimal(MAISON, dog) :- maison(MAISON), not aAnimal(MAISON, fox), not aAnimal(MAISON, horse), not aAnimal(MAISON, snail), not aAnimal(MAISON, zebra).
aAnimal(MAISON, fox) :- maison(MAISON), not aAnimal(MAISON, horse), not aAnimal(MAISON, snail), not aAnimal(MAISON, zebra), not aAnimal(MAISON, dog).
aAnimal(MAISON, horse) :- maison(MAISON), not aAnimal(MAISON, snail), not aAnimal(MAISON, zebra), not aAnimal(MAISON, dog), not aAnimal(MAISON, fox).
aAnimal(MAISON, snail) :- maison(MAISON), not aAnimal(MAISON, zebra), not aAnimal(MAISON, dog), not aAnimal(MAISON, fox), not aAnimal(MAISON, horse).
aAnimal(MAISON, zebra) :- maison(MAISON), not aAnimal(MAISON, dog), not aAnimal(MAISON, fox), not aAnimal(MAISON, horse), not aAnimal(MAISON, snail).

% Toute maison a une marque de cigarette parmi : chesterfield, kool, luckyStrike, oldGold, parliament (5 règles)
aCigarette(MAISON, chesterfield) :- maison(MAISON), not aCigarette(MAISON, kool), not aCigarette(MAISON, luckyStrike), not aCigarette(MAISON, oldGold), not aCigarette(MAISON, parliament).
aCigarette(MAISON, kool) :- maison(MAISON), not aCigarette(MAISON, luckyStrike), not aCigarette(MAISON, oldGold), not aCigarette(MAISON, parliament), not aCigarette(MAISON, chesterfield).
aCigarette(MAISON, luckyStrike) :- maison(MAISON), not aCigarette(MAISON, oldGold), not aCigarette(MAISON, parliament), not aCigarette(MAISON, chesterfield), not aCigarette(MAISON, kool).
aCigarette(MAISON, oldGold) :- maison(MAISON), not aCigarette(MAISON, parliament), not aCigarette(MAISON, chesterfield), not aCigarette(MAISON, kool), not aCigarette(MAISON, luckyStrike).
aCigarette(MAISON, parliament) :- maison(MAISON), not aCigarette(MAISON, chesterfield), not aCigarette(MAISON, kool), not aCigarette(MAISON, luckyStrike), not aCigarette(MAISON, oldGold).


% 2. Contraintes de différence : on les modélise par des contraintes négatives en éliminant tous les mondes dans lesquelles 2 maisons ont une même valeur pour une même propriété
:- aBoisson(M1, B), aBoisson(M2, B), M1 != M2.
:- aAnimal(M1, A), aAnimal(M2, A), M1 != M2.
:- aNationalite(M1, N), aNationalite(M2, N), M1 != M2.
:- aCigarette(M1, M), aCigarette(M2, M), M1 != M2.
:- aCouleur(M1, C), aCouleur(M2, C), M1 != M2.
 

%3. Contraintes du puzzle

%Predicat aCote, AGauche, et aDroite :
aDroite(M1,M2) :- maison(M1), maison(M2), M1 = M2+1.	%si M1 a droite de M2 on a M2 M1 donc M1 == M2+1
aGauche(M1,M2) :- maison(M1), maison(M2), M2 = M1+1.			%si M1 est a gauche de M2, M2 est a droite de M1
aCote(M1,M2) :- aDroite(M1,M2).			%soit M1 est a droite de M2
aCote(M1,M2) :- aGauche(M1,M2).			%soit M1 est a gauche de M2

% The Englishman lives in the red house
:- aCouleur(M1, red), aNationalite(M2, english), M1 != M2.

% The Spaniard owns the dog.
:- aNationalite(M1,spanish), aAnimal(M2, dog), M1 != M2.

% Coffee is drunk in the green house.
:- aBoisson(M1, coffee), aCouleur(M2, green), M1 != M2.

% The Ukrainian drinks tea.
:- aNationalite(M1,ukrainian), aBoisson(M2, tea), M1 != M2.

% The green house is immediately to the right of the ivory house.
:- aCouleur(M1, green), aCouleur(M2, ivory), not aDroite(M1,M2).

% The Old Gold smoker owns snails.
:- aCigarette(M1,oldGold), aAnimal(M2,snail), M1 != M2.

% Kools are smoked in the yellow house.
:- aCigarette(M1,kool), aCouleur(M2,yellow), M1 != M2.

% Milk is drunk in the middle house.
:- aBoisson(M1,milk), M1 != 3.

% The Norwegian lives in the first house.
:- aNationalite(M1,norwegian), M1 != 1.

% The man who smokes Chesterfields lives in the house next to the man with the fox.
:- aCigarette(M1, chesterfield), aAnimal(M2,fox), not aCote(M1,M2).	%si tes pas a cote marche pas

% Kools are smoked in the house next to the house where the horse is kept. [should be "... a house ...", see discussion below]
:- aCigarette(M1,kool), aAnimal(M2,horse), not aCote(M1,M2).

% The Lucky Strike smoker drinks orange juice.
:- aCigarette(M1,luckyStrike), aBoisson(M2,orange), M1 != M2.

% The Japanese smokes Parliaments.
:- aNationalite(M1,japanese), aCigarette(M2,parliament), M1 != M2.

% The Norwegian lives next to the blue house.
:-aNationalite(M1,norwegian), aCouleur(M2, blue), not aCote(M1,M2).


% 4. Enfin, une règle pour zoli affichage des résultats :
% Pour une maison X, grouper toutes ses propriétés dans un seul prédicat 
maisons(MAISON, COULEUR, NATIONALITE, BOISSON, ANIMAL, CIGARETTE) :- maison(MAISON), aCouleur(MAISON, COULEUR), aNationalite(MAISON, NATIONALITE), aBoisson(MAISON, BOISSON), aAnimal(MAISON, ANIMAL), aCigarette(MAISON, CIGARETTE).

#show maisons/6.