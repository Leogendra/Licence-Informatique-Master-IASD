-- 1
BEGIN DBMS_OUTPUT.PUT_LINE('-- 1.2.1'); END;
/

DROP TABLE test;
CREATE TABLE test(
    num char(3) CONSTRAINT chk_num_domain CHECK (num >= 0 AND num <= 999),
    commentaire char(97)
);

ANALYZE TABLE test COMPUTE STATISTICS;

-- Nombre de blocs utilisés par la table
SELECT BLOCKS FROM USER_TABLES WHERE TABLE_NAME = 'TEST';
-- Nombre de blocs alloués mais non utilisés par la table
SELECT EMPTY_BLOCKS FROM USER_TABLES WHERE TABLE_NAME = 'TEST';

exec remplissage(1,50);
ANALYZE TABLE test COMPUTE STATISTICS;
SELECT BLOCKS FROM USER_TABLES WHERE TABLE_NAME = 'TEST';
SELECT EMPTY_BLOCKS FROM USER_TABLES WHERE TABLE_NAME = 'TEST';

exec remplissage(1,100);
ANALYZE TABLE test COMPUTE STATISTICS;
SELECT BLOCKS FROM USER_TABLES WHERE TABLE_NAME = 'TEST';
SELECT EMPTY_BLOCKS FROM USER_TABLES WHERE TABLE_NAME = 'TEST';

exec remplissage(1,100);
ANALYZE TABLE test COMPUTE STATISTICS;
SELECT BLOCKS FROM USER_TABLES WHERE TABLE_NAME = 'TEST';
SELECT EMPTY_BLOCKS FROM USER_TABLES WHERE TABLE_NAME = 'TEST';

exec remplissage(1,100);
ANALYZE TABLE test COMPUTE STATISTICS;
SELECT BLOCKS FROM USER_TABLES WHERE TABLE_NAME = 'TEST';
SELECT EMPTY_BLOCKS FROM USER_TABLES WHERE TABLE_NAME = 'TEST';