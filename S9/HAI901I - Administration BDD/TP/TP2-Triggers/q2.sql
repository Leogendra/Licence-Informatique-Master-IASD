-- rec = record
CREATE OR REPLACE PROCEDURE delete_all_triggers AS
cursor trigger_cur IS SELECT trigger_name FROM user_triggers;
BEGIN
    FOR trigger_rec IN trigger_cur LOOP
        EXECUTE IMMEDIATE 'DROP TRIGGER ' || trigger_rec.trigger_name;
    END LOOP;
END;
/

EXECUTE delete_all_triggers;