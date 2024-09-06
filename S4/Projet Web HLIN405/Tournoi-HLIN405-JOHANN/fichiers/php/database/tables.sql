-- Tables pour les utilisateurs --

CREATE TABLE user(
    id INT AUTO_INCREMENT,
    email VARCHAR(191) UNIQUE NOT NULL,
    passwd VARCHAR(191) NOT NULL,
    name VARCHAR(191) NOT NULL,
    profile_picture VARCHAR(191) DEFAULT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE role(
    id INT AUTO_INCREMENT,
    label VARCHAR(191) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE user_role(
    user_id INT REFERENCES user(id),
    role_id INT REFERENCES role(id),
    PRIMARY KEY(user_id, role_id)
);

-- Extension des utilisateurs --

CREATE TABLE player(
    id INT AUTO_INCREMENT,
    description VARCHAR(191) NOT NULL,
    user_id INT REFERENCES user(id),
    PRIMARY KEY(id)
);

-- Contact --

CREATE TABLE city(
    id INT AUTO_INCREMENT,
    name VARCHAR(191) UNIQUE NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE zip_code(
    id INT AUTO_INCREMENT,
    code VARCHAR(5) UNIQUE NOT NULL,
    city_id INT REFERENCES city(id),
    PRIMARY KEY(id)
);

CREATE TABLE location(
    id INT AUTO_INCREMENT,
    address1 VARCHAR(38) NOT NULL,
    address2 VARCHAR(38) DEFAULT NULL,
    zip_code_id INT REFERENCES zip_code(id),
    PRIMARY KEY(id)
);

CREATE TABLE contact(
    id INT AUTO_INCREMENT,
    phone VARCHAR(14) NOT NULL,
    email VARCHAR(191) NOT NULL,
    location_id INT REFERENCES location(id),
    PRIMARY KEY(id)
);

-- Équipes --

CREATE TABLE team(
    id INT AUTO_INCREMENT,
    name VARCHAR(191) UNIQUE NOT NULL,
    level INT NOT NULL,
    profile_picture VARCHAR(191) DEFAULT NULL,
    active BOOLEAN DEFAULT 1,
    captain INT REFERENCES player(id),
    contact_id INT REFERENCES contact(id),
    PRIMARY KEY(id)
);

CREATE TABLE player_team(
    team_id INT REFERENCES team(id),
    player_id INT REFERENCES player(id),
    join_date DATETIME NOT NULL,
    left_date DATETIME DEFAULT NULL,
    PRIMARY KEY(team_id, player_id, join_date)
);

CREATE TABLE postulate_team(
    team_id INT REFERENCES team(id),
    player_id INT REFERENCES player(id),
    postulate_date DATETIME NOT NULL,
    statut VARCHAR(191) DEFAULT 'pending',
    PRIMARY KEY(team_id, player_id, postulate_date)
);

-- Tournois --

-- 1/ Type de tournois --

CREATE TABLE tournament_type(
    id INT AUTO_INCREMENT,
    label VARCHAR(191) UNIQUE NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE outcome_type(
    id INT AUTO_INCREMENT,
    label VARCHAR(191) UNIQUE NOT NULL,
    PRIMARY KEY(id)
);

-- 2/ Tournoi --

CREATE TABLE tournament(
    id INT AUTO_INCREMENT,
    name VARCHAR(191) NOT NULL,
    start_date DATETIME NOT NULL,
    end_inscription DATETIME NOT NULL,
    duration_in_day INT NOT NULL,
    manager_id INT REFERENCES user(id),
    location_id INT REFERENCES location(id),
    tournament_type_id INT REFERENCES tournament_type(id),
    PRIMARY KEY(id)
);

-- 3/ Scores --

CREATE TABLE score_tournament(
    tournament_type_id INT REFERENCES tournament_type(id),
    outcome_type_id INT REFERENCES outcome_type(id),
    score INT NOT NULL,
    PRIMARY KEY(tournament_type_id, outcome_type_id)
);

CREATE TABLE team_match(
    id INT AUTO_INCREMENT,
    team1_id INT REFERENCES team(id),
    team2_id INT REFERENCES team(id),
    tournament_id INT REFERENCES tournament(id),
    date DATETIME NOT NULL,
    result VARCHAR(191) DEFAULT NULL,
    parents_id VARCHAR(191) DEFAULT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE team_result(
    team_match_id INT REFERENCES team_match(id),
    team_id INT REFERENCES team(id),
    score_tournament_id INT REFERENCES score_tournament(id),
    PRIMARY KEY(team_match_id, team_id)
);

-- 4/ Liste des équipes qui participent --

CREATE TABLE postulate_tournament(
    team_id INT REFERENCES team(id),
    tournament_id INT REFERENCES tournament(id),
    postulate_date DATETIME NOT NULL,
    statut VARCHAR(191) DEFAULT 'pending',
    PRIMARY KEY(team_id, tournament_id)
);