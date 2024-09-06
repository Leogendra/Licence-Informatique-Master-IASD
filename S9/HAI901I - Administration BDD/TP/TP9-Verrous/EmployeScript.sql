-- si environnement de la session autre que francais
ALTER SESSION SET NLS_LANGUAGE = FRENCH;
ALTER SESSION SET NLS_TERRITORY =FRANCE;


drop table emp cascade constraints;
drop table dep cascade constraints;
drop table fonction cascade constraints;
drop table vente cascade constraints;



prompt table fonction

create table fonction (
nom_f varchar(20) constraint fonction_pk primary key,
salaire_min float,
salaire_max float);

prompt table dep

create table dep (
num_d number constraint departement_pk primary key,
nom_d varchar(22),
adresse varchar(22));

prompt table emp

create table emp (
num number constraint employe_pk primary key,
nom varchar(20),
prenom varchar(15),
fonction varchar(20) constraint job_fk references fonction(nom_f) 
deferrable initially deferred,
salaire float,
commission float,
date_embauche date,
n_sup number constraint employe_sup references emp(num) deferrable initially deferred,
n_dep number constraint dept_fk references dep(num_d) deferrable initially deferred);


create table vente (numV integer constraint vente_pk primary key, article varchar(15), qte integer, dateV date, montant float, numCommercial number constraint vente_commercial references emp(num));


prompt peuplement

insert into fonction values ('president',10000,100000);
insert into fonction values ('directeur',4000,15000);
insert into fonction values ('drh',3000,15000);
insert into fonction values ('chef projet',3000,15000);
insert into fonction values ('commercial',2000,20000);
insert into fonction values ('ingenieur',1500,8000);
insert into fonction values ('dessinateur',1500,5000);
insert into fonction values ('secretaire',1100,2300);
insert into fonction values ('technicien',1000,2500);
insert into fonction values ('agent entretien',900,1500);
insert into fonction values ('designer',2000,10000);
insert into fonction values ('informaticien',4000,5000);
insert into fonction values ('menuisier',1200,5000);
insert into fonction values ('ebeniste',1400,5000);
insert into fonction values ('ouvrier',1000,3000);
insert into fonction values ('manutentionnaire',900,3000);
insert into fonction values ('directeur ventes',3900,6000);
insert into fonction values ('modiste',1900,8000);

insert into dep values (1,'bureau dessin','Montpellier');
insert into dep values (2,'atelier encastillage','La Grande Motte');
insert into dep values (3,'atelier voiles','Palavas');
insert into dep values (4,'menuiserie','Palavas');
insert into dep values (5,'direction','Montpellier');


insert into emp values (1,'Martin','Pierre','dessinateur',2000,null,'10/10/04',3,1);
insert into emp values (2,'Roi','Pierre-Claude','president',20000,null,'20/03/96',null,1);
insert into emp values (3,'Dubois','Eric','chef projet',3000,null,'8/01/06',2,1);
insert into emp values (4,'Amora','Marie','dessinateur',2400,null,'6/10/00',3,1);
insert into emp values (5,'Duhamel','Jerome','technicien',1400,null,'6/6/06',6,4);
insert into emp values (6,'Miller','Tommy','ingenieur',3400,null,'6/9/99',2,4);
insert into emp values (7,'Galfione','Mona','secretaire',1800,null,'16/3/96',2,5);
insert into emp values (8,'Bellini','Ahmed','drh',4000,null,'16/3/96',2,5);
insert into emp values (9,'Zorrima','Lucio','commercial',6000,1200,'16/8/96',2,5);
insert into emp values (10,'Landauer','Max','commercial',8000,300,'16/8/97',2,5);
insert into emp values (11,'Maleon','Stephanie','dessinateur',2500,null,'10/10/02',3,1);
insert into emp values (12,'Marlow','Paul','dessinateur',3500,null,'10/10/99',3,1);
insert into emp values (13,'Marlin','Paulin','commercial',3500,879,'10/07/99',3,1);
insert into emp values (14,'Marlinot','Pauline','directeur',4500,null,'6/07/05',2,1);
insert into emp values (15,'Mertinot','Patricia','secretaire',1500,null,'6/07/01',2,1);
insert into emp values (16,'Mertinotti','Patrizio','secretaire',1800,null,'6/07/00',2,1);
insert into emp values (17,'Molina','Marie','menuisier',2800,null,'6/07/00',2,1);
insert into emp values (18,'Molinaro','Paola','menuisier',2700,null,'6/07/00',2,1);
insert into emp values (19,'Molinari','Columba','modiste',2600,null,'6/07/00',2,1);
insert into emp values (20,'Molinari','Anne','ebeniste',2600,null,'6/07/00',2,1);
insert into emp values (21,'Martin','Annette','agent entretien',1600,null,'6/07/00',2,1);
insert into emp values (22,'Martinet','Janette','technicien',1900,null,'6/07/00',2,1);
insert into emp values (23,'Mougin','Thierry','directeur ventes',4900,null,'6/07/00',2,1);
insert into emp values (24,'Maillard','Claude','informaticien',3900,null,'6/07/00',2,1);
insert into emp values (25,'Mallet','Helene','designer',5900,null,'6/07/00',2,1);
insert into emp values (26,'Magnin','Aurore','designer',2900,null,'6/07/00',2,1);
insert into emp values (27,'Magnine','Elise','designer',3900,null,'6/07/00',2,1);
insert into emp values (28,'Magnanime','Eloi','manutentionnaire',1900,null,'6/07/00',2,1);
insert into emp values (29,'Faure','Etienne','manutentionnaire',2000,null,'6/07/00',2,1);
insert into emp values (30,'Faurez','Antoine','manutentionnaire',2000,null,'6/07/00',2,1);
insert into emp values (31,'Landin','Maxime','modiste',2000,null,'16/8/97',2,3);
insert into emp values (32,'Lipp','Martin','modiste',4000,null,'16/8/97',2,3);
insert into emp values (33,'Landin','Maxime','modiste',2000,null,'16/8/97',2,3);
insert into emp values (34,'Lippiaz','Martine','modiste',4000,null,'16/8/97',2,3);
insert into emp values (35,'Lappalide','Corentine','secretaire',1900,null,'16/3/96',2,3);
insert into emp values (36,'Nonrancer','Noemie','designer',2900,null,'16/3/96',2,3);
insert into emp values (37,'Mercer','Clement','designer',3900,null,'16/3/96',2,3);
insert into emp values (38,'Mercerez','Clemence','designer',3900,null,'16/3/96',2,3);
insert into emp values (39,'Merceriaz','Caroline','designer',3900,null,'16/3/96',2,3);
insert into emp values (40,'Merceran','Chloe','designer',3900,null,'16/3/96',2,3);
insert into emp values (41,'Martin','Pierre','dessinateur',2000,null,'10/10/04',3,1);
insert into emp values (42,'Maleon','Stephanie','dessinateur',2500,null,'10/10/02',3,1);
insert into emp values (43,'Marlow','Paul','dessinateur',3500,null,'10/10/99',3,1);
insert into emp values (44,'Marlin','Paulin','commercial',3500,879,'10/07/99',3,1);
insert into emp values (45,'Marlinot','Pauline','directeur',4500,null,'6/07/05',2,1);
insert into emp values (46,'Mertinot','Patricia','secretaire',1500,null,'6/07/01',2,1);
insert into emp values (47,'Mertinotti','Patrizio','secretaire',1800,null,'6/07/00',2,1);
insert into emp values (48,'Molina','Marie','menuisier',2800,null,'6/07/00',2,1);
insert into emp values (49,'Molinaro','Paola','menuisier',2700,null,'6/07/00',2,1);
insert into emp values (50,'Molinari','Columba','modiste',2600,null,'6/07/00',2,1);
insert into emp values (51,'Molinari','Anne','ebeniste',2600,null,'6/07/00',2,1);
insert into emp values (52,'Martin','Annette','agent entretien',1600,null,'6/07/00',2,1);
insert into emp values (53,'Martinet','Janette','technicien',1900,null,'6/07/00',2,1);
insert into emp values (54,'Mougin','Thierry','directeur ventes',4900,null,'6/07/00',2,1);
insert into emp values (55,'Maillard','Claude','informaticien',3900,null,'6/07/00',2,1);
insert into emp values (56,'Mallet','Helene','designer',5900,null,'6/07/00',2,1);
insert into emp values (57,'Magnin','Aurore','designer',2900,null,'6/07/00',2,1);
insert into emp values (58,'Magnine','Elise','designer',3900,null,'6/07/00',2,1);
insert into emp values (59,'Magnanime','Eloi','manutentionnaire',1900,null,'6/07/00',2,1);
insert into emp values (60,'Faure','Etienne','manutentionnaire',2000,null,'6/07/00',2,1);
insert into emp values (61,'Faurez','Antoine','manutentionnaire',2000,null,'6/07/00',2,1);
insert into emp values (62,'Roi','Pierre-Claude','president',20000,null,'20/03/96',null,1);
insert into emp values (63,'Dubois','Eric','chef projet',3000,null,'8/01/06',2,1);
insert into emp values (64,'Amora','Marie','dessinateur',2400,null,'6/10/00',3,1);
insert into emp values (65,'Duhamel','Jerome','technicien',1400,null,'6/6/06',6,4);
insert into emp values (66,'Miller','Tommy','ingenieur',3400,null,'6/9/99',2,4);
insert into emp values (67,'Galfione','Mona','secretaire',1800,null,'16/3/96',2,5);
insert into emp values (68,'Bellini','Ahmed','drh',4000,null,'16/3/96',2,5);
insert into emp values (69,'Zorrima','Lucio','commercial',6000,1200,'16/8/96',2,5);
insert into emp values (70,'Landauer','Max','commercial',8000,300,'16/8/97',2,5);
insert into emp values (71,'Landin','Maxime','modiste',2000,null,'16/8/97',2,3);
insert into emp values (72,'Lipp','Martin','modiste',4000,null,'16/8/97',2,3);
insert into emp values (73,'Landin','Maxime','modiste',2000,null,'16/8/97',2,3);
insert into emp values (74,'Lippiaz','Martine','modiste',4000,null,'16/8/97',2,3);
insert into emp values (75,'Lappalide','Corentine','secretaire',1900,null,'16/3/96',2,3);
insert into emp values (76,'Nonrancer','Noemie','designer',2900,null,'16/3/96',2,3);
insert into emp values (77,'Mercer','Clement','designer',3900,null,'16/3/96',2,3);
insert into emp values (78,'Mercerez','Clemence','designer',3900,null,'16/3/96',2,3);
insert into emp values (79,'Merceriaz','Caroline','designer',3900,null,'16/3/96',2,3);
insert into emp values (80,'Merceran','Chloe','designer',3900,null,'16/3/96',2,3);
insert into emp values (90,'Slowman','Pierre','drh',2000,null,'10/10/04',3,1);
insert into emp values (91,'Fastman','Pierrette','ouvrier',2010,null,'10/10/84',3,1);
insert into emp values (92,'Greenriver','Manuel','ingenieur',2010,null,'10/01/84',3,1);


insert into vente values (1,'hobby cat',1,sysdate,5000,44);
insert into vente values (2,'10m',1,sysdate,25000,44);
insert into vente values (3,'hobby cat',1,sysdate,5000,9);
insert into vente values (4,'10m',1,sysdate,25000,9);
insert into vente values (5,'hobby cat',1,sysdate,5000,10);
insert into vente values (6,'10m',1,sysdate,25000,10);
insert into vente values (7,'hobby cat',1,sysdate,5000,10);
insert into vente values (8,'10m',1,sysdate,25000,10);


commit;