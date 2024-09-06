BEGIN
EXECUTE IMMEDIATE 'DROP TABLE PHOTO';
EXCEPTION
 WHEN OTHERS THEN
    IF SQLCODE != -942 THEN
    RAISE;
    END IF;
END;
/
BEGIN
EXECUTE IMMEDIATE 'DROP TABLE LICENCE';
EXCEPTION
 WHEN OTHERS THEN
    IF SQLCODE != -942 THEN
    RAISE;
    END IF;
END;
/
BEGIN
EXECUTE IMMEDIATE 'DROP TABLE CONFIGURATION';
EXCEPTION
 WHEN OTHERS THEN
    IF SQLCODE != -942 THEN
    RAISE;
    END IF;
END;
/
BEGIN
EXECUTE IMMEDIATE 'DROP TABLE APPAREIL_PHOTO';
EXCEPTION
 WHEN OTHERS THEN
    IF SQLCODE != -942 THEN
    RAISE;
    END IF;
END;
/
BEGIN
EXECUTE IMMEDIATE 'DROP TABLE UTILISATEUR';
EXCEPTION
 WHEN OTHERS THEN
    IF SQLCODE != -942 THEN
    RAISE;
    END IF;
END;
/



CREATE TABLE UTILISATEUR (
    id_use NUMBER,
    nom_user VARCHAR2(50),
    PRIMARY KEY (id_use)
);
CREATE TABLE APPAREIL_PHOTO (
    id_app NUMBER,
    marque VARCHAR2(50),
    nom_app VARCHAR2(50),
    PRIMARY KEY (id_app)
);
CREATE TABLE Configuration (
    id_conf NUMBER,
    ouverture_focale NUMBER(3,2),
    temps_exposition NUMBER(3,2),
    distance_focale NUMBER,
    flash VARCHAR2(50),
    appareil_id NUMBER,
    PRIMARY KEY (id_conf),
    FOREIGN KEY (appareil_id)
        REFERENCES APPAREIL_PHOTO(id_app) ON DELETE CASCADE
);

CREATE TABLE LICENCE (
    id_licence NUMBER PRIMARY KEY,
    type VARCHAR2(50) NOT NULL,
    CHECK (type IN ('tous droits reserves',
		'utilisation commerciale autorisee',
		'modifications de limage autorisees')
    )
);
CREATE TABLE PHOTO (
    code NUMBER,
    lieu VARCHAR2(200),
    date_photo DATE,
    licence_id NUMBER,
    publicateur_id NUMBER,
    appareil_id NUMBER,
    PRIMARY KEY (code),
    FOREIGN KEY (licence_id)
        REFERENCES LICENCE(id_licence),
    FOREIGN KEY (publicateur_id)
        REFERENCES UTILISATEUR(id_use),
    FOREIGN KEY (appareil_id)
        REFERENCES APPAREIL_PHOTO(id_app)
);



