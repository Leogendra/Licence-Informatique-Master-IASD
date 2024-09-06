-- set autotrace on;

-- -- explain plan for select ...

-- set linesize 300;
-- set pagesize 100;
-- select * from table(dbms_xplan.display());


-- 3. SELECT /*+ GATHER PLAN STATISTICS */ . . .

-- SELECT * FROM
-- TABLE(DBMS_XPLAN.display cursor(format=>'ALLSTATS LAST +cost +outline'));


-- query 1 : on selectionne toutes les communes qui sont chef lieu d'un département moins celles qui sont cheflieu d'une region
select nomcommaj, codeinsee from comm, dep
where codeinsee = cheflieu
minus
select nomcommaj, codeinsee from comm, region_opti
where codeinsee = cheflieu
FETCH FIRST 1 ROWS ONLY;

-- query 2 : on selectionne toutes les communes qui sont chef lieu d'un département et pas d'une region 
select nomcommaj, codeinsee from comm where
codeinsee in (select cheflieu from dep)
and codeinsee not in (select cheflieu from region_opti)
FETCH FIRST 2 ROWS ONLY;
-- plus performant : 2 et 3

-- query 3 : on selectionne toutes les communes qui sont dans les cheflieu d'un département mais pas dans le cheflieu d'une region
select nomcommaj, codeinsee from comm where
exists (select null from dep where codeinsee=cheflieu)
and not exists (select null from region_opti where codeinsee=cheflieu)
FETCH FIRST 3 ROWS ONLY;

-- query 4 : on selectionne toutes les communes qui sont chef lieu d'un département mais pas dans les cheflieu d'une region
select nomcommaj, codeinsee from comm, dep
where codeinsee = cheflieu
and codeinsee not in (select cheflieu from region_opti)
FETCH FIRST 4 ROWS ONLY;

--query 5 : on selectionne toutes les communes qui ont un cheflieu d'un département mais pas d'une region
select nomcommaj, codeinsee from comm 
left join dep on codeinsee = cheflieu
where cheflieu is not null
and codeinsee not in (select cheflieu from region_opti)
FETCH FIRST 5 ROWS ONLY;

--query 6 : 
select nomcommaj, codeinsee from comm 
join dep on codeinsee = cheflieu
minus
select nomcommaj, codeinsee from comm 
join region_opti on codeinsee = cheflieu
FETCH FIRST 6 ROWS ONLY;

--query 7
select nomcommaj, codeinsee from comm 
left join dep on codeinsee = cheflieu
where decode(cheflieu,null,'non','oui') = 'oui'
and codeinsee not in (select cheflieu from region_opti)
FETCH FIRST 7 ROWS ONLY;

--query 8
select nomcommaj, codeinsee from comm, (select cheflieu from dep) d
where codeinsee = d.cheflieu
minus
select nomcommaj, codeinsee from comm, (select cheflieu from region_opti) r
where codeinsee = r.cheflieu
FETCH FIRST 8 ROWS ONLY;

/*
SELECT sum(buffer_gets), sum(cpu_time)/1000/1000 temps_ecoule_sec, sum(rows_processed) lignes_lues, plan_hash_value, sum(DIRECT_READS) bytes
FROM v$sql
WHERE plan_hash_value in (3570472645, 1834607502, 1785368984, 2956014282, 1678707384, 643524255)
GROUP BY plan_hash_value;
*/
-- select disk_reads, buffer_gets, cpu_time, rows_processed from v$sql where plan_hash_value=3570472645;
-- select disk_reads, buffer_gets, cpu_time, rows_processed from v$sql where plan_hash_value=1834607502;
-- select disk_reads, buffer_gets, cpu_time, rows_processed from v$sql where plan_hash_value=1785368984;
-- select disk_reads, buffer_gets, cpu_time, rows_processed from v$sql where plan_hash_value=2956014282;
-- select disk_reads, buffer_gets, cpu_time, rows_processed from v$sql where plan_hash_value=1678707384;
-- select disk_reads, buffer_gets, cpu_time, rows_processed from v$sql where plan_hash_value=3570472645;
-- select disk_reads, buffer_gets, cpu_time, rows_processed from v$sql where plan_hash_value=643524255;
-- select disk_reads, buffer_gets, cpu_time, rows_processed from v$sql where plan_hash_value=3570472645;