SET SERVEROUTPUT ON;
-- SELECT NUM_ROWS, BLOCKS, EMPTY_BLOCKS, AVG_SPACE, AVG_ROW_LEN
-- FROM USER_TABLES;

-- 1
BEGIN DBMS_OUTPUT.PUT_LINE('-- 1.1.1'); END;
/
SELECT DISTINCT TABLESPACE_NAME
FROM USER_TABLES;

-- 2
BEGIN DBMS_OUTPUT.PUT_LINE('-- 1.1.2'); END;
/
SHOW parameter
SHOW parameter db_block_size

SELECT name, value 
FROM v$parameter 
WHERE name LIKE 'db_block%';

-- 3
BEGIN DBMS_OUTPUT.PUT_LINE('-- 1.1.3'); END;
/
ANALYZE TABLE emp COMPUTE STATISTICS;

SELECT TABLE_NAME, (BLOCKS + EMPTY_BLOCKS) * (SELECT value FROM V$PARAMETER WHERE NAME = 'db_block_size') AS COST_IN_BYTES
FROM USER_TABLES
WHERE TABLE_NAME = 'EMP';