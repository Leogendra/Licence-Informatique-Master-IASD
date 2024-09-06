CREATE TABLE Lieu (
    id_lieu NUMBER PRIMARY KEY,
    nom VARCHAR2(200),
    longitude VARCHAR2(200),
    latitude VARCHAR2(200)
);
CREATE TABLE Table_Date (
    id_date NUMBER PRIMARY KEY,
    jour NUMBER,
    mois NUMBER,
    annee NUMBER
);
CREATE TABLE Appareil_photo (
    id_app NUMBER PRIMARY KEY,
    marque VARCHAR2(50),
    nom VARCHAR2(50),
    createur VARCHAR(200)
);
CREATE TABLE Heritage_Photo (
    id_app NUMBER,
    id_derive NUMBER,
    distance NUMBER,
    flag_top NUMBER,
    flag_bot NUMBER,
    PRIMARY KEY (id_app, id_derive),
    FOREIGN KEY (id_app) REFERENCES Appareil_photo(id_app),
    FOREIGN KEY (id_derive) REFERENCES Appareil_photo(id_app)
);
CREATE TABLE Photo (
    appareil_id NUMBER,
    lieu_id,
    date_id NUMBER,
    PRIMARY KEY (appareil_id, lieu_id, date_id),
    FOREIGN KEY (appareil_id) REFERENCES Appareil_photo(id_app),
    FOREIGN KEY (lieu_id) REFERENCES Lieu(id_lieu),
    FOREIGN KEY (date_id) REFERENCES Table_Date(id_date)
);