INSERT INTO Utilisateur VALUES (1, 'Alice');
INSERT INTO Utilisateur VALUES (2, 'Bob');
INSERT INTO Utilisateur VALUES (3, 'Carl');

INSERT INTO Appareil_photo VALUES (1, 'NIXON','B150');
INSERT INTO Appareil_photo VALUES (2, 'Panasonix','Lumix');
INSERT INTO Appareil_photo VALUES (3, 'CANON','EX500');

INSERT INTO Configuration VALUES (1, 1.50,0.01,10,'oui',1);
INSERT INTO Configuration VALUES (2, 1.50,0.01,20,'non',1);
INSERT INTO Configuration VALUES (3, 3.50,0.01,30,'non',2);
INSERT INTO Configuration VALUES (4, 1.05,0.10,50,'non',3);

INSERT INTO Licence VALUES (1, 'tous droits reserves');
INSERT INTO Licence VALUES (2, 'utilisation commerciale autorisee');
INSERT INTO Licence VALUES (3, 'modifications de limage autorisees');

INSERT INTO Photo VALUES (1, 'Montpellier',TO_DATE('01/09/2022','DD/MM/YYYY'),1,1,1);
INSERT INTO Photo VALUES (2, 'Montpellier',TO_DATE('04/09/2022','DD/MM/YYYY'),3,1,2);
INSERT INTO Photo VALUES (3, 'Montpellier',TO_DATE('05/09/2022','DD/MM/YYYY'),3,2,3);
INSERT INTO Photo VALUES (4, 'Montpellier',TO_DATE('06/09/2022','DD/MM/YYYY'),2,3,3);
INSERT INTO Photo VALUES (5, 'Beziers',TO_DATE('03/09/2022','DD/MM/YYYY'),1,1,1);
INSERT INTO Photo VALUES (6, 'Beziers',TO_DATE('05/01/2022','DD/MM/YYYY'),3,2,2);
INSERT INTO Photo VALUES (7, 'Beziers',TO_DATE('03/02/2022','DD/MM/YYYY'),2,3,3);
INSERT INTO Photo VALUES (8, 'Toulouse',TO_DATE('01/09/2022','DD/MM/YYYY'),2,2,3);

