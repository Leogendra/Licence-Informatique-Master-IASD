-- Données pour faire des tests sur le site non live --

-- Les utilisateurs --

DELETE FROM user WHERE id IN (2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010);

-- mdp = 12345678 --
INSERT INTO user VALUES(2001, 'michel.meynard@umontpellier.fr', '$2y$10$TraQzPAFskcMqp4zqWekXOBQoVqDt5emHWrnIOVpY3ZITlWduz2YK', 'Michel Meynard', NULL);
INSERT INTO user VALUES(2002, 'pierre.pompidor@umontpellier.fr', '$2y$10$TraQzPAFskcMqp4zqWekXOBQoVqDt5emHWrnIOVpY3ZITlWduz2YK', 'Pierre Pompidor', NULL);
INSERT INTO user VALUES(2003, 'david.delahaye@umontpellier.fr', '$2y$10$TraQzPAFskcMqp4zqWekXOBQoVqDt5emHWrnIOVpY3ZITlWduz2YK', 'David Delahaye', NULL);
INSERT INTO user VALUES(2004, 'bruno.grenet@umontpellier.fr', '$2y$10$TraQzPAFskcMqp4zqWekXOBQoVqDt5emHWrnIOVpY3ZITlWduz2YK', 'Bruno Grenet', NULL);
INSERT INTO user VALUES(2005, 'vincent.boudet@umontpellier.fr', '$2y$10$TraQzPAFskcMqp4zqWekXOBQoVqDt5emHWrnIOVpY3ZITlWduz2YK', 'Vincent Boudet', NULL);
INSERT INTO user VALUES(2006, 'clementine.nebut@umontpellier.fr', '$2y$10$TraQzPAFskcMqp4zqWekXOBQoVqDt5emHWrnIOVpY3ZITlWduz2YK', 'Clémentine Nebut', NULL);
INSERT INTO user VALUES(2007, 'anne-muriel.arigon@umontpellier.fr', '$2y$10$TraQzPAFskcMqp4zqWekXOBQoVqDt5emHWrnIOVpY3ZITlWduz2YK', 'Anne-Muriel Arigon', NULL);
INSERT INTO user VALUES(2008, 'philippe.jansen@umontpellier.fr', '$2y$10$TraQzPAFskcMqp4zqWekXOBQoVqDt5emHWrnIOVpY3ZITlWduz2YK', 'Philippe Janssen', NULL);
INSERT INTO user VALUES(2009, 'hinde.bouziane@umontpellier.fr', '$2y$10$TraQzPAFskcMqp4zqWekXOBQoVqDt5emHWrnIOVpY3ZITlWduz2YK', 'Hinde Bouziane', NULL);
INSERT INTO user VALUES(2010, 'sylvain.daude@umontpellier.fr', '$2y$10$TraQzPAFskcMqp4zqWekXOBQoVqDt5emHWrnIOVpY3ZITlWduz2YK', 'Sylvain Daude', NULL);

DELETE FROM player WHERE id IN (1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010);

INSERT INTO player VALUES(1001, 'Le pro des échecs rien ne l arrête', 2001);
INSERT INTO player VALUES(1002, 'Jeu de go à gogo', 2002);
INSERT INTO player VALUES(1003, 'Manager des meilleures team !', 2003);
INSERT INTO player VALUES(1004, 'Un vrai glouton', 2004);
INSERT INTO player VALUES(1005, 'La récursion ou rien', 2005);
INSERT INTO player VALUES(1006, 'Aime danser la java', 2006);
INSERT INTO player VALUES(1007, 'Tout est logique rien n est étonnant', 2007);
INSERT INTO player VALUES(1008, 'Bonjour à tous !', 2008);
INSERT INTO player VALUES(1009, 'Je suis ici pour me faire un réseau', 2009);
INSERT INTO player VALUES(1010, 'Aime les algos rigolos', 2010);

DELETE FROM user_role WHERE user_id IN (2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010);

INSERT INTO user_role VALUES(2001, 4);
INSERT INTO user_role VALUES(2002, 4);
INSERT INTO user_role VALUES(2003, 4);
INSERT INTO user_role VALUES(2004, 4);
INSERT INTO user_role VALUES(2005, 4);
INSERT INTO user_role VALUES(2006, 4);
INSERT INTO user_role VALUES(2007, 4);
INSERT INTO user_role VALUES(2008, 4);
INSERT INTO user_role VALUES(2009, 4);
INSERT INTO user_role VALUES(2010, 4);

-- Villes et location --

DELETE FROM city WHERE id IN (1001, 1002);

INSERT INTO city VALUES(1001, 'Montpellier');
INSERT INTO city VALUES(1002, 'Pignan');

DELETE FROM zip_code WHERE id IN (1001, 1002, 1003, 1004);

INSERT INTO zip_code VALUES(1001, '34000', 1001);
INSERT INTO zip_code VALUES(1002, '34080', 1001);
INSERT INTO zip_code VALUES(1003, '34090', 1001);
INSERT INTO zip_code VALUES(1004, '34570', 1002);

DELETE FROM location WHERE id IN (1001);

INSERT INTO location VALUES(1001, '839 Rue du Truel', NULL, 1003);

-- Équipes --

DELETE FROM contact WHERE id IN (1001);

INSERT INTO contact VALUES(1001, '0467144170', 'contact@contact.com', 1001);

DELETE FROM team WHERE id IN (1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010);

INSERT INTO team VALUES(1001, 'Les algoriens', 1, 'teams/team-1.png', 1, 1008, 1001);
INSERT INTO team VALUES(1002, 'Les webies', 1, 'teams/team-2.png', 1, 1003, 1001);
INSERT INTO team VALUES(1003, 'Female power', 1, 'teams/team-3.png', 1, 1007, 1001);
INSERT INTO team VALUES(1004, 'Les logisticiens', 1, 'teams/team-4.png', 1, 1006, 1001);
INSERT INTO team VALUES(1005, 'Team 5', 5, 'teams/team-5.png', 1, 1001, 1001);
INSERT INTO team VALUES(1006, 'Team 6', 6, 'teams/team-6.png', 1, 1002, 1001);
INSERT INTO team VALUES(1007, 'Team 7', 7, 'teams/team-7.png', 1, 1003, 1001);
INSERT INTO team VALUES(1008, 'Team 8', 8, NULL, 1, 1004, 1001);
INSERT INTO team VALUES(1009, 'Team 9', 9, NULL, 1, 1005, 1001);
INSERT INTO team VALUES(1010, 'Team 10', 10, NULL, 1, 1006, 1001);

DELETE FROM player_team WHERE team_id IN (1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010);

INSERT INTO player_team VALUES(1001, 1004, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1001, 1005, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1001, 1008, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1001, 1010, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1002, 1001, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1002, 1002, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1002, 1003, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1003, 1006, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1003, 1007, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1003, 1009, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1004, 1003, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1004, 1006, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1004, 1007, '2021-03-14 00:00:00', NULL);

INSERT INTO player_team VALUES(1005, 1001, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1006, 1002, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1007, 1003, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1008, 1004, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1009, 1005, '2021-03-14 00:00:00', NULL);
INSERT INTO player_team VALUES(1010, 1006, '2021-03-14 00:00:00', NULL);

-- Postulat --

DELETE FROM postulate_team WHERE team_id IN (1001, 1002, 1003, 1004);

INSERT INTO postulate_team VALUES(1001, 1004, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_team VALUES(1001, 1005, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_team VALUES(1001, 1010, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_team VALUES(1001, 1007, '2021-03-27 00:00:00', 'pending');
INSERT INTO postulate_team VALUES(1001, 1003, '2021-03-27 00:00:00', 'pending');
INSERT INTO postulate_team VALUES(1001, 1009, '2021-03-27 00:00:00', 'refused');
INSERT INTO postulate_team VALUES(1001, 1001, '2021-03-26 00:00:00', 'accepted');
INSERT INTO postulate_team VALUES(1001, 1001, '2021-03-27 00:00:00', 'refused');
INSERT INTO postulate_team VALUES(1001, 1006, '2021-03-26 00:00:00', 'refused');
INSERT INTO postulate_team VALUES(1001, 1006, '2021-03-27 00:00:00', 'pending');
INSERT INTO postulate_team VALUES(1001, 1002, '2021-03-26 00:00:00', 'accepted');
INSERT INTO postulate_team VALUES(1001, 1002, '2021-03-27 00:00:00', 'pending');

-- Tournois --

INSERT INTO user_role VALUES(2001, 2);
INSERT INTO user_role VALUES(2002, 2);

DELETE FROM tournament WHERE id IN (1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010, 1011, 1012, 1013, 1014, 1015);

INSERT INTO tournament VALUES(1001, 'Tournoi no. 1001', '2021-03-27', '2021-03-26', 14, 2002, 1001, 1);
INSERT INTO tournament VALUES(1002, 'Tournoi no. 1002', '2021-04-09', '2021-04-08', 7, 2001, 1001, 1);
INSERT INTO tournament VALUES(1003, 'Tournoi no. 1003', '2021-04-09', '2021-04-08', 21, 2001, 1001, 1);
INSERT INTO tournament VALUES(1004, 'Tournoi no. 1004', '2021-04-12', '2021-04-11', 14, 2001, 1001, 1);
INSERT INTO tournament VALUES(1005, 'Tournoi no. 1005', '2021-04-18', '2021-04-17', 3, 2001, 1001, 1);
INSERT INTO tournament VALUES(1006, 'Tournoi no. 1006', '2021-04-24', '2021-04-23', 7, 2001, 1001, 1);
INSERT INTO tournament VALUES(1007, 'Tournoi no. 1007', '2021-05-01', '2021-04-30', 14, 2001, 1001, 1);
INSERT INTO tournament VALUES(1008, 'Tournoi no. 1008', '2021-05-01', '2021-04-30', 7, 2001, 1001, 1);
INSERT INTO tournament VALUES(1009, 'Tournoi no. 1009', '2021-05-01', '2021-04-30', 12, 2002, 1001, 1);
INSERT INTO tournament VALUES(1010, 'Tournoi no. 1010', '2021-05-10', '2021-05-09', 7, 2001, 1001, 1);
INSERT INTO tournament VALUES(1011, 'Tournoi no. 1011', '2021-05-12', '2021-05-11', 5, 2001, 1001, 1);
INSERT INTO tournament VALUES(1012, 'Tournoi no. 1012', '2021-05-18', '2021-04-15', 14, 2001, 1001, 1);
INSERT INTO tournament VALUES(1013, 'Tournoi no. 1013', '2021-05-22', '2021-05-21', 7, 2002, 1001, 1);
INSERT INTO tournament VALUES(1014, 'Tournoi no. 1014', '2021-05-30', '2021-05-29', 4, 2001, 1001, 1);
INSERT INTO tournament VALUES(1015, 'Tournoi no. 1015', '2021-06-01', '2021-05-29', 28, 2002, 1001, 1);

-- Préinscriptions --

DELETE FROM postulate_tournament WHERE tournament_id IN (1013, 1015, 1012, 1001);

INSERT INTO postulate_tournament VALUES(1001, 1015, '2021-03-27 00:00:00', 'pending');
INSERT INTO postulate_tournament VALUES(1002, 1015, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1003, 1015, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1004, 1015, '2021-03-27 00:00:00', 'pending');
INSERT INTO postulate_tournament VALUES(1001, 1013, '2021-03-27 00:00:00', 'pending');
INSERT INTO postulate_tournament VALUES(1002, 1013, '2021-03-27 00:00:00', 'refused');
INSERT INTO postulate_tournament VALUES(1003, 1013, '2021-03-26 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1004, 1013, '2021-03-27 00:00:00', 'refused');

INSERT INTO postulate_tournament VALUES(1001, 1012, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1002, 1012, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1003, 1012, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1004, 1012, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1005, 1012, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1006, 1012, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1007, 1012, '2021-03-27 00:00:00', 'accepted');

INSERT INTO postulate_tournament VALUES(1001, 1001, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1002, 1001, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1003, 1001, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1004, 1001, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1005, 1001, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1006, 1001, '2021-03-27 00:00:00', 'accepted');
INSERT INTO postulate_tournament VALUES(1007, 1001, '2021-03-27 00:00:00', 'accepted');

-- Résultat --

DELETE FROM team_match WHERE tournament_id IN (1001);

INSERT INTO team_match VALUES(1001, 1004, 1001, 1001, '2021-03-27 12:00:00', '1/7', '1005-1002');
INSERT INTO team_match VALUES(1002, 1001, 1005, 1001, '2021-03-27 12:00:00', '1/0', '1004-1003');
INSERT INTO team_match VALUES(1003, 1005, 1006, 1001, '2021-03-27 12:00:00', '2/0', NULL);
INSERT INTO team_match VALUES(1004, 1002, 1001, 1001, '2021-03-27 12:00:00', '1/3', NULL);
INSERT INTO team_match VALUES(1005, 1004, 1007, 1001, '2021-03-27 12:00:00', '2/1', '1006');
INSERT INTO team_match VALUES(1006, 1004, 1003, 1001, '2021-03-27 12:00:00', '2/1', NULL);