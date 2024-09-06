CREATE OR REPLACE TRIGGER changementModele
AFTER CREATE ON SCHEMA
DECLARE
    name_obj VARCHAR2(100);
    type_obj VARCHAR2(50);
BEGIN
    name_obj := ora_dict_obj_name;
    type_obj := ora_dict_obj_type;
    
    DBMS_OUTPUT.PUT_LINE('Objet cree : ' || name_obj || ' de type ' || type_obj);
END;
/