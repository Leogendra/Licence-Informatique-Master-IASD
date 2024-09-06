set serveroutput on

CREATE OR REPLACE PROCEDURE employes_du_departement (departement_id IN NUMBER, employes OUT VARCHAR2) AS
CURSOR employes_cur IS (SELECT num, nom, salaire, fonction FROM emp WHERE n_dept=numDep);
BEGIN
    employes := '';
    FOR employes_rec IN employes_cur LOOP
        employes := employes || employes_rec.num || ' ' || employes_rec.nom || ', ';
    END LOOP;
    IF employes = '' 
        THEN RAISE vide;
    END IF;
    
    EXCEPTION
    WHEN vide 
        THEN dbms_output.put_line('Pas de num correspondant');
    WHEN others 
        THEN dbms_output.put_line('survenue du pb suivant '||SQLERRM);
END;
/

declare
employes varchar(1000);
begin
    EmployesDuDepartement(10,employes);
    dbms_output.put_line(employes);
end;
/

variable employes varchar(1000);
variable n_dept integer ;
execute :n_dept := 10;
exec EmployesDuDepartement(:n_dept, employees)

declare
employes varchar(1000);
begin
    EmployesDuDepartement(2,employes);
    dbms_output.put_line(employes);
end;
/