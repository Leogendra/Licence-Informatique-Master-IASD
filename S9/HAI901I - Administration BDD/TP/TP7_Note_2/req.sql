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
