create table REGION_OPTI as select * from P00000009432.REGION ;
-- ne pas oublier de rajouter la contrainte de cle primaire et donc d’index unique
alter table REGION_OPTI add constraint REGIONPK primary key(numreg) ;