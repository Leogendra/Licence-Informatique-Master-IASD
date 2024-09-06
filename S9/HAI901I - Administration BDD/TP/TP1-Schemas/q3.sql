CREATE OR REPLACE TRIGGER rennesMille
BEFORE INSERT OR UPDATE OR DELETE ON Emp
FOR EACH ROW
DECLARE
    nom_ville Dept.lieu%TYPE;
BEGIN
    SELECT lieu INTO nom_ville
    FROM Dept
    WHERE :NEW.n_dept = Dept.n_dept;

    IF (:NEW.salaire < 1000 AND nom_ville = 'Rennes') 
    THEN raise_application_error(-20100, 'Le salaire de '|| :NEW.nom ||' est inferieur a 1000 dollars');
    END IF;
END;
/
DROP TRIGGER rennesMille;


CREATE OR REPLACE PROCEDURE JoursEtHeuresOuvrables
IS
BEGIN
    IF (to_char(sysdate,'DY')='SAT') OR
        (to_char(sysdate,'DY')='SUN')
    THEN RAISE_APPLICATION_ERROR(-20001, 'C''est le week-end, pas de travail');
    ELSE DBMS_OUTPUT.PUT_LINE('It''s ' || to_char(sysdate,'DAY'));
    END IF;
END;
/


CREATE OR REPLACE TRIGGER ouvrable
BEFORE INSERT OR UPDATE OR DELETE ON Emp
FOR EACH ROW
BEGIN
    JoursEtHeuresOuvrables;
END;
/


CREATE OR REPLACE TRIGGER monitor
BEFORE INSERT OR UPDATE OR DELETE ON Dept
FOR EACH ROW
DECLARE
    typeOpe VARCHAR2(50);
BEGIN
    IF INSERTING THEN
        typeOpe := 'INSERT';
    ELSIF UPDATING THEN
        typeOpe := 'UPDATE';
    ELSIF DELETING THEN
        typeOpe := 'DELETE';
    END IF;

    INSERT INTO Historique (dateOperation, nomUsager, typeOperation)
    VALUES (SYSDATE, USER, typeOpe);
END;
/



CREATE OR REPLACE TRIGGER cascade
AFTER DELETE OR UPDATE ON Dept
FOR EACH ROW
BEGIN
    IF DELETING THEN
        DELETE FROM Emp WHERE n_dept = :old.n_dept;
    ELSIF UPDATING THEN
        UPDATE Emp SET n_dept = :new.n_dept WHERE n_dept = :old.n_dept;
    END IF;
END;
/
