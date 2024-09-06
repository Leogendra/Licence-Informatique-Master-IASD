--Vous testerez les séquences  suivantes et vous expliquerez les effets obtenus

-- une seule session
-- SEQUENCE 1
set transaction read only;
-- la transaction ne prend en charge que des lectures
select * from cat;
update emp set fonction='commerciale' where fonction='commercial';
rollback;
--> expliquer : la table est en readonly du coup ça update pas 🙂

--SEQUENCE 2
select * from emp;
set transaction read only;
rollback;
--> expliquer : pas de problèmes ici

--SEQUENCE 3
select * from emp for update;
set transaction read only;
rollback;
--> expliquer : la première ligne vérouille emp pour des modification, donc pas read-only après


--SEQUENCE 4
set transaction read only;
create table PrVoir (valeur integer primary key);
update emp set fonction='commerciale' where fonction='commercial';
rollback;
--> expliquer : un COMMIT est exécuté AVANT et APRES le create (et tous les ordres de LDD), ce qui annule le read-only. le rollback est alors effectif que pour le update

--SEQUENCE 5
insert into PrVoir values (1);
insert into PrVoir values (2);
insert into PrVoir values (3);
insert into PrVoir values (4);
insert into PrVoir values (5);
create table EncorePrVoir (valeur integer cle primaire);
rollback;
select * from PrVoir;
--> expliquer : la création de table n'est pas reconnue (car erreur), les COMMIT ne sont donc pas exécutés


--SEQUENCE 6
insert into PrVoir values (1);
insert into PrVoir values (2);
insert into PrVoir values (3);
insert into PrVoir values (4);
insert into PrVoir values (5);
create table EncorePrVoir (valeur integer primary key);
rollback;
select * from PrVoir;
--> expliquer : comme le create de ENcorePrVoir déclenche un COMMIT, les modifs sont enregistrés même après le rollback

--SEQUENCE 7
delete from PrVoir;
create table CorePrVoir (valeur integer primary key);
insert into PrVoir values (1);
insert into PrVoir values (2);
insert into PrVoir values (3);
insert into PrVoir values (4);
insert into PrVoir values (5);
rollback; 
select * from PrVoir;
--> expliquer : le create est en premier, donc pas de commit, le rollback est bien effectif

--SEQUENCE 8
insert into PrVoir values (1);
create table CorePrVoir (valeur integre);
rollback;
select * from PrVoir;
--> expliquer : le commit a bien eu lieu car l'erreur n'est pas trop grosse

--SEQUENCE 9
insert into PrVoir values (6);
create table EncorePrVoir (valeur integer primary key);
rollback;
select * from PrVoir;
--> expliquer : là la valeur est bien ajoutée, pas de rollback


--SEQUENCE 10
insert into PrVoir values (7);
insert into PrVoir values (8);
alter table EncorePrVoir add definition varchar(10);
rollback;
select * from PrVoir;
--> expliquer : le alter est dans LDD, donc commit


--SEQUENCE 11
insert into PrVoir values (9);
insert into PrVoir values (10);
drop table EncorePrVoir ;
rollback;
select * from PrVoir;
--> expliquer : le drop fait également un COMMIT


--SEQUENCE 12
delete from PrVoir where valeur in (6,8);
update PrVoir set valeur=12 where valeur = 10;
insert into PrVoir values (14);
rollback;
select * from PrVoir;
--> expliquer : le rollback annule bien les modification de l'update et de l'insert



-- deux sessions ouvertes par ex. user1/user1 et user2/user2 sur master (travaillez en binôme)
/*
user1 : E20190002767
user2 : E20190014669
*/
-- SEQUENCE 13 - visualisation des transactions seulement quand elles sont validees
-- sur user2 : donner tous les droits a user1 sur la table emp :
GRANT ALL PRIVILEGES ON emp TO E20190014669;

--sur user2 : insérer un tuple dans la table emp (de user2) d''un nouvel employé de numéro 101
insert into emp values (101,'Petit','Jean','ingenieur',20000,null,'10/01/84',3,1);

-- sur user1 : consulter la table user2.emp 
SELECT * FROM E20190014669.emp
ORDER BY num;

--sur user1, constatation : rien n'a changé

-- sur user2, constatation : la ligne à été ajoutée
-- sur user2, valider la transaction

commit;

-- sur user1, consulter la table user2.emp --> constatation ? là c'est bon je vois

-- sur user2, mettre à jour le tuple de num 101
update emp set salaire =1000 where num=101;

-- sur user1
-- essayer de mettre à jour le même tuple
update E20190014669.emp set salaire=2000 where num=101;
--> constatation ? le terminal se bloque en attendant d'avoir accès a la table

-- sur user2, valider la transaction
commit;
-- sur user1, --> constatation ? après l'update est bien envoyée !


-- SEQUENCE 14
-- sur user2, donner tous les droits a user1 sur la table emp
GRANT ALL PRIVILEGES ON emp TO E20190014669;

-- sur user1
set transaction isolation level serializable;
--sur user2, insérer un tuple de num 102 dans la table emp (de user2)

-- sur user1, consulter la table 
SELECT * FROM E20190014669.emp ;
-- constatation ? je vois pas la ligne

-- sur user2, valider la transaction
commit;

-- sur user1, consulter la table user2.emp 
SELECT * FROM E20190014669.emp ;
-- constatation ? quel aurait été le résultat avec un mode transactionnel read committed ? on voit que j'ai pas accès à la table mise à jour

-- sur user2, mettre à jour un tuple 
update emp set salaire=1000 where num=102;

-- sur user1, essayer de mettre à jour le même tuple
update E20190014669.emp set salaire=2000 where num=102;
-- constatation ? user1 est bloqué, et lors du commit il a une erreur de serialisation

-- sur user2, valider la transaction
-- sur user1, constatation ? quel aurait été le résultat avec un mode transactionnel read committed ?


-- SEQUENCE 15
-- interblocage
-- deux sessions ouvertes en mode read committed
-- sur user2 
-- mettre à jour un tuple 
update emp set salaire =4000 where num=102;

-- sur user1
-- essayer de mettre à jour un autre tuple
update user2.emp set salaire =6000 where num=101;

-- sur user2 
-- mettre à jour le tuple verrouillé par user 1 
update emp set salaire =4000 where num=101;

-- sur user1
-- mettre à jour le tuple verrouillé par user 2
update user2.emp set salaire =6000 where num=102;

--constatation ? comment sortir de l'interblocage et quelle est l'information perdue
--quel aurait été le résultat avec un mode transactionnel serializable ?


