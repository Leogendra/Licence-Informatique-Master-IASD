-- Nom de l'hote
SELECT HOST_NAME
FROM V$INSTANCE;


-- QUand l'instance est démarée
SELECT TO_CHAR(STARTUP_TIME, 'DD-MON-YYYY HH24:MI:SS') AS DEMARRAGE
FROM V$INSTANCE;


-- 3.1
-- SELECT * FROM V$SGA;
-- SELECT * FROM V$SGAINFO;

-- 1. taille (en Mo) allouée à la mémoire partagée (shared pool)
SELECT ROUND(bytes / 1000 / 1000, 1) AS "Taille Shared Pool (Mo)"
FROM V$SGAINFO
WHERE name = 'Shared Pool Size';

-- 2. taille (en Mo) allouée au tampon de données (data buffer cache)
SELECT ROUND(bytes / 1000 / 1000, 1) AS "Taille Data Buffer Cache (Mo)"
FROM V$SGAINFO
WHERE name = 'Buffer Cache Size';

-- 3. taille (en Mo) allouée au tampon de journalisation (redo log buffer)
SELECT ROUND(bytes / 1000 / 1000, 1) AS "Taille Redo Log Buffer (Mo)"
FROM V$SGAINFO
WHERE name = 'Redo Buffers';

-- 4. taille totale (en Go) allouée à la SGA
SELECT ROUND(SUM(value) / 1000 / 1000 / 1000, 2) AS "Taille allouee a la SGA (Go)"
FROM V$SGA;

-- 3.2.
-- SELECT * FROM v$librarycache;
SELECT ROUND(SUM(pins - reloads) / SUM(pins), 5) As "library cache hit ratio"
FROM v$librarycache;


-- 3.2.2
CREATE OR REPLACE PROCEDURE User_Activity(p_user_schema IN VARCHAR2) AS
BEGIN
  -- Afficher les informations sur les accès aux données de l'utilisateur
  FOR rec IN (SELECT sql_id FROM V$SQLAREA WHERE parsing_schema_name = p_user_schema) 
  LOOP
    DBMS_OUTPUT.PUT_LINE('SQL_ID : ' || rec.sql_id);
    
    -- Récupérer le texte SQL
    FOR sql_rec IN (SELECT sql_text FROM V$SQLTEXT WHERE sql_id = rec.sql_id ORDER BY piece) 
    LOOP
      DBMS_OUTPUT.PUT_LINE(sql_rec.sql_text);
    END LOOP;
    
    -- Afficher le plan d'exécution SQL
    FOR plan_rec IN (SELECT * FROM V$SQL_PLAN WHERE sql_id = rec.sql_id) 
    LOOP
      DBMS_OUTPUT.PUT_LINE('Execution Plan : ' || plan_rec.operation || ' - ' || plan_rec.object_name);
    END LOOP;
    
    DBMS_OUTPUT.PUT_LINE('---------------------------------------');
  END LOOP;
EXCEPTION
  WHEN OTHERS THEN
    DBMS_OUTPUT.PUT_LINE('Erreur : ' || SQLERRM);
END User_Activity;
/

BEGIN
  User_Activity('E20190002767');
END;
/

CREATE OR REPLACE PROCEDURE Costly_Cursors AS
BEGIN
  -- Sélectionner les 10 requêtes les plus coûteuses
  FOR rec IN (
    SELECT *
    FROM (
        SELECT sql_id,
            executions,
            sql_text,
            parsing_schema_name,
            elapsed_time/1000000 AS elapsed_seconds,
            cpu_time/1000000 AS cpu_seconds,
            disk_reads,
            (elapsed_time / (executions * 1000000)) AS avg_elapsed_per_execution,
            RANK() OVER (ORDER BY (disk_reads + cpu_time/1000000 + elapsed_time/1000000) DESC) AS ranking
        FROM V$SQLAREA
    )
    WHERE ranking <= 10
  ) 
  LOOP
    DBMS_OUTPUT.PUT_LINE('Nom de l''user : ' || rec.parsing_schema_name);
    DBMS_OUTPUT.PUT_LINE('SQL_ID : ' || rec.sql_id);
    DBMS_OUTPUT.PUT_LINE('SQL_TEXT : ' || rec.sql_text);
    DBMS_OUTPUT.PUT_LINE('Executions : ' || rec.executions);
    DBMS_OUTPUT.PUT_LINE('Elapsed Time (s) : ' || rec.elapsed_seconds);
    DBMS_OUTPUT.PUT_LINE('CPU Time (s) : ' || rec.cpu_seconds);
    DBMS_OUTPUT.PUT_LINE('Disk Reads : ' || rec.disk_reads);
    DBMS_OUTPUT.PUT_LINE('Moyenne du Elapsed Time par Execution (s) : ' || rec.avg_elapsed_per_execution);
    DBMS_OUTPUT.PUT_LINE('------------------------------------------');
  END LOOP;
END Costly_Cursors;
/

BEGIN
  Costly_Cursors;
END;
/




-- select sql_id, substr(sql_text,1,80) as req, disk_reads from v$sqlarea where
-- parsing_schema_name =user;


-- col req for a200;

-- SELECT parsing_schema_name, sql_id, substr(sql_text,2,200) as req 
-- FROM v$sqlarea 
-- WHERE parsing_schema_name = 'E20190014669'
-- order by first_load_time;

-- select parsing_schema_name, sql_id, substr(sql_text,1,80) as req from v$sqlarea where
-- parsing_schema_name<>'SYS';

-- select to_char(logon_time, 'DD/MM/YYYY HH24:MI:SS') , username, program, sql_text
-- from v$session , v$sqlarea
-- where v$session.sql_address = v$sqlarea.address
-- order by username, program;

-- select r.sql_id, disk_reads, elapsed_time, username from v$sql r, v$session s where
-- s.sql_id = r.sql_id and type='USER';

-- select sql_FullText,(cpu_time/100000) "Cpu Time (s)", 
-- (elapsed_time/1000000) "Elapsed time (s)", 
-- fetches, buffer_gets, disk_reads, executions
-- FROM v$sqlarea 
-- WHERE Parsing_Schema_Name ='P00000009432'
-- AND rownum <50
-- order by 3 desc;