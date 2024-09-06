% Ex 1 logique des propositions
% instances
b.
c.

% encoding
d :- c.
a :- f.
x :- e.
f :- b.
x :- b, not a.


% Ex 2 logique du premier ordre
% instance
herbivore(chevre).
humain(chaperon).

% encoding
animal(X) :- carnivore(X).
animal(X) :- herbivore(X).
%carnivore(X) :- omnivore(X).
%herbivore(X) :- omnivore(X).
animal(X) :- omnivore(X).
omnivore(X) :- humain(X).
animal(Y) :- carnivore(X), mange(X,Y).
plante(Y) :- herbivore(X), mange(X,Y).
mange(X,Y) :- carnivore(X), herbivore(Y), X != Y.
:- animal(X), plante(X).