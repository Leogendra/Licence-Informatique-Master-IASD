Question 1 (2 points)
-- Donner les ordres de creation de tables et de contraintes

```sql
CREATE TABLE Comm AS SELECT * FROM P00000009432.Commune;

ALTER TABLE Comm
    ADD CONSTRAINT COMMUNE_PK PRIMARY KEY (CODEINSEE);
```

```sql
CREATE TABLE Dep AS SELECT * FROM P00000009432.Departement;
-- DESC Dep; pour voir le nom des colonnes
ALTER TABLE Dep
    ADD CONSTRAINT DEP_PK PRIMARY KEY (NUMDEP);
```

```sql
ALTER TABLE Comm
    ADD CONSTRAINT FK_COMM_DEP FOREIGN KEY (NUMDEP)
    REFERENCES Dep (NUMDEP);
```

Question 2 (1 point)
-- Donner l'ordre de l'index non unique (de nom numdep_idx) 

CREATE INDEX NUMDEP_IDX ON Comm (NUMDEP);

Question 3 (6 points) requêtes + résultats obtenus et/ou explications

```sql
  -- ANALYZE INDEX NUMDEP_IDX VALIDATE STRUCTURE;
  -- ANALYZE INDEX COMMUNE_PK VALIDATE STRUCTURE;
  -- EXEC DBMS_UTILITY.ANALYZE_SCHEMA(user,'COMPUTE');
INDEX_STATS;
```

-- Vous donnerez les requêtes SQL suivantes ainsi que leurs résultats :
-- Quelle est la hauteur de l'index COMMUNE_PK de la table COMMUNE ? 

```
-- memo :
> DESC INDEX_STATS
 Nom                                       NULL ?   Type
 ----------------------------------------- -------- ----------------------------
 HEIGHT                                             NUMBER
 BLOCKS                                             NUMBER
 NAME                                               VARCHAR2(128)
 PARTITION_NAME                                     VARCHAR2(128)
 LF_ROWS                                            NUMBER
 LF_BLKS                                            NUMBER
 LF_ROWS_LEN                                        NUMBER
 LF_BLK_LEN                                         NUMBER
 BR_ROWS                                            NUMBER
 BR_BLKS                                            NUMBER
 BR_ROWS_LEN                                        NUMBER
 BR_BLK_LEN                                         NUMBER
 DEL_LF_ROWS                                        NUMBER
 DEL_LF_ROWS_LEN                                    NUMBER
 DISTINCT_KEYS                                      NUMBER
 MOST_REPEATED_KEY                                  NUMBER
 BTREE_SPACE                                        NUMBER
 USED_SPACE                                         NUMBER
 PCT_USED                                           NUMBER
 ROWS_PER_KEY                                       NUMBER
 BLKS_GETS_PER_ACCESS                               NUMBER
 PRE_ROWS                                           NUMBER
 PRE_ROWS_LEN                                       NUMBER
 OPT_CMPR_COUNT                                     NUMBER
 OPT_CMPR_PCTSAVE                                   NUMBER
 DEL_LF_CMP_ROWS                                    NUMBER
 PRG_LF_CMP_ROWS                                    NUMBER
 LF_CMP_ROWS                                        NUMBER
 LF_CMP_ROWS_LEN                                    NUMBER
 LF_UNCMP_ROWS                                      NUMBER
 LF_UNCMP_ROWS_LEN                                  NUMBER
 LF_SUF_ROWS_LEN                                    NUMBER
 LF_CMP_ROWS_UNCMP_LEN                              NUMBER
 LF_CMP_RECMP_COUNT                                 NUMBER
 LF_CMP_LOCK_VEC_LEN                                NUMBER
 LF_CMP_BLKS                                        NUMBER
 LF_UNCMP_BLKS                                      NUMBER
```

```sql
SELECT height hauteur 
FROM INDEX_STATS 
WHERE name = 'COMMUNE_PK';
```
```
   HAUTEUR
----------
         2
```

-- Quels sont les nombres de blocs de branches et de feuilles pour l'index COMMUNE_PK  de la table COMMUNE 

```sql
SELECT br_blks blocs_branche, lf_blks blocs_feuilles 
FROM INDEX_STATS 
WHERE name = 'COMMUNE_PK';
```
```
BLOCS_BRANCHE BLOCS_FEUILLES
------------- --------------
            1             79
```

-- Pour cet index, quelle est la taille de chaque tuple présent au niveau des blocs des feuilles ?

```sql
SELECT lf_rows_len taille_tuple_feuille, lf_rows nb_tuple_feuille
FROM INDEX_STATS 
WHERE Name = 'COMMUNE_PK';
```
```
TAILLE_TUPLE_FEUILLE NB_TUPLE_FEUILLE
-------------------- ----------------
              565712            35357
```

-- Par comparaison, quelle est la taille moyenne de chaque tuple de la table COMMUNE et combien de tuples peuvent être stockés dans un bloc (calcul du facteur de blocage de l’espace qui tient compte de l’espace toujours laissé libre, et donc de la valeur de PCT FREE de la vue USER TABLES) ?


```sql
DECLARE
    avg_row_len NUMBER;
    pct_free NUMBER;
    block_size NUMBER;
    avg_tuple_size NUMBER;
    block_factor NUMBER;
BEGIN
    SELECT AVG_ROW_LEN, PCT_FREE, BLOCKS
    INTO avg_row_len, pct_free, block_size
    FROM USER_TABLES
    WHERE TABLE_NAME = 'COMM';
    DBMS_OUTPUT.PUT_LINE('AVG_ROW_LEN : ' || avg_row_len);
    DBMS_OUTPUT.PUT_LINE('PCT_FREE : ' || pct_free || '%');
    DBMS_OUTPUT.PUT_LINE('BLOCK_SIZE : ' || block_size);

    avg_tuple_size := avg_row_len * (100 - pct_free) / 100;

    block_factor := block_size / avg_tuple_size;

    DBMS_OUTPUT.PUT_LINE('Taille moyenne du tuple : ' || ROUND(avg_tuple_size, 2) || ' octets');
    DBMS_OUTPUT.PUT_LINE('Facteur de blocage : ' || ROUND(block_factor, 2));
END;
/
```
```
Taille moyenne du tuple : 47,7 octets
Facteur de blocage : 5,95
```


-- Quelle est la hauteur de la taille de l'index NUMDEP_IDX de la table COMMUNE ? 

```sql
SELECT height hauteur
FROM INDEX_STATS 
WHERE name = 'NUMDEP_IDX';
```
```
   HAUTEUR
----------
         2
```

-- Expliquer ce que renvoient les valeurs des attributs DISTINCT_KEYS et MOST_REPEATED_KEY de la vue INDEX_STATS pour l'index NUMDEP_IDX

```sql
SELECT DISTINCT_KEYS, MOST_REPEATED_KEY
FROM INDEX_STATS 
WHERE name = 'NUMDEP_IDX';
```
Cette vue renvoie que le nombre de clés distinctes dans l'index est 101, et que la clé la plus répéteé à la valeur 891.

Question 4 (5 points) - code PL/SQL et exemple d'utilisation et commentaires

```sql
DECLARE
    object_no integer;
    row_no integer;
    row_id ROWID;

BEGIN
    SELECT ROWID INTO row_id FROM comm
    WHERE codeInsee = '34172';
    object_no := DBMS_ROWID.ROWID_OBJECT(row_id);
    row_no := DBMS_ROWID.ROWID_ROW_NUMBER(row_id);
    DBMS_OUTPUT.PUT_LINE('The obj. # is '||object_no||' '||row_no);
END;
/
```
```sql
SELECT DBMS_ROWID.ROWID_BLOCK_NUMBER(rowid), DBMS_ROWID.ROWID_OBJECT(rowid), nomcommaj
FROM Comm 
WHERE codeInsee = '34172';
```

-- Que renvoie la requête suivante ? `SELECT rowid, rownum, codeinsee FROM comm;`

La requête renvoie les rowid, les numéros de ligne et les codes insee de la table commune.

-- Vous construirez une procédure PL/SQL nommée MEMEBLOCQUE

```sql
CREATE OR REPLACE PROCEDURE MEMEBLOCQUE (codeI IN VARCHAR2) AS
    id_bloc INTEGER;
    id_row ROWID;
BEGIN
    SELECT rowid INTO id_row
    FROM COMM
    WHERE codeInsee = codeI;

    id_bloc := DBMS_ROWID.ROWID_BLOCK_NUMBER(id_row);
    DBMS_OUTPUT.PUT_LINE('Numéro de l''objet: ' || id_bloc);

    DBMS_OUTPUT.PUT_LINE('Liste des communes dans le meme bloc :');
    FOR ligne IN (SELECT codeInsee, nomcommaj
                FROM COMM
                WHERE DBMS_ROWID.ROWID_BLOCK_NUMBER(ROWID) = id_bloc)
    LOOP
        DBMS_OUTPUT.PUT_LINE(ligne.nomcommaj || ', code INSEE: ' || ligne.codeInsee);
    END LOOP;

    EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Le code INSEE n''existe pas');
END MEMEBLOCQUE;
/
```

exemple de requête
```sql
EXEC MEMEBLOCQUE('34172');
```

— Vous construirez une seconde procédure PL/SQL nommée NBRETUPLESPARBLOC

```sql
CREATE OR REPLACE PROCEDURE NBRETUPLESPARBLOC AS
BEGIN
    DBMS_OUTPUT.PUT_LINE('Nombre de tuples par bloc :');
    FOR ligne IN (SELECT DBMS_ROWID.ROWID_BLOCK_NUMBER(ROWID) id_ligne, COUNT(*) nb_tuples
                    FROM COMM
                    GROUP BY DBMS_ROWID.ROWID_BLOCK_NUMBER(ROWID))
    LOOP
        DBMS_OUTPUT.PUT_LINE('Bloc numero ' || ligne.id_ligne || ', nombre de tuples : ' || ligne.nb_tuples);
    END LOOP;
END NBRETUPLESPARBLOC;
/
```
```sql
EXEC NBRETUPLESPARBLOC;
```

Question 3 (6 points) - code PL/SQL et exemple d'utilisation

— Vous construirez une nouvelle procédure PL/SQL nommée BLOCSDUDEPARTEMENT

```sql
CREATE OR REPLACE PROCEDURE BLOCSDUDEPARTEMENT (num_dep IN VARCHAR2) AS
BEGIN
    DBMS_OUTPUT.PUT_LINE('Liste des blocs du departement ' || num_dep || ' :');
    FOR ligne IN (SELECT DISTINCT DBMS_ROWID.ROWID_BLOCK_NUMBER(ROWID) id
                    FROM COMM
                    WHERE numdep = num_dep)
    LOOP
        DBMS_OUTPUT.PUT_LINE('Bloc numero ' || ligne.id);
    END LOOP;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Le departement ' || num_dep || ' n''existe pas');
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Erreur inconnue');
END BLOCSDUDEPARTEMENT;
/
```
```sql
EXEC BLOCSDUDEPARTEMENT('34');
```

-- Vous construirez une dernière procédure PL/SQL (nommée DANSCACHE) qui renvoie le
numéro des différents blocs (id) de COMMUNE, ainsi que leurs enregistrements (juste co-
deInsee, nom com) quand des copies de ces blocs sont présents dans le cache de données (il
vous faudra faire appel à la vue v$bh).


j'arrive pas :(