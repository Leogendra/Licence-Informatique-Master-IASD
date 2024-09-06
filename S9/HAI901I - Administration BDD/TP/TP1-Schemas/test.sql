-- CREATE TABLE EMP
--        (NOM VARCHAR2(10),
-- 	       NUM NUMBER(5),
--         FONCTION VARCHAR2(15),
--         N_SUP NUMBER(5),
--         EMBAUCHE DATE,
--         SALAIRE NUMBER(7,2),
--         COMM NUMBER(7,2),
--         N_DEPT NUMBER(3));

-- INSERT INTO EMP 
-- VALUES ('JEAN',00001,'administratif', 16712,'10-sep-08',1500,NULL,30);

-- SELECT emp.nom, dept.lieu FROM emp
-- JOIN dept ON emp.n_dept = dept.n_dept 
-- WHERE emp.n_dept = 30;

-- UPDATE Dept
-- SET Lieu = 'Montpellier'
-- WHERE n_dept = 30;

-- SELECT emp.nom, dept.lieu FROM emp
-- JOIN dept ON emp.n_dept = dept.n_dept 
-- WHERE emp.n_dept = 30;

-- SELECT * FROM Historique;

-- Q4.
CREATE TABLE tableTest(
    quoi VARCHAR2(10)
);



ROLLBACK;