
drop table abc;
create table ABC (
    A number, 
    B varchar(20), 
    C varchar(20)
); 


declare i number;
begin 
    for i in 1..1000000
    loop 
        insert into abc values (i,dbms_random.string('L', 20),dbms_random.string('U', 20)) ;
    end loop ; 
    commit ;
end ; 
/

alter table abc add constraint abc_pk primary key (a);