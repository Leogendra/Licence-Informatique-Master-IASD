-- Toutes les données à entrer par défaut --

-- Les rôles --

INSERT INTO role VALUES(1, 'Administrateur');
INSERT INTO role VALUES(2, 'Gestionnaire de tournois');
INSERT INTO role VALUES(3, 'Capitaine');
INSERT INTO role VALUES(4, 'Joueur');

-- Les scores des tournois --

-- 1/ Les types de tournoi --

INSERT INTO tournament_type VALUES(1, 'Coupe');
INSERT INTO tournament_type VALUES(2, 'Championnat');
INSERT INTO tournament_type VALUES(3, 'Poules');

-- 2/ outcome --

INSERT INTO outcome_type VALUES(1, 'Victoire');
INSERT INTO outcome_type VALUES(2, 'Nul');
INSERT INTO outcome_type VALUES(3, 'Défaite');

-- 3/ Points par type & outcome --

INSERT INTO score_tournament VALUES(1, 1, 3);
INSERT INTO score_tournament VALUES(1, 3, 0);
INSERT INTO score_tournament VALUES(2, 1, 3);
INSERT INTO score_tournament VALUES(2, 2, 1);
INSERT INTO score_tournament VALUES(2, 3, 0);
INSERT INTO score_tournament VALUES(3, 1, 3);
INSERT INTO score_tournament VALUES(3, 3, 0);

-- Le compte administrateur est paramétré dans config.php! --