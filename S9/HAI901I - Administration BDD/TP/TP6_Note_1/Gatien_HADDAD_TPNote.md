Exercice 1 : Expliquer tous vos résultats (vues du dictionnaire et requêtes SQL exploitées)


-- Pour la table (1 point par question)
_1. vérifier la taille d'un tuple de table et vérifier la cardinalité de la table (nombre de tuples)_

La taille moyenne d'un tuple est de 50 octets `SELECT avg_row_len FROM ALL_TABLES WHERE TABLE_NAME = 'ABC';`  
La cardinalité de la table est bien de 1 million `select count(*) from abc`

_2. donner le nombre total de blocs alloués à la table ABC en indiquant le nombre de blocs ayant fait l'objet d'écriture et le nombre de blocs vides_

```sql
SELECT TABLE_NAME, BLOCKS, EMPTY_BLOCKS, (BLOCKS + EMPTY_BLOCKS) AS Total_Blocks
FROM USER_TABLES
WHERE TABLE_NAME = 'ABC';
```
```
BLOCKS     EMPTY_BLOCKS TOTAL_BLOCKS
---------- ------------ ------------
7300       124          7424
```

_3. donner le nombre d'extents (et leur taille en blocs) compris dans le segment de table associé à la table ABC_ 

Il y a 73 extents
```sql
SELECT extents 
FROM user_segments 
WHERE segment_name = 'ABC';
```

_4. donner la taille en octets de l'espace de stockage qui a été réservé pour la table ABC_

La taille totale d'octets réservés pour la table ABC est donc de 60'817'408 octets, soit 58Mio

```sql
SELECT SEGMENT_NAME, BYTES/1024/1024 AS taille_Mio
FROM DBA_SEGMENTS
WHERE SEGMENT_NAME = 'ABC'
AND OWNER = 'E20190002767';
```

_5. commenter les résultats obtenus par rapport à ce que vous en saviez après calculs. Est ce cohérent ?_  

En calculant la taille théorique réservée, on obtiens :  
- Taille bloc utilisable : `8192 - 819.2 (10% du bloc réservé pour les données) = 7372 octets utilisables`
- Taille totale des tuples : `50 (taille d'un tuple) * 1'000'000 = 50'000'000 octets`
- Nombre de blocs nécessaires : `50'000'000 / 7372 = 6780.5 blocs`
- Taille totale réservée : `6781 * 8192 = 55'556'352 octets`

On voit donc que la taille réserveé est plus grande que la taille théorique
Cette différence est due au fait que faut prendre en compte les blocs vides aloués en plus, etc.

_6. donnez un exemple d'utilisation d'une vue qui pourrait être consultée pour connaître le nombre de blocs parcourus lors de l'exécution d'une requête (utilisant ou non l'index) ?_

```sql
SELECT sql_text, buffer_gets/executions AS "blocs parcourus"
FROM V$sqlarea
WHERE sql_text LIKE '%ABC%';
```

Dans cette vue on peut récupérer des informations sur les différentes requêtes qui ont été exécutées dans la base de données. Ici on l'utilise pour savoir le nombre de blocs parcourus lors de l'exécution d'une requête


--Pour l'index (1 point par question)
_1. comment savoir si l'index ABC_PK est unique et dense ?_  

unicité de l'index :
```sql
SELECT INDEX_NAME, UNIQUENESS
FROM USER_INDEXES
WHERE INDEX_NAME = 'ABC_PK';
```
```
INDEX_NAME      UNIQUENES 
--------------- ---------
ABC_PK          UNIQUE    
```

densité de l'index : 
```sql
SELECT INDEX_NAME, DISTINCT_KEYS
FROM user_indexes
WHERE INDEX_NAME = 'ABC_PK';
```

L'index est unique et dense car le nombre de clés distinctes est égal au nombre de tuples de la table


_2. donner la taille en octets d'un tuple de branche d'index et la taille d'un tuple de feuille d'index (en expliquant la différence de taille)_  

"""aide
 INDEX_STATS :
— BLOCKS blocs allou ́es au segment d’index
— NAME nom de l’index
— LF_ROWS nombre de tuples feuilles (litt ́eralement leaF ROWS)
— LF_BLKS nombre de blocs feuilles
— LF_ROWS_LEN somme de la taille totale de tous les tuples feuilles en octets
— LF_BLK_LEN espace libre dans les blocs feuilles
— BR_ROWS nombre de tuples branches (litt ́eralement BRanch ROWS)
— BR_BLKS nombre de blocs branches
— BR_ROWS_LEN somme de la taille totale de tous les tuples branches en octets
— BR_BLK_LEN espace libre dans les blocs branches
— DISTINCT_KEYS nombre de valeurs d’entr ́ee (cl ́es) distinctes dans l’arbre
— MOST_REPEATED_KEY nombre de r ́ep ́etitions de valeurs d’entr ́ee (cl ́es) dans l’arbre
"""

```sql
SELECT LF_ROWS_LEN/LF_ROWS AS taille_tuple_feuille, BR_ROWS_LEN/BR_ROWS AS taille_tuple_branche
FROM INDEX_STATS
WHERE NAME = 'ABC_PK';
```
```
TAILLE_TUPLE_FEUILLE TAILLE_TUPLE_BRANCHE
-------------------- --------------------
           14,979802           10,9894586
```

Les tuples de feuilles ont un pointeur vers les tuples de la table, alors que les tuples de branche pointent vers les feuilles d'index

_3. donner le nombre de blocs branche d'index et le nombre de blocs feuille d'index_  

```sql
SELECT BR_BLKS, LF_BLKS
FROM INDEX_STATS
WHERE NAME = 'ABC_PK';
```
```
  BR_BLKS    LF_BLKS
---------- ----------
         4       2088
```


_4. donner le nombre total de blocs alloués à l'index ABC_PK, ainsi que la hauteur de l'index_  

```sql
SELECT NAME, BLOCKS, HEIGHT
FROM INDEX_STATS
WHERE NAME = 'ABC_PK';
```
```
INDEX_NAME     BLOCKS     HEIGHT
-------------- ---------- ----------
ABC_PK         2176       3
```

_5. donner la taille en octets de l'espace de stockage qui a été réservé à l'index ABC\_PK_   

```sql
SELECT NAME, USED_SPACE taille_octets, USED_SPACE/1024/1024 taille_Mio
FROM INDEX_STATS
WHERE NAME = 'ABC_PK';
```
```
NAME      TAILLE_OCTETS TAILLE_MIO
--------- ------------- ----------
ABC_PK    15002737      14,307725
```

_6. commenter les résultats obtenus par rapport à ce que vous en saviez après calculs. Est ce cohérent ?_  

On voit donc que la taille réserveé est plus petite que la taille théorique
Cette différence de quasiment 1Mo (calcul dans le TD) peut être due au fait que oracle optimise la BDD ce qui gagner de la place

_7. donnez un exemple d'utilisation d'une vue qui pourrait être consultée pour savoir si tous les blocs de l'index ABC_PK sont présents dans le cache de données_  

???

Exercice 2 : De manière générale
Question 1  (1/2 point par question) : Pensez vous que l’index est utilisé pour les ordres de consultation suivants ? (justifiez) :
1. `select * from ABC where A = 10001 ;` 

L'index sera utilisé car on filtr sur A

2. `select A from ABC;`

Pas d'index utilisé car on fais juste un SELECT

3. `select A, B from ABC ;`

Pareil, pas d'index car juste un SELECT

4. `select C from ABC where A >=20 AND A <=40`

L'index sera utilisé car on filtre sur la colonne A et on aura donc besoin de l'index pour trouver les tuples correspondants

5. `select * from ABC where C like 'ABC%';`

L'index ne sera *pas* utilisé car on filtre juste sur la colonne C

6. `select A from ABC where A != 10001 ;`

L'index sera utilisé si on filtre sur la colonne A en sélectionnant tous les tuples sauf ceux qui ont A = 10001

Question 2 (4 points)
code de la procédure infosTables (et possiblement du paquetage) 

Construisez une procédure PL/SQL nommée InfosTable qui exploite au moins deux vues du métaschéma parmi lesquelles USER TABLES, USER INDEXES, USER SEGMENTS et USER EXTENTS, qui soit à même de renvoyer les informations les plus importantes concernant l’organisation logique et physique d’une table et de ses index. La définition de la procédure au sein d’un paquetage et la gestion des exceptions pourront faire l’objet d’un point bonus.

```sql
CREATE OR REPLACE PACKAGE infosTables AS
  PROCEDURE infosTable (nomTable VARCHAR2);
END infosTables;
```

```sql
CREATE OR REPLACE PROCEDURE InfosTable(nom_table IN VARCHAR2) IS
    taille_table NUMBER;
    taille_index NUMBER;
BEGIN
    SELECT BYTES INTO taille_table
    FROM USER_SEGMENTS
    WHERE SEGMENT_NAME = nom_table;

    BEGIN
        SELECT BYTES INTO taille_index
        FROM USER_SEGMENTS
        WHERE SEGMENT_NAME = nom_table || '_PK';
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            taille_index := NULL;
    END;

    DBMS_OUTPUT.PUT_LINE('Table ' || nom_table || ', taille de la table : ' || taille_table || ' octets');

    IF taille_index IS NOT NULL THEN
        DBMS_OUTPUT.PUT_LINE('Taille de l''index principal : ' || taille_index || ' octets');
    ELSE
        DBMS_OUTPUT.PUT_LINE('Index principal non trouvé.');
    END IF;

EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Table inexistante ou erreur lors de l''exécution de la procédure.');
END InfosTable;
/
```

en raison d'un manque de temps, la procédure ne peut que récuperer que les infos d'un seul index . Pour gérer les multiples index, il aurait fallu utilisr un cursor