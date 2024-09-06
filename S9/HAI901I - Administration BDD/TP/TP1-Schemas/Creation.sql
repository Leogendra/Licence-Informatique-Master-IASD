--SCRIPT de creation et remplissage de tables

ALTER SESSION SET NLS_LANGUAGE = AMERICAN;
ALTER SESSION SET NLS_TERRITORY = AMERICA;

DROP TABLE EMP;
DROP TABLE DEPT;

CREATE TABLE EMP
       (NOM VARCHAR2(10),
	NUM NUMBER(5),
        FONCTION VARCHAR2(15),
        N_SUP NUMBER(5),
        EMBAUCHE DATE,
        SALAIRE NUMBER(7,2),
        COMM NUMBER(7,2),
        N_DEPT NUMBER(3));    

INSERT INTO EMP VALUES
        ('MARTIN',16712,'directeur',25717,'23-may-90',20000,NULL,30);
INSERT INTO EMP VALUES
        ('DUPONT',17574,'administratif',16712,'03-may-05',2000,NULL,30);
INSERT INTO EMP VALUES
        ('DUPOND',26691,'commercial',27047,'04-apr-08',2500,2500,20);
INSERT INTO EMP VALUES
        ('LAMBERT',25012,'administratif',27047,'14-apr-91',2200,NULL,20);
INSERT INTO EMP VALUES
        ('JOUBERT',25717,'president',NULL,'10-oct-92',30000,NULL,30);
INSERT INTO EMP VALUES
        ('LEBRETON',16034,'commercial',27047,'01-jun-99',3000,0,20);
INSERT INTO EMP VALUES
        ('MARTIN',17147,'commercial',27047,'10-dec-73',1500,500,20);
INSERT INTO EMP VALUES
        ('PAQUEL',27546,'commercial',27047,'03-sep-93',2000,300,20);
INSERT INTO EMP VALUES
        ('LEFEBVRE',25935,'commercial',27047,'11-jan-04',2300,100,20);
INSERT INTO EMP VALUES
        ('GARDARIN',15155,'ingenieur',24533,'22-mar-85',2400,NULL,10);
INSERT INTO EMP VALUES
        ('SIMON',26834,'ingenieur',24533,'04-oct-88',2000,NULL,10);
INSERT INTO EMP VALUES
        ('DELOBEL',16278,'ingenieur',24533,'16-nov-94',2000,NULL,10);
INSERT INTO EMP VALUES
        ('ADIBA',25067,'ingenieur',24533,'05-oct-97',3000,NULL,10);
INSERT INTO EMP VALUES
        ('CODD',24533,'directeur',25717,'12-sep-75',5500,NULL,10);
INSERT INTO EMP VALUES
        ('LAMERE',27047,'directeur',25717,'07-sep-99',4500,NULL,20);
INSERT INTO EMP VALUES
	('BALIN',17232,'administratif',24533,'03-oct-97',1300,NULL,10);
INSERT INTO EMP VALUES
	('BARA',24831,'administratif', 16712,'10-sep-08',1500,NULL,30);



CREATE TABLE DEPT
       (N_DEPT NUMBER(3),
        NOM VARCHAR2(14),
        LIEU VARCHAR2(13) );



INSERT INTO DEPT VALUES
        (10,'recherche','Rennes');
INSERT INTO DEPT VALUES (20,'vente','Metz');
INSERT INTO DEPT VALUES
        (30,'direction','Gif');
INSERT INTO DEPT VALUES
        (40,'fabrication','Toulon');

alter table emp add constraint emp_pk
        primary key (num);
alter table dept add constraint dept_pk
        primary key (n_dept);
alter table emp add constraint emp_fk1
        foreign key (n_sup) 
        references emp(num) 
        on delete cascade;
alter table emp add constraint emp_fk2
        foreign key (n_dept) 
        references dept(n_dept) 
        on delete cascade;


DROP TABLE Historique;

CREATE TABLE Historique (
    dateOperation date,
    nomUsager VARCHAR2(50),
    typeOperation VARCHAR2(50)
);

COMMIT;

--