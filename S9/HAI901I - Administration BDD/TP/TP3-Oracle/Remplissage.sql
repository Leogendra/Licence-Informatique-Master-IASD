create or replace procedure remplissage (borneInf in number, borneSup in number) is
    i number;
    comm char(97);
begin
    comm := 'cot_';
    for i in borneInf .. borneSup
        loop
        comm := dbms_random.value || i ;
        insert into test values (i,comm);
        end loop;
    DBMS_OUTPUT.PUT_LINE((borneSup - borneInf + 1) || 'lignes inserees');
    commit;
    exception
        when others then dbms_output.put_line(SQLCODE||'  '||SQLERRM);
end;
/