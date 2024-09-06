<?php

namespace PH;

/**
 * La classe PHDB permet l'intéraction entre la base de données et l'application
 * web. 
 * Elle contient toutes les requêtes dont à besoin le site pour fonctionner. 
 * Elle grandit chaque jour selon les besoins des développeurs.
 */
class PHDB {
    private \PDO $dbh;
    private string $name;
    private string $charset;
    private string $collate;
    private Database\Cache $cache;

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * Ouvre une connexion MySQL avec la base de données configurée dans config.php
     * 
     * @param string $user    Nom de l'utilisateur de la base de données.
     * @param string $passwd  Le mot de passe avec lequel se connecter.
     * @param string $host    L'adresse de l'hôte de la base de données.
     * @param string $name    Le nom de la base de données à laquelle se connecter.
     * @param string $charset L'encodage de la base de données.
     * @param string $collate La `Collation` de la base de données.
     */
    public function __construct(string $user, string $passwd, string $host, string $name, string $charset, string $collate) {
        $this->name = $name;
        $this->charset = $charset;
        $this->collate = $collate;
        $this->cache = new Database\Cache();
        $dsn = "mysql:host=$host;charset=$charset";
        $attr = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_PERSISTENT => true,
        );
        try {
            $this->dbh = new \PDO($dsn, $user, $passwd, $attr);
        }
        catch (\PDOException $e) {
            ph_error_display('Échec de la connexion à la base de données : ' . $e->getMessage());
        }
    }

    // --------------------------------------------------------------------------------------------
    // Méthodes d'installation.

    /**
     * Vérifie si la base de données configurée existe.
     * 
     * @return bool Vrai si la base a bien été créée.
     * 
     * @author Johann Rosain
     */
    public function exists() : bool {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = $this->dbh->prepare("SELECT schema_name FROM information_schema.schemata WHERE schema_name = :name");
        $query->execute(array(':name' => $this->name));
        $result = count($query->fetchAll()) === 1 && count($this->getAllTablesInfos()) > 0;

        $this->cache->set(__FUNCTION__, $result);

        return $result;
    }

    /**
     * Crées la base de données avec le nom donné dans le constructeur.
     * 
     * @author Johann Rosain
     */
    public function createDatabase() : void {
        $this->dbh->query("CREATE DATABASE IF NOT EXISTS $this->name DEFAULT CHARACTER SET $this->charset COLLATE $this->collate");
        $this->useDb();
    }

    /**
     * Crée la base de données ainsi que toutes les tables présentent dans le 
     * schéma $db_schema.
     * 
     * @param  string $db_schema Schéma des tables de la base de données à créer.
     * 
     * @throws \Exception Si le schéma est vide.
     * @throws \PDOException S'il y a une erreur dans le schéma.
     * 
     * @author Johann Rosain
     */
    public function runInstallation(string $db_schema) : void {
        if (empty($db_schema)) {
            throw new \Exception('Le schéma de la base de données ne doit pas être vide.');
        }

        // Récupération de toutes les tables à créer
        $schema = explode(';', $db_schema);
        // Suppression des lignes vides.
        $schema = array_filter($schema);

        // Création du schéma
        foreach($schema as $query) {
            $this->dbh->query($query);
        }
    }

    /**
     * Permet de faire n'importe quelle requête depuis n'importe où.
     * 
     * @param  string $query La requête à faire.
     * @return \PDOStatement L'objet ayant la requête
     * @return false         Si une erreur survient
     * 
     * @throws \PDOException S'il y a une erreur dans la requête donnée.
     * 
     * @author Benoît Huftier
     */
    public function query(string $query) : \PDOStatement|false {
        return $this->dbh->query($query);
    }

    /**
     * Met à jour le PDO pour lui dire d'utiliser la base de données existante
     * avec le nom $this->name.
     * 
     * @author Johann Rosain
     */
    public function useDb() : void {
        $this->dbh->query("use $this->name");
    }

    // ------------------------------------------------------------------------
    // Méthodes de selection dans la base de données.

    /**
     * Récupère le nom de toutes les tables ainsi que leur colonnes.
     * 
     * @return array Association avec table => array(colonnes).
     * 
     * @author Johann Rosain
     */
    public function getAllTablesInfos() : array {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = 
        $this->dbh->prepare(
            "SELECT " . 
                "table_name, column_name " .
                "FROM information_schema.columns " .
                "WHERE table_schema = :name " .
                "GROUP BY table_name, column_name");
        $query->bindParam(':name', $this->name);
        $query->execute();
        $all_tables_infos = array();
        foreach($query->fetchAll() as $row) {
            $table_name = $row[0];
            $column_name = $row[1];

            $all_tables_infos[$table_name][] = $column_name;
        }

        $this->cache->set(__FUNCTION__, $all_tables_infos);

        return $all_tables_infos;
    }

    /**
     * Récupère tous les rôles qui existent dans la base de données.
     * 
     * @return array Toutes les lignes de la table `role`. 
     * 
     * @author Johann Rosain
     */
    public function getAllRoles() : array {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = $this->dbh->query('SELECT * FROM role');
        $result = $this->parseFetchedArray($query->fetchAll());

        $this->cache->set(__FUNCTION__, $result);

        return $result;
    }

    /**
     * Récupère tous les types de tournois qui existent dans la base de données.
     * 
     * @return array Toutes les lignes de la table `tournament_type`. 
     * 
     * @author Johann Rosain
     */
    public function getAllTournamentTypes() : array {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = $this->dbh->query('SELECT * FROM tournament_type');
        $result = $this->parseFetchedArray($query->fetchAll());

        $this->cache->set(__FUNCTION__, $result);

        return $result;
    }

    /**
     * Récupère tous les types de résultats qui existent dans la base de données.
     * 
     * @return array Toutes les lignes de la table `outcome_type`. 
     * 
     * @author Johann Rosain
     */
    public function getAllOutcomeTypes() : array {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = $this->dbh->query('SELECT * FROM outcome_type');
        $result = $this->parseFetchedArray($query->fetchAll());

        $this->cache->set(__FUNCTION__, $result);

        return $result;
    }

    /**
     * Récupère tous les résultats possibles qui existent dans la base de données.
     * 
     * @return array Toutes les lignes de la table `score_tournament`. 
     * 
     * @author Johann Rosain
     */
    public function getAllTournamentScores() : array {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = $this->dbh->query('SELECT * FROM score_tournament');
        $result = $this->parseFetchedArray($query->fetchAll());

        $this->cache->set(__FUNCTION__, $result);

        return $result;
    }

    /**
     * Fait une requête pour dump la base de données dans un fichier.
     * 
     * @param  array  $tables   Les tables à sélectionner.
     * @param  string $filename Nom du fichier dans lequel enregistrer les données.
     * 
     * @author Johann Rosain 
     */
    public function dumpInto(string $filename) : void {
        $tables = $this->getAllTablesInfos();

        $content = '';

        foreach(array_keys($tables) as $table) {
            $query = $this->dbh->query("SELECT * FROM $table");
            $data = $this->parseFetchedArray($query->fetchAll());
            $content .= $this->getInsertQueryFromRecord($table, $data);            
        }

        file_put_contents($filename, $content);
    }

    /**
     * Supprime la base de données.
     * 
     * @author Johann Rosain
     */
    public function dropDatabase() : void {
        $this->dbh->query("DROP DATABASE $this->name");
    }

    /**
     * Vérifie si le super administrateur avec les attributs donnés est le même 
     * que celui stocké dans la base de données.
     * 
     * @return bool Faux si un des attributs est différent.
     * 
     * @author Johann Rosain
     */
    public function adminDiffers(string $email, string $passwd, string $name) : bool {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = $this->dbh->query('SELECT email, passwd, name FROM user WHERE id = 1');
        $result = $query->fetchAll();

        if (empty($result)) {
            throw new Exception('La base de données est corrompue, il n\'y a pas de compte super administrateur.');
        }

        $result = $result[0];
        $result = !($email === $result['email'] && (true === password_verify($passwd, $result['passwd'])) && $name === $result['name']);

        $this->cache->set(__FUNCTION__, $result);
        
        return $result;
    }

    /**
     * Génère une requête d'insertion pour chaque ligne de l'enregistrement sql
     * donné.
     * 
     * @param  string $table La table concernée par l'enregistrement.
     * @param  array $data   L'enregistrement pour lequel générer les requêtes.
     * @return string        La requête générée.
     * 
     * @author Johann Rosain
     */
    private function getInsertQueryFromRecord(string $table, array $data) : string {
        $content = '';
        if (!empty($data)) {
            $content .= "INSERT INTO $table VALUES";
            foreach ($data as $index => $row) {
                $content .= '(';
                foreach ($row as $i => $attr) {
                    if (!is_numeric($attr) && is_string($attr)) {
                        $attr = "'" . $attr . "'";
                    }
                    if (is_null($attr)) {
                        $attr = 'NULL';
                    }

                    $content .= $attr;

                    if ($i < count($row) - 1) {
                        $content .= ', ';
                    }
                }
                $content .= ')';
                if ($index < count($data) - 1) {
                    $content .= ', ';
                }
            }
            $content .= ';' . PHP_EOL;
        }
        return $content;
    }

    // --------------------------------------------------------------------------------------------
    // Méthodes pour l'utilisateur.

    /**
     * Récupère l'utilisateur ayant l'email donné.
     * 
     * @param  string $user_email L'email de l'utilisateur à récupérer.
     * @return array              Les champs de l'utilisateur demandé sous forme
     *                            de tableau.
     * 
     * @author Johann Rosain
     */
    public function getUserFromEmail(string $user_email) : array {
        return $this->getUserFrom('email', $user_email, \PDO::PARAM_STR);
    }

    /**
     * Récupère l'utilisateur ayant l'identifiant donné.
     * 
     * @param  int   $user_id L'identifiant de l'utilisateur à récupérer.
     * @return array          Les champs de l'utilisateur demandé sous forme
     *                        de tableau.
     * 
     * @author Benoît Huftier
     */
    public function getUserFromId(int $user_id) : array {
        return $this->getUserFrom('id', $user_id, \PDO::PARAM_INT);
    }

    private function getUserFrom(string $field, mixed $value, int $data_type = \PDO::PARAM_STR) : array {
        if ($this->cache->isValid(__FUNCTION__ . $field . strval($value))) {
            return $this->cache->get(__FUNCTION__ . $field . strval($value));
        }

        $query = $this->dbh->prepare(
            "SELECT user.*, player.description, player.id AS player_id
             FROM user
             LEFT JOIN player ON player.user_id = user.id
             WHERE user.$field = :value"
        );
        $query->bindParam(':value', $value, $data_type);
        $query->execute();
        $result = $query->fetch();

        if (false === $result) {
            $result = array();
        }

        $this->cache->set(__FUNCTION__ . $field . strval($value), $result);

        return $result;
    }

    /**
     * Récupère tous les utilisateurs du site
     * 
     * @param  array $conds Les conditions de sélection d'un joueur.
     * @return array        Les champs de tous les utilisateurs sous forme de tableau.
     * 
     * @author Benoît Huftier
     */
    public function getUsers(array $conds = array()) : array {
        $cache_key = __FUNCTION__ . json_encode($conds);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $conds = $this->processUsersConds($conds);
        $where = $conds['where'];
        $values = $conds['values'];

        $query = $this->dbh->prepare(
            "SELECT user.*, player.description, player.id AS player_id
             FROM user
             LEFT JOIN player ON player.user_id = user.id
             $where"
        );

        $result = array();
        if ($query->execute($values)) {
            foreach ($query->fetchAll() as $user) {
                $result[$user['id']] = $user;
            }
        }

        $this->cache->set($cache_key, $result);

        return $result;
    }

    private function processUsersConds(array $conds) : array {
        $cache_key = __FUNCTION__ . json_encode($conds);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $str = 'WHERE 1 ';
        $roles = '';
        $roles_v = array();
        $prof = 0;
        $values = array();

        $corr = array(
            'name' => 'user.name',
            'email' => 'user.email',
            'role-1' => 'role.label',
            'role-2' => 'role.label',
            'role-3' => 'role.label',
        );

        foreach ($conds as $k => $v) {
            if (array_key_exists($k, $corr)) {
                if (false !== strstr($k, 'role')) {
                    if (empty($roles)) {
                        $roles .= 'AND user.id IN (SELECT DISTINCT user_id FROM user_role WHERE role_id = (SELECT id FROM role WHERE label = ?)';
                    }
                    else {
                        $roles .= ' AND user_id IN (SELECT user_id FROM user_role WHERE role_id = (SELECT id FROM role WHERE label = ?)';
                    }
                    $prof += 1;
                    $roles_v[] = $v;
                }
                else {
                    $str .= "AND {$corr[$k]} LIKE ? ";
                    $values[] = '%' . $v . '%';
                }
            }
        }

        if (!empty($roles)) {
            for ($i = 0; $i < $prof; ++$i) {
                $roles .= ')';
            }

            $str .= $roles;
            $values = array_merge($values, $roles_v);
        }

        $result = array(
            'where' => $str,
            'values' => $values,
        );

        $this->cache->set($cache_key, $result);
        return $result;
    }

    /**
     * Récupère le joueur ayant l'identifiant donné.
     * 
     * @param  int   $player_id L'identifiant du joueur à récupérer.
     * @return array            Les champs du joueur demandé sous forme de tableau
     *                          combiné avec les informations de l'utilisateur
     *                          correspondant.
     * 
     * @author Benoît Huftier
     */
    public function getPlayerFromId(int $player_id) : array {
        if ($this->cache->isValid(__FUNCTION__ . strval($player_id))) {
            return $this->cache->get(__FUNCTION__ . strval($player_id));
        }

        $query = $this->dbh->prepare(
            'SELECT user.*, player.description, player.id AS player_id
             FROM player
             INNER JOIN user ON user.id = player.user_id
             WHERE player.id = :id'
        );
        $query->bindParam(':id', $player_id, \PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();

        if (false === $result) {
            $result = array();
        }

        $this->cache->set(__FUNCTION__ . strval($player_id), $result);

        return $result;
    }

    /**
     * Récupère toutes les informations des joueurs d'une équipe
     * 
     * @param  int   $team_id L'id de l'équipe.
     * @return array          Les champs des joueurs demandés sous forme de tableau
     *                        combiné avec les informations des utilisateurs
     *                        correspondant.
     * 
     * @author Benoît Huftier
     */
    public function getPlayersForTeam(int $team_id) : array {
        if ($this->cache->isValid(__FUNCTION__ . strval($team_id))) {
            return $this->cache->get(__FUNCTION__ . strval($team_id));
        }

        $query = $this->dbh->prepare(
            'SELECT user.*, player.description, player.id AS player_id
             FROM player_team
             INNER JOIN player ON player.id = player_team.player_id
             INNER JOIN user ON user.id = player.user_id
             WHERE player_team.team_id = :id
             AND player_team.left_date IS NULL'
        );

        $query->bindParam(':id', $team_id, \PDO::PARAM_INT);

        $result = array();
        if ($query->execute()) {
            foreach ($query->fetchAll() as $player) {
                $result[$player['id']] = $player;
            }
        }

        $this->cache->set(__FUNCTION__ . strval($team_id), $result);

        return $result;
    }

    /**
     * Récupère toutes les informations des joueurs de plusieurs équipes
     * 
     * @param  array $team_ids Les id des différentes équipes.
     * @return array           Les champs des joueurs demandés sous forme de tableau
     *                         combiné avec les informations des utilisateurs
     *                         correspondant.
     *                         Chaque team a son propre tableau de joueur et l'id de
     *                         l'équipe est la clé du tableau.
     * 
     * @author Benoît Huftier
     */
    public function getPlayersForTeams(array $team_ids) : array {
        if (empty($team_ids)) {
            return array();
        }

        $cache_key = __FUNCTION__ . implode(',', $team_ids);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $ids = $this->createBindingFromArray($team_ids);

        $query = $this->dbh->prepare(
            "SELECT user.*, player.description, player.id AS player_id, player_team.team_id
             FROM player_team
             INNER JOIN player ON player.id = player_team.player_id
             INNER JOIN user ON user.id = player.user_id
             WHERE player_team.team_id IN $ids
             AND player_team.left_date IS NULL"
        );

        $this->bindFromArray($query, $team_ids, \PDO::PARAM_INT);

        $result = array();
        if ($query->execute()) {
            foreach ($query->fetchAll() as $player) {
                $result[$player['team_id']][$player['id']] = $player;
            }
        }

        $this->cache->set($cache_key, $result);

        return $result;
    }

    /**
     * Récupère les rôles d'un utilisateur donné.
     * 
     * @param  int   $user_id L'id de l'utilisateur.
     * @return array          Les rôles de l'utilisateur sous forme de tableau.
     * 
     * @author Benoît Huftier
     */
    public function getRolesForUser(int $user_id) : array {
        if ($this->cache->isValid(__FUNCTION__ . strval($user_id))) {
            return $this->cache->get(__FUNCTION__ . strval($user_id));
        }

        $query = $this->dbh->prepare(
            'SELECT role.*
             FROM role
             INNER JOIN user_role ON user_role.role_id = role.id
             WHERE user_role.user_id = :user_id'
        );
        $query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll();

        if (false === $result) {
            $result = array();
        }

        $this->cache->set(__FUNCTION__ . strval($user_id), $result);

        return $result;
    }

    /**
     * Récupère les rôles de plusieurs utilisateurs donnés.
     * 
     * @param  array $user_ids Les id des utilisateurs.
     * @return array           Les rôles de tous les utilisateurs sous forme de
     *                         tableau. Les ids des utilisateurs sont les clés du
     *                         tableau.
     * 
     * @author Benoît Huftier
     */
    public function getRolesForUsers(array $user_ids) : array {
        if (empty($user_ids)) {
            return array();
        }

        $cache_key = __FUNCTION__ . implode(',', $user_ids);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $ids = $this->createBindingFromArray($user_ids);

        $query = $this->dbh->prepare(
            "SELECT role.*, user_role.user_id
             FROM role
             INNER JOIN user_role ON user_role.role_id = role.id
             WHERE user_role.user_id IN $ids"
        );

        $this->bindFromArray($query, $user_ids, \PDO::PARAM_INT);
        
        $result = array();
        if ($query->execute()) {
            foreach ($query->fetchAll() as $role) {
                $result[$role['user_id']][] = $role;
            }
        }

        $this->cache->set($cache_key, $result);

        return $result;
    }

    // --------------------------------------------------------------------------------------------
    // Méthodes pour les équipes

    /**
     * Récupère l'équipe ayant l'id donné.
     * 
     * @param  int   $team_id L'id de l'équipe à récupérer.
     * @return array          Les champs de l'équipe demandée sous forme de tableau.
     * 
     * @author Benoît Huftier
     */
    public function getTeamFromId(int $team_id) : array {
        return $this->getTeamFrom('id', $team_id, \PDO::PARAM_INT);
    }

    /**
     * Récupère l'équipe ayant le nom donné.
     * 
     * @param  string $team_name Le nome de l'équipe à récupérer.
     * @return array             Les champs de l'équipe demandée sous forme de tableau.
     * 
     * @author Benoît Huftier
     */
    public function getTeamFromName(string $team_name) : array {
        return $this->getTeamFrom('name', $team_name, \PDO::PARAM_STR);
    }

    private function getTeamFrom(string $field, mixed $value, int $data_type = \PDO::PARAM_STR) : array {
        if ($this->cache->isValid(__FUNCTION__ . $field . strval($value))) {
            return $this->cache->get(__FUNCTION__ . $field . strval($value));
        }

        $query = $this->dbh->prepare(
            "SELECT
                team.*,
                player.user_id AS captain,
                contact.email AS email,
                contact.phone AS phone,
                city.name AS city,
                zip_code.code AS code,
                location.id AS location_id,
                location.address1 AS address1,
                location.address2 AS address2
             FROM team
             INNER JOIN player ON player.id = team.captain
             INNER JOIN contact ON contact.id = team.contact_id
             INNER JOIN location ON location.id = contact.location_id
             INNER JOIN zip_code ON zip_code.id = location.zip_code_id
             INNER JOIN city ON city.id = zip_code.city_id
             WHERE team.$field = :value"
        );

        $query->bindParam(':value', $value, $data_type);
        $query->execute();
        $result = $query->fetch();

        if (false === $result) {
            $result = array();
        }

        $this->cache->set(__FUNCTION__ . $field . strval($value), $result);

        return $result;
    }

    /**
     * Récupère tous les utilisateurs capitaines dans la base de données.
     */
    public function getAllCaptains() : array {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = $this->dbh->query(
            'SELECT 
                user.name,
                player.id
             FROM 
                team
                INNER JOIN player ON (team.captain = player.id)
                INNER JOIN user ON (player.user_id = user.id)
            ');
        $query->execute();

        $result = array();

        foreach ($query->fetchAll() as $record) {
            $result[$record['id']] = $record['name'];
        }

        $this->cache->set(__FUNCTION__, $result);
        return $result;
    }

    /**
     * Récupère le nombre d'équipe dont les colonnes matchent avec les conditions données.
     * 
     * @param  array  $conds Les conditions qui doivent matcher avec les colonnes de la table des équipes.
     * @return int           Le nombre d'équipe qui matchent 
     * 
     * @see getTeamsWhereCondsMatch pour récupérer les équipes en questions
     * 
     * @author Benoît Huftier
     */
    public function getTeamsNumberWhereCondsMatch(array $conds) : int {
        if ($this->cache->isValid(__FUNCTION__ . json_encode($conds))) {
            return $this->cache->get(__FUNCTION__ . json_encode($conds));
        }

        $conds = $this->processTeamsConds($conds);
        $where = $conds['where'];
        $values = $conds['values'];

        // La requête qui suit ne marche pas si les attributs sont émulés, pour une raison inconnue.
        $this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

        $query = $this->dbh->prepare(
            "SELECT COUNT(team.id)
             FROM team
             $where"
        );

        $query->execute($values);
        $result = $query->fetch();

        if (false === $result) {
            $result = 0;
        }
        else {
            $result = $result[0];
        }

        // La requête qui précède ne marche pas si les attributs sont émulés, pour une raison inconnue.
        $this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

        $this->cache->set(__FUNCTION__ . json_encode($conds), $result);

        return $result;
    }

    /**
     * Récupère les équipes où les colonnes matchent avec les conditions données.
     * Elles sont triées dans l'ordre suivant :
     * - Les équipes de l'utilisateur actuel
     * - Les équipes en activité
     * - Les noms des équipes
     * 
     * @param  string  $conds Les conditions qui doivent matcher avec les colonnes de la table des équipes.
     * @param  int     $max   Le nombre max d'équipe voulu. Défault = 10. 
     * @param  int     $page  La page voulue (offset de la requête). Défault = 1. 
     * @return array          Toutes les équipes qui matchent dans un tableau
     * 
     * @see getTeamsNumberWhereCondsMatch pour récupérer le nombre total d'équipes qui matche
     * 
     * @author Benoît Huftier
     */
    public function getTeamsWhereCondsMatch(array $conds, int $max = 10, int $page = 1) : array {
        if ($this->cache->isValid(__FUNCTION__ . json_encode($conds) . strval($max) . strval($page))) {
            return $this->cache->get(__FUNCTION__ . json_encode($conds) . strval($max) . strval($page));
        }

        $offset = abs($page - 1) * $max;
        $player_id = ph_get_user()->isPlayer() ? ph_get_user()->getPlayerId() : 0;

        $conds = $this->processTeamsConds($conds);
        $where = $conds['where'];
        $values = array_merge(array($player_id), $conds['values'], array($max, $offset));

        // La requête qui suit ne marche pas si les attributs sont émulés, pour une raison inconnue.
        $this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

        $query = $this->dbh->prepare(
            "SELECT
                team.*,
                player.user_id AS captain,
                contact.phone AS phone,
                contact.email AS email,
                city.name AS city,
                zip_code.code AS code,
                location.id AS location_id,
                location.address1 AS address1,
                location.address2 AS address2
             FROM team
             LEFT JOIN player_team ON player_team.team_id = team.id AND player_team.player_id = ?
             INNER JOIN player ON player.id = team.captain
             INNER JOIN contact ON contact.id = team.contact_id
             INNER JOIN location ON location.id = contact.location_id
             INNER JOIN zip_code ON zip_code.id = location.zip_code_id
             INNER JOIN city ON city.id = zip_code.city_id
             $where
             ORDER BY player_team.player_id IS NULL, team.active DESC, team.name
             LIMIT ?
             OFFSET ?"
        );
        
        $result = array();
        if ($query->execute($values)) {
            foreach ($query->fetchAll() as $team) {
                $result[$team['id']] = $team;
            }
        }

        // La requête qui précède ne marche pas si les attributs sont émulés, pour une raison inconnue.
        $this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

        $this->cache->set(__FUNCTION__ . json_encode($conds) . strval($max) . strval($page), $result);

        return $result;
    }

    private function processTeamsConds(array $conds) : array {
        $cache_key = __FUNCTION__ . json_encode($conds);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $str = 'WHERE 1 ';
        $values = array();

        $corr = array(
            'name' => 'team.name',
            'captain' => 'team.captain',
            'activity' => 'team.active',
            'nb-players' => '(SELECT COUNT(*) FROM player_team WHERE team_id = team.id AND left_date IS NULL)',
        );

        foreach ($conds as $k => $v) {
            if (array_key_exists($k, $corr)) {
                $str .= 'AND ';
                if ('nb-players' === $k) {
                    $str .= "{$corr[$k]} {$v[0]} ? ";
                    $values[] = $v[1];
                }
                else if ('name' === $k) {
                    $str .= " {$corr[$k]} LIKE ? ";
                    $values[] = '%' . $v . '%';
                }
                else {
                    if ('activity' === $k) {
                        $v = ('1' === $v);
                    }
                    $str .= "{$corr[$k]} = ? ";
                    $values[] = $v;
                }
            }
        }

        $result = array(
            'where' => $str,
            'values' => $values,
        );

        $this->cache->set($cache_key, $result);
        return $result;
    }

    /**
     * Récupère les joueurs qui ont postulés à une équipe.
     * C'est uniquement le dernier postulat de chaque joueur pour l'équipe demandée qui est
     * récupéré.
     * 
     * @param  int  $team_id L'id de l'équipe dont l'on veut les postulats.
     * @return array         Les postulats = joueur + données du postulat.
     * 
     * @author Benoît Huftier
     */
    public function getPostulatesForTeam(int $team_id) : array {
        if ($this->cache->isValid(__FUNCTION__ . strval($team_id))) {
            return $this->cache->get(__FUNCTION__ . strval($team_id));
        }

        $query = $this->dbh->prepare(
            'SELECT user.*, player.description, player.id AS player_id, pt1.statut, pt1.postulate_date
             FROM postulate_team pt1
             INNER JOIN player ON player.id = pt1.player_id
             INNER JOIN user ON user.id = player.user_id
             WHERE pt1.team_id = :team_id
             AND pt1.postulate_date = (
                 SELECT MAX(pt2.postulate_date)
                 FROM postulate_team pt2
                 WHERE pt2.player_id = pt1.player_id
                 AND pt2.team_id = pt1.team_id
            )'
        );

        $query->bindParam(':team_id', $team_id, \PDO::PARAM_INT);

        $result = array();
        if ($query->execute()) {
            foreach ($query->fetchAll() as $player) {
                $result[$player['id']] = $player;
            }
        }

        $this->cache->set(__FUNCTION__ . strval($team_id), $result);

        return $result;
    }

    // ------------------------------------------------------------------------
    // Méthodes de modification de la base de données.

    /**
     * Enregistre un utilisateur dans la base de données
     * 
     * @param  string $email           L'email du nouvel utilisateur.
     * @param  string $name            Le nom affiché du nouvel utilisateur.
     * @param  string $password        Le mot de passe chiffré avec password_hash() en BCRYPT du nouvel utilisateur.
     * @param  string $profile_picture Chemin de la photo de profil. Nul s'il n'y en a pas.
     * 
     * @author Johann Rosain
     */
    public function registerUser(string $email, string $name, string $password, string|null $profile_picture) : void {
        // Ajouter le nouvel utilisateur
        $query = $this->dbh->prepare('INSERT INTO user(email, name, passwd, profile_picture) VALUES(:email, :name, :passwd, :pp);');
        $query->bindValue(':email', $email);
        $query->bindValue(':name', $name);
        $query->bindValue(':passwd', $password);
        $query->bindValue(':pp', $profile_picture);
        $query->execute();

        // Ajouter le joueur
        $query = $this->dbh->prepare('INSERT INTO player(description, user_id) VALUES(\'\', (SELECT id FROM user WHERE email = :email));');
        $query->bindValue(':email', $email);
        $query->execute();

        // Ajouter son rôle de joueur
        $query = $this->dbh->prepare('INSERT INTO user_role VALUES((SELECT id FROM user WHERE email = :email), (SELECT id FROM role WHERE label = :label));');
        $query->bindValue(':email', $email);
        $query->bindValue(':label', 'Joueur');
        $query->execute();
    }

    /**
     * Mets à jour les données d'un utilisateur.
     * 
     * @param  int    $id              L'id de l'utilisateur à mettre à jour.
     * @param  string $email           L'email de l'utilisateur.
     * @param  string $name            Le nom affiché de l'utilisateur.
     * @param  string $profile_picture Chemin de la photo de profil. Nul s'il n'y en a pas.
     * 
     * @author Johann Rosain
     */
    public function updateUser(int $id, string $email, string $name, string|null $profile_picture) : void {
        $query = $this->dbh->prepare('UPDATE user SET email = :email, name = :name, profile_picture = :pp WHERE id = :id;');
        $query->bindValue(':id', $id);
        $query->bindValue(':email', $email);
        $query->bindValue(':name', $name);
        $query->bindValue(':pp', $profile_picture);
        $query->execute();
    }

    /**
     * Mets à jour le mot de passe d'un utilisateur.
     * 
     * @param  int    $id       L'id de l'utilisateur à mettre à jour.
     * @param  string $password Le mot de passe chiffré avec password_hash() en BCRYPT de l'utilisateur.
     * 
     * @author Johann Rosain
     */
    public function updatePassword(int $id, string $password) : void {
        $query = $this->dbh->prepare('UPDATE user SET passwd = :passwd WHERE id = :id;');
        $query->bindValue(':id', $id);
        $query->bindValue(':passwd', $password);
        $query->execute();
    }

    /**
     * Mets à jour la description d'un joueur.
     * 
     * @param  int    $id          L'id du joueur à mettre à jour.
     * @param  string $description La description du joueur.
     * 
     * @author Johann Rosain
     */
    public function updatePlayer(int $id, string $description) : void {
        $query = $this->dbh->prepare('UPDATE player SET description = :description WHERE id = :id;');
        $query->bindValue(':id', $id);
        $query->bindValue(':description', $description);
        $query->execute();
    }

    /**
     * Modifie le capitaine d'une équipe
     * 
     * @param int $team_id         L'identificant de l'équipe
     * @param int $new_captain_id  L'identifiant de joueur du nouveau capitaine.
     * 
     * @author Benoît Huftier
     */
    public function changeTeamCaptain(int $team_id, int $new_captain_id) : void {
        $query = $this->dbh->prepare('UPDATE team SET captain = :captain WHERE id = :id;');
        $query->bindValue(':captain', $new_captain_id, \PDO::PARAM_INT);
        $query->bindValue(':id', $team_id, \PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Modifie l'activation d'une équipe
     * 
     * @param int  $team_id     L'identificant de l'équipe
     * @param bool $activation  Activation ou désactivation de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function changeTeamActivation(int $team_id, bool $activation) : void {
        $query = $this->dbh->prepare('UPDATE team SET active = :active WHERE id = :id;');
        $query->bindValue(':active', $activation, \PDO::PARAM_BOOL);
        $query->bindValue(':id', $team_id, \PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Ajoute un joueur dans une équipe
     * 
     * @param int          $player_id  L'identifiant du joueur qui rejoint l'équipe.
     * @param int          $team_id    L'identifiant de l'équipe que le joueur rejoint.
     * @param string       $join_date  La date à laquelle le joueur à rejoint, par défaut, c'est maintenant.
     * @return array                   Un tableau contenant les 3 clés primaires de la valeur insérée
     *                                 (player_team n'ayant pas d'identifiant).
     * @throws \Exception  Si l'équipe n'a pas pu être insérée dans la base de données.
     * 
     * @author Benoît Huftier
     */
    public function playerJoinTeam(int $player_id, int $team_id, string $join_date = '') : array {
        $query = $this->dbh->prepare(
            'INSERT INTO player_team(team_id, player_id, join_date)
             VALUES(:team, :player, :join_date)'
        );

        if (empty($join_date)) {
            $join_date = (new \DateTime())->format('Y-m-d h:i:s');
        }
        
        $query->bindValue(':team', $team_id);
        $query->bindValue(':player', $player_id);
        $query->bindValue(':join_date', $join_date);

        if (false === $query->execute()) {
            throw new \Exception('Impossible d\'ajouter le joueur à l\'équipe demandée dans la base de données.');
        }

        return array(
            'team_id' => $team_id,
            'player_id' => $player_id,
            'join_date' => $join_date,
        );
    }

    /**
     * Fait quitter un joueur d'une équipe, en mettant une valeur dans sa date de sortie
     * 
     * @param int  $player_id L'identificant du joueur
     * @param int  $team_id   L'identificant de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function playerLeftTeam(int $player_id, int $team_id) : void {
        $query = $this->dbh->prepare('UPDATE player_team SET left_date = NOW() WHERE player_id = :player_id AND team_id = :team_id AND left_date IS NULL');
        $query->bindValue(':player_id', $player_id, \PDO::PARAM_INT);
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Supprime le postulat d'un joueur pour une équipe
     * 
     * @param int  $player_id L'identificant du joueur
     * @param int  $team_id   L'identificant de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function removePostulate(int $player_id, int $team_id) : void {
        $query = $this->dbh->prepare('DELETE FROM postulate_team WHERE player_id = :player_id AND team_id = :team_id AND statut = :statut;');
        $query->bindValue(':player_id', $player_id, \PDO::PARAM_INT);
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->bindValue(':statut', \Postulate::Pending, \PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Accepte le dernier postulat d'un joueur pour une équipe
     * 
     * @param int  $player_id L'identificant du joueur
     * @param int  $team_id   L'identificant de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function acceptPostulate(int $player_id, int $team_id) : void {
        $query = $this->dbh->prepare('UPDATE postulate_team SET statut = :accepted WHERE player_id = :player_id AND team_id = :team_id AND statut = :pending;');
        $query->bindValue(':accepted', \Postulate::Accepted, \PDO::PARAM_STR);
        $query->bindValue(':player_id', $player_id, \PDO::PARAM_INT);
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->bindValue(':pending', \Postulate::Pending, \PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Refuse le dernier postulat d'un joueur pour une équipe
     * 
     * @param int  $player_id L'identificant du joueur
     * @param int  $team_id   L'identificant de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function refusePostulate(int $player_id, int $team_id) : void {
        $query = $this->dbh->prepare('UPDATE postulate_team SET statut = :refused WHERE player_id = :player_id AND team_id = :team_id AND statut = :pending;');
        $query->bindValue(':refused', \Postulate::Refused, \PDO::PARAM_STR);
        $query->bindValue(':player_id', $player_id, \PDO::PARAM_INT);
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->bindValue(':pending', \Postulate::Pending, \PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Transforme le dernier postulat refusé en accepté
     * 
     * @param int  $player_id L'identificant du joueur
     * @param int  $team_id   L'identificant de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function acceptRefusedPostulate(int $player_id, int $team_id) : void {
        $query = $this->dbh->prepare('UPDATE postulate_team SET statut = :accepted WHERE player_id = :player_id AND team_id = :team_id AND statut = :refused;');
        $query->bindValue(':accepted', \Postulate::Accepted, \PDO::PARAM_STR);
        $query->bindValue(':player_id', $player_id, \PDO::PARAM_INT);
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->bindValue(':refused', \Postulate::Refused, \PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Supprime le dernier postulat refusé pour débloquer la possibilité au joueur de repostuler
     * 
     * @param int  $player_id L'identificant du joueur
     * @param int  $team_id   L'identificant de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function unblockPlayer(int $player_id, int $team_id) : void {
        $query = $this->dbh->prepare('DELETE FROM postulate_team WHERE player_id = :player_id AND team_id = :team_id AND statut = :statut;');
        $query->bindValue(':player_id', $player_id, \PDO::PARAM_INT);
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->bindValue(':statut', \Postulate::Refused, \PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Ajoute le postulat d'un joueur dans une équipe
     * 
     * @param int    $player_id L'identificant du joueur
     * @param int    $team_id   L'identificant de l'équipe
     * @param string $statut    Le status à mettre de base dans le postulat, défaut à "pending"
     * 
     * @author Benoît Huftier
     */
    public function postulate(int $player_id, int $team_id, string $statut = \Postulate::Pending) : void {
        $query = $this->dbh->prepare('INSERT INTO postulate_team(player_id, team_id, postulate_date, statut) VALUES(:player_id, :team_id, NOW(), :statut);');
        $query->bindValue(':player_id', $player_id, \PDO::PARAM_INT);
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->bindValue(':statut', $statut, \PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Supprime l'équipe demandée de la base de donnée.
     * Supprimer également tout postulat ou historique des joueurs avec cette équipe.
     * 
     * @param int $team_id L'identifiant de l'équipe à supprimer
     * 
     * @author Benoît Huftier
     */
    public function deleteTeam(int $team_id) : void {
        $query = $this->dbh->prepare('DELETE FROM team WHERE id = :team_id');
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->execute();
        
        $query = $this->dbh->prepare('DELETE FROM player_team WHERE team_id = :team_id');
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->execute();
        
        $query = $this->dbh->prepare('DELETE FROM postulate_team WHERE team_id = :team_id');
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->execute();
        
        $query = $this->dbh->prepare('DELETE FROM postulate_tournament WHERE team_id = :team_id');
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->execute();
    }

    // ------------------------------------------------------------------------
    // Autres méthodes publiques.

    /**
     * Remet le cache à 0.
     * 
     * @author Johann Rosain
     */
    public function resetCache() : void {
        $this->cache = new Database\Cache();
    }

    // --------------------------------------------------------------------------------------------
    // Méthodes pour récupérer des infos depuis la base de données.

    /**
     * Récupère l'id et le nom de tous les gestionnaires de tournois.
     * 
     * @return array Les gestionnaires de tournoi avec un tableau id => nickname.
     * 
     * @author Johann Rosain
     */
    public function getManagers() : array {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = $this->dbh->prepare(
            'SELECT 
                u.id, u.name
             FROM
                user u
                INNER JOIN user_role ur ON (u.id = ur.user_id)
                INNER JOIN role r ON (ur.role_id = r.id)
             WHERE 
                 r.label = :label'
        );

        $query->bindValue(':label', \Role::toString(\Role::Manager));

        $result = array();
        if ($query->execute()) {
            foreach ($query->fetchAll() as $record) {
                $result[$record['id']] = $record['name'];
            }
        }

        $this->cache->set(__FUNCTION__, $result);

        return $result;
    }

    /**
     * Récupère le nom de toutes les villes enregistrées dans la base de données.
     * 
     * @return array Les villes avec un tableau contenant le nom des villes.
     * 
     * @author Johann Rosain
     */
    public function getCities() : array {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = $this->dbh->prepare(
            'SELECT DISTINCT name
             FROM
                city'
        );

        $result = array();
        if ($query->execute()) {
            foreach ($query->fetchAll() as $record) {
                $result[] = $record['name'];
            }
        }

        $this->cache->set(__FUNCTION__, $result);

        return $result;
    }

    /**
     * Récupère le numéro de tous les codes postaux enregistrées dans la base de données.
     * 
     * @return array Les codes postaux avec un tableau contenant les codes.
     * 
     * @author Johann Rosain
     */
    public function getZips() : array {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = $this->dbh->prepare(
            'SELECT DISTINCT code
             FROM
                zip_code'
        );

        $result = array();
        if ($query->execute()) {
            foreach ($query->fetchAll() as $record) {
                $result[] = $record['code'];
            }
        }

        $this->cache->set(__FUNCTION__, $result);

        return $result;
    }

    // --------------------------------------------------------------------------------------------
    // Méthodes d'insertion dans la BDD.

    /**
     * Insère la ville dans la base de données si elle n'existe pas.
     * 
     * @param  string     $city_name Le nom de la ville.
     * @return int                   L'identifiant de la ville demandée.
     * @throws \Exception Lors d'une erreur à l'insertion.
     * 
     * @author Johann Rosain
     */
    public function insertCityIfNotExists(string $city_name) : int {
        return $this->insertIfNotExists(
            __FUNCTION__ . $city_name,
            'SELECT * FROM city WHERE name = ?',
            'INSERT INTO city(name) VALUES(?)',
            array($city_name),
            array($city_name)
        );
    }

    /**
     * Insère le code postal dans la base de données s'il n'existe pas.
     * 
     * @param  string     $zip     Le code postal.
     * @param  int        $city_id L'identifiant de la ville.
     * @return int                 L'identifiant du code postal demandé.
     * @throws \Exception Lors d'une erreur à l'insertion.
     * 
     * @author Johann Rosain
     */
    public function insertZipIfNotExists(string $zip, int $city_id) : int {
        return $this->insertIfNotExists(
            __FUNCTION__ . $zip,
            'SELECT * FROM zip_code WHERE code = ?',
            'INSERT INTO zip_code(code, city_id) VALUES(?, ?)',
            array($zip),
            array($zip, $city_id)
        );
    }

    /**
     * Insère l'adresse dans la base de données si elle n'existe pas.
     * 
     * @param  string      $address1    La première ligne d'adresse.
     * @param  string|null $address2    La ligne facultative d'adresse.
     * @param  string      $zip_code_id L'identifiant du code postal.
     * @return int                      L'identifiant de la localisation demandée.
     * @throws \Exception  Lors d'une erreur à l'insertion.
     * 
     * @author Johann Rosain
     */
    public function insertLocationIfNotExists(string $address1, string|null $address2, int $zip_code_id) : int {
        $query_exists = is_null($address2)
                      ? 'SELECT * FROM location WHERE address1 = ? AND address2 IS ? AND zip_code_id = ?'
                      : 'SELECT * FROM location WHERE address1 = ? AND address2 = ? AND zip_code_id = ?';

        return $this->insertIfNotExists(
            __FUNCTION__ . $address1 . $zip_code_id,
            $query_exists,
            'INSERT INTO location(address1, address2, zip_code_id) VALUES(?, ?, ?)',
            array($address1, $address2, $zip_code_id),
            array($address1, $address2, $zip_code_id)
        );
    }

    /**
     * Insère l'adresse dans la base de données si elle n'existe pas.
     * 
     * @param  string     $phone        Le téléphone du contact.
     * @param  string     $email        L'adresse mail du contact.
     * @param  int        $location_id  L'identifiant de la localisation.
     * @throws \Exception Lors d'une erreur à l'insertion.
     * 
     * @author Johann Rosain
     */
    public function insertContactIfNotExists(string $phone, string $email, int $location_id) : int {
        return $this->insertIfNotExists(
            __FUNCTION__ . $phone . ' ' . $email . ' ' . strval($location_id),
            'SELECT * FROM contact WHERE phone = ? AND email = ? AND location_id = ?',
            'INSERT INTO contact(phone, email, location_id) VALUES(?, ?, ?)',
            array($phone, $email, $location_id),
            array($phone, $email, $location_id),
        );
    }

    /**
     * Insère le nouveau tournoi dans la base de données.
     * 
     * @param  string     $name             Le nom du tournoi.
     * @param  string     $date             La date de début.
     * @param  string     $end_inscription  La date de fin des inscriptions.
     * @param  int        $duration         La durée en jours.
     * @param  int        $manager_id       Le gestionnaire de tournoi affecté. 
     * @param  int        $location_id      L'identifiant de la localisation de tournoi. 
     * @param  int        $type_id          Le type de tournoi.
     * @return int                          L'identifiant du tournois inséré.
     * @throws \Exception Si le tournoi n'a pas pu être inséré dans la base de données.
     * 
     * @author Johann Rosain
     */
    public function insertTournament(string $name, string $date, string $end_inscription, int $duration, int $manager_id, int $location_id, int $type_id) : int {
        $query = $this->dbh->prepare(
            'INSERT INTO tournament(name, start_date, end_inscription, duration_in_day, manager_id, location_id, tournament_type_id)
             VALUES(:name, :date, :end, :duration, :manager, :location, :type)'
        );
        
        $query->bindValue(':name', $name);
        $query->bindValue(':date', $date);
        $query->bindValue(':end', $end_inscription);
        $query->bindValue(':duration', $duration);
        $query->bindValue(':manager', $manager_id);
        $query->bindValue(':location', $location_id);
        $query->bindValue(':type', $type_id);

        if (false === $query->execute()) {
            throw new \Exception('Le tournoi n\'a pas pu être inséré dans la base de données.');
        }

        return $this->dbh->lastInsertId();
    }

    /**
     * Insère la nouvelle équipe dans la base de données.
     * 
     * @param  string      $name            Le nom de l'équipe.
     * @param  int         $level           Le niveau de l'équipe.
     * @param  string|null $profile_picture La photo de profil de l'équipe.
     * @param  bool        $active          Si l'équipe est activée ou non. 
     * @param  int         $captain_id      L'identifiant du capitaine de l'équipe. 
     * @param  int         $contact_id      L'dentifiant du contact de l'équipe.
     * @return int                          L'identifiant de l'équipe insérée.
     * @throws \Exception  Si l'équipe n'a pas pu être insérée dans la base de données.
     * 
     * @author Johann Rosain
     */
    public function insertTeam(string $name, int $level, string|null $profile_picture, bool $active, int $captain_id, int $contact_id) : int {
        $query = $this->dbh->prepare(
            'INSERT INTO team(name, level, profile_picture, active, captain, contact_id)
             VALUES(:name, :level, :profile_picture, :active, :captain, :contact)'
        );
        
        $query->bindValue(':name', $name);
        $query->bindValue(':level', $level);
        $query->bindValue(':profile_picture', $profile_picture);
        $query->bindValue(':active', $active);
        $query->bindValue(':captain', $captain_id);
        $query->bindValue(':contact', $contact_id);

        if (false === $query->execute()) {
            throw new \Exception('L\'équipe n\'a pas pu être insérée dans la base de données.');
        }

        return $this->dbh->lastInsertId();
    }

    /**
     * Insère un nouveau match dans la base de données.
     * 
     * @param  int|null     $team1_id       L'identifiant de la première équipe, il peut être nul si le match n'a pas encore de participant.
     * @param  int|null     $team2_id       L'identifiant de la deuxième équipe, il peut être nul si le match n'a pas encore de participant.
     * @param  int          $tournament_id  L'identifiant du tournoi où est ajouté le match.
     * @param  string       $date           La date du match. 
     * @param  string       $result         Le résultat du match.
     * @param  string       $parents_id     Une chaîne de caractère permettant de décoder les identifiants des matchs parents
     * @return int                          L'identifiant du match inséré.
     * @throws \Exception  Si le match n'a pas pu être inséré dans la base de données.
     * 
     * @author Benoît Huftier
     */
    public function insertMatchForTournament(int|null $team1_id, int|null $team2_id, int $tournament_id, string $date, string $result, string|null $parents_id) : int {
        $query = $this->dbh->prepare(
            'INSERT INTO team_match(team1_id, team2_id, tournament_id, date, result, parents_id)
             VALUES(:team1_id, :team2_id, :tournament_id, :date, :result, :parents_id)'
        );
        
        $query->bindValue(':team1_id', $team1_id);
        $query->bindValue(':team2_id', $team2_id);
        $query->bindValue(':tournament_id', $tournament_id);
        $query->bindValue(':date', $date);
        $query->bindValue(':result', $result);
        $query->bindValue(':parents_id', $parents_id);

        if (false === $query->execute()) {
            throw new \Exception('Le match n\'a pas pu être inséré dans la base de données.');
        }

        return $this->dbh->lastInsertId();
    }

    /**
     * Met à jour une équipe dans la base de données.
     * 
     * @param  int         $id              L'identifiant de l'équipe. 
     * @param  string      $name            Le nom de l'équipe.
     * @param  int         $level           Le niveau de l'équipe.
     * @param  string|null $profile_picture La photo de profil de l'équipe.
     * @param  int         $contact_id      L'dentifiant du contact de l'équipe.
     * @return int                          L'identifiant de l'équipe insérée.
     * @throws \Exception  Si l'équipe n'a pas pu être insérée dans la base de données.
     * 
     * @author Johann Rosain
     */
    public function updateTeam(int $id, string $name, int $level, string|null $profile_picture, int $contact_id) : void {
        $query = $this->dbh->prepare(
            'UPDATE team SET name = :name, level = :level, profile_picture = :profile_picture, contact_id = :contact
             WHERE id = :id'
        );
        
        $query->bindValue(':id', $id);
        $query->bindValue(':name', $name);
        $query->bindValue(':level', $level);
        $query->bindValue(':profile_picture', $profile_picture);
        $query->bindValue(':contact', $contact_id);

        if (false === $query->execute()) {
            throw new \Exception('L\'équipe n\'a pas pu être mise à jour dans la base de données.');
        }
    }

    private function insertIfNotExists(string $cache_key, string $query_exists, string $query_insert, array $exists_values, array $insert_values) : int {
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $query = $this->dbh->prepare($query_exists);
        $query->execute($exists_values);
        $result = $query->fetch();

        if (!empty($result)) {
            $result = $result['id'];
        }
        else {
            $query = $this->dbh->prepare($query_insert);
            $query->execute($insert_values);
            $result = $this->dbh->lastInsertId();
        }
        
        $this->cache->set($cache_key, $result);
        return $result;
    }

    // ------------------------------------------------------------------------
    // Méthodes pour les tournois.

    /**
     * Récupère les données pour créer une Location depuis l'id fourni.
     * 
     * @param  int   $id L'id de la Location en bdd. 
     * @return array     Les données de la Location.
     * 
     * @author Johann Rosain
     */
    public function getLocation(int $id) : array {
        $cache_key = __FUNCTION__ . strval($id);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $query = $this->dbh->prepare(
            'SELECT 
                loc.address1, loc.address2,
                zip.code,
                city.name
             FROM 
                location loc
                INNER JOIN zip_code zip ON (loc.zip_code_id = zip.id)
                INNER JOIN city ON (zip.city_id = city.id)
             WHERE loc.id = :id'
        );
        $query->bindValue(':id', $id);

        $query->execute();
        $result = $query->fetch();

        if (!$result) {
            $result = array();
        }

        $this->cache->set($cache_key, $result);
        return $result;
    }

    /**
     * Récupère les données nécessaires pour créer tous les tournois, notamment :
     *   - Les attributs du tournoi
     *   - Les attributs du lieu
     * Les critères doivent être formatés comme ceci :
     * array(
     *     'starting-date' => array('<|<=|==|>=|>', date-string),
     *     'name' => string,
     *     'duration' => array('<|<=|==|>=|>', int),
     *     'type' => string, 
     *     'department' => int, 
     *     'city' => string
     * )
     * Ces critères se combinent en AND.
     * 
     * @param  array $conds Les conditions de récupération des tournois. Doit être formaté correctement.
     * @param  int   $max   Le nombre max de tournois voulus. Défault = 10
     * @param  int   $page  La page voulue. Défault = 1
     * @return array        Tous les tournois qui répondent aux critères.
     * 
     * @author Johann Rosain
     */
    public function getTournaments(array $conds, int $max = 10, int $page = 1) : array {
        $cache_key = __FUNCTION__ . json_encode($conds) . strval($max) . strval($page);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        // La requête qui suit ne marche pas si les attributs sont émulés, pour une raison inconnue.
        $this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

        $conds = $this->processTournamentConds($conds);
        $where = $conds['where'];
        $values = $conds['values'];

        $query = $this->dbh->prepare(
            "SELECT
                t.id AS tournament_id, t.name AS tournament_name, t.start_date, t.end_inscription, t.duration_in_day AS duration, t.manager_id AS manager,
                tt.label AS type,
                l.id AS location_id, l.address1 AS address1, l.address2 AS address2,
                zip.code AS code,
                city.name AS city_name
             FROM 
                tournament t
                INNER JOIN location l ON (t.location_id = l.id)
                INNER JOIN tournament_type tt ON (t.tournament_type_id = tt.id)
                INNER JOIN zip_code zip ON (l.zip_code_id = zip.id)
                INNER JOIN city ON (zip.city_id = city.id)
             $where
             ORDER BY t.start_date ASC
             LIMIT ?
             OFFSET ?"
        );

        $offset = abs($page - 1) * $max;

        $values[] = $max;
        $values[] = $offset;

        $result = array();

        if ($query->execute($values)) {
            foreach ($query->fetchAll() as $tournament) {
                $result[] = $tournament;
            }
        }

        // La requête précédente ne marche pas si les attributs sont émulés, pour une raison inconnue.
        $this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

        $this->cache->set($cache_key, $result);
        return $result;
    }

    /**
     * Récupère le nombre de tournois qui remplissent les critères suivants :
     * array(
     *     'starting-date' => array('<|<=|==|>=|>', date-string),
     *     'name' => string,
     *     'duration' => array('<|<=|==|>=|>', int),
     *     'type' => string, 
     *     'department' => int, 
     *     'city' => string
     * )
     * Ces critères se combinent en AND.
     * 
     * @param  array $conds Les conditions de récupération des tournois. Doit être formaté correctement.
     * @return array        Le nombre de tournois qui répondent aux critères.
     * 
     * @author Johann Rosain
     */
    public function countTournaments(array $conds) : int {
        $cache_key = __FUNCTION__ . json_encode($conds);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $conds = $this->processTournamentConds($conds);
        $where = $conds['where'];
        $values = $conds['values'];

        $query = $this->dbh->prepare(
            "SELECT
                COUNT(*)
             FROM
                tournament t
                INNER JOIN location l ON (t.location_id = l.id)
                INNER JOIN tournament_type tt ON (t.tournament_type_id = tt.id)
                INNER JOIN zip_code zip ON (l.zip_code_id = zip.id)
                INNER JOIN city ON (zip.city_id = city.id)
             $where"
        );

        $query->execute($values);
        $result = $query->fetch();

        if (false === $result) {
            $result = 0;
        }
        else {
            $result = $result[0];
        }

        $this->cache->set($cache_key, $result);
        return $result;
    }

    private function processTournamentConds(array $conds) : array {
        $cache_key = __FUNCTION__ . json_encode($conds);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $str = 'WHERE 1 ';
        $values = array();

        $corr = array(
            'starting-date' => 't.start_date',
            'ending-date' => 't.start_date',
            'duration' => 't.duration_in_day',
            'name' => 't.name',
            'type' => 'tt.label',
            'department' => 'zip.code',
            'city' => 'city.name',
            'status' => 't.start_date', /* Ligne un peu spéciale, vu que pas dans la base de données. */
            'manager' => 't.manager_id',
            'filter' => 't.id'
        );

        foreach ($conds as $k => $v) {
            if (array_key_exists($k, $corr)) {
                $str .= 'AND ';
                if (in_array($k, array('starting-date', 'duration'), $strict = true)) {
                    $str .= "{$corr[$k]} {$v[0]} ? ";
                    $values[] = $v[1];
                }
                else if ('ending-date' === $k) {
                    $str .= " DATE_ADD({$corr[$k]}, INTERVAL (t.duration_in_day - 1) DAY) {$v[0]} DATE(?) ";
                    $values[] = $v[1];
                }
                else if (in_array($k, array('name', 'type', 'city', 'department'), $strict = true)) {
                    $str .= "{$corr[$k]} LIKE ? ";
                    $values[] = '%' . $v . '%';
                }
                else if ('status' === $k) {
                    $status = \Status::fromString($v);
                    switch ($status) {
                    case \Status::PreRegistrations :
                        $str .= 'NOW() < t.end_inscription ';
                        break;
                    case \Status::Forthcoming :
                        $str .= "NOW() < {$corr[$k]} ";
                        break;
                    case \Status::Finished :
                        $str .= "NOW() > DATE_ADD({$corr[$k]}, INTERVAL t.duration_in_day DAY) ";
                        break;
                    default :
                        $str .= "NOW() BETWEEN {$corr[$k]} AND DATE_ADD({$corr[$k]}, INTERVAL (t.duration_in_day - 1) DAY) ";
                        break;
                    }
                }
                else if ('filter' === $k) {
                    $str .= "{$corr[$k]} IN (";
                    foreach (array_values($v) as $index => $id) {
                        $str .= '?';
                        if ($index !== count($v) - 1) {
                            $str .= ', ';
                        }
                        $values[] = $id;
                    }
                    $str .= ') ';
                }
                else {
                    $str .= "{$corr[$k]} = ? ";
                    $values[] = $v;
                }
            }
        }

        $result = array(
            'where' => $str,
            'values' => $values
        );

        $this->cache->set($cache_key, $result);
        return $result;
    }

    /**
     * Récupère tous les gestionnaires de tournoi.
     * 
     * @return array Un tableau avec tous les gestionnaires.
     * 
     * @author Johann Rosain
     */
    public function getAllManagers() : array {
        if ($this->cache->isValid(__FUNCTION__)) {
            return $this->cache->get(__FUNCTION__);
        }

        $query = $this->dbh->prepare(
            'SELECT 
                u.id, u.name 
             FROM 
                user_role ur 
                INNER JOIN user u ON (ur.user_id = u.id) 
                INNER JOIN role r ON (ur.role_id = r.id) 
             WHERE 
                r.label = :label'
        );

        $query->bindValue(':label', \Role::toString(\Role::Manager));

        $query->execute();

        $result = array();

        foreach ($query->fetchAll() as $record) {
            $result[$record['id']] = $record['name'];
        }

        $this->cache->set(__FUNCTION__, $result);
        return $result;
    }

    /**
     * Récupère un tournoi avec son id.
     * 
     * @param  int   $id L'id du tournoi à récupérer 
     * @return array     Les informations de ce tournoi.
     * 
     * @author Johann Rosain
     */
    public function getTournament(int $id) : array {
        $cache_key = __FUNCTION__ . strval($id);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $query = $this->dbh->prepare(
            'SELECT
                t.name AS tournament_name, t.start_date, t.end_inscription, t.duration_in_day AS duration, t.manager_id AS manager,
                tt.label AS type,
                l.id AS location_id, l.address1 AS address1, l.address2 AS address2,
                zip.code AS code,
                city.name AS city_name
             FROM 
                tournament t
                INNER JOIN location l ON (t.location_id = l.id)
                INNER JOIN tournament_type tt ON (t.tournament_type_id = tt.id)
                INNER JOIN zip_code zip ON (l.zip_code_id = zip.id)
                INNER JOIN city ON (zip.city_id = city.id)
             WHERE t.id = :id'
        );
        $query->bindValue(':id', $id);

        $query->execute();
        $result = $query->fetch();

        if (!$result) {
            $result = array();
        }

        $this->cache->set($cache_key, $result);
        return $result;
    }

    /**
     * Récupère l'id de toutes les équipes qui veulent s'inscrire au tournoi donné.
     * 
     * @param  int   $tournament_id L'id du tournoi pour lequel chercher.
     * @return array                L'id de toutes les équipes, ainsi que la date de postulat et le status.
     * 
     * @author Johann Rosain
     */
    public function getAllPreinscriptions(int $tournament_id) : array {
        $cache_key = __FUNCTION__ . strval($tournament_id);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $query = $this->dbh->prepare(
            'SELECT 
                team_id,
                postulate_date,
                statut
             FROM 
                postulate_tournament
             WHERE
                tournament_id = :id
            '
        );
        $query->bindValue(':id', $tournament_id, \PDO::PARAM_INT);

        $query->execute();

        $result = array();
        foreach ($query->fetchAll() as $record) {
            $result[$record['team_id']] = array(
                'id' => $record['team_id'],
                'date' => $record['postulate_date'],
                'status' => $record['statut']
            );
        }

        $this->cache->set($cache_key, $result);
        return $result;
    }

    /**
     * @param  int  $id L'id du joueur a tester. 
     * @return bool     Vrai si un joueur est capitaine d'au moins une équipe.
     * 
     * @author Johann Rosain
     */
    public function isCaptain(int $id) : bool {
        $cache_key = __FUNCTION__ . strval($id);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $query = $this->dbh->prepare(
            'SELECT 
                COUNT(*) AS n_captain
             FROM 
                team
             WHERE
                captain = :id
            '
        );
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch()['n_captain'] > 0;

        $this->cache->set($cache_key, $result);
        return $result;
    }

    /**
     * Récupère toutes les équipes où le capitaine est $player_id
     * 
     * @param  int   $player_id L'id du capitaine.
     * @return array            Les équipes organisés dans un tableau d'id => nom
     * 
     * @author Johann Rosain
     */
    public function getTeamsWhereCaptainIs(int $player_id) : array {
        $cache_key = __FUNCTION__ . strval($player_id);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $query = $this->dbh->prepare(
            'SELECT 
                id, name
             FROM 
                team
             WHERE
                captain = :id
            '
        );
        $query->bindValue(':id', $player_id, \PDO::PARAM_INT);
        $query->execute();
        $result = array();

        foreach ($query->fetchAll() as $record) {
            $result[$record['id']] = $record['name'];
        }

        $this->cache->set($cache_key, $result);
        return $result;
    }

    /**
     * Préinscrit une équipe au tournoi.
     * 
     * @param  int $tournament_id L'id du tournoi auquel l'équipe veut s'inscrire.
     * @param  int $team_id       L'id de l'équipe à préinscrire.
     * 
     * @author Johann Rosain
     */
    public function registerTournament(int $tournament_id, int $team_id) : void {
        $query = $this->dbh->prepare('INSERT INTO postulate_tournament(team_id, tournament_id, postulate_date, statut) VALUES(:team_id, :tournament_id, NOW(), :statut);');
        $query->bindValue(':tournament_id', $tournament_id, \PDO::PARAM_INT);
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->bindValue(':statut', \Postulate::Pending, \PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Supprime le postulat d'une équipe au tournoi.
     * 
     * @param  int $tournament_id L'id du tournoi auquel l'équipe est inscrite/en attente/bloqué.
     * @param  int $team_id       L'id de l'équipe.
     * 
     * @author Johann Rosain
     */
    public function deleteRegistration(int $tournament_id, int $team_id) : void {
        $query = $this->dbh->prepare('DELETE FROM postulate_tournament WHERE team_id = :team_id AND tournament_id = :tournament_id;');
        $query->bindValue(':tournament_id', $tournament_id, \PDO::PARAM_INT);
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Change le type de l'inscription d'une équipe au tournoi.
     * 
     * @param  int    $tournament_id L'id du tournoi auquel l'équipe à un postulat.
     * @param  int    $team_id       L'id de l'équipe qui doit modifier son postulat.
     * @param  string $type          Le type de postulat qu'il faut mettre à l'équipe
     * 
     * @author Benoît Huftier
     */
    public function updateRegistration(int $tournament_id, int $team_id, string $type) : void {
        $query = $this->dbh->prepare('UPDATE postulate_tournament SET statut = :statut WHERE team_id = :team_id AND tournament_id = :tournament_id');
        $query->bindValue(':tournament_id', $tournament_id, \PDO::PARAM_INT);
        $query->bindValue(':team_id', $team_id, \PDO::PARAM_INT);
        $query->bindValue(':statut', $type, \PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Récupère tous les tournois auquel le joueur $player_id a participé.
     * 
     * @param  int   $player_id L'id du joueur à récupérer.
     * @return array            Un tableau avec les id des tournois.
     * 
     * @author Johann Rosain
     */
    public function getTournamentWherePlayerParticipated(int $player_id) : array {
        return $this->getTournamentWhereParticipated('player_id', $player_id);
    }

    /**
     * Récupère tous les tournois auquel l'équipe $team_id a participé.
     * 
     * @param  int   $team_id   L'id de l'équipe à récupérer.
     * @return array            Un tableau avec les id des tournois.
     * 
     * @author Johann Rosain
     */
    public function getTournamentWhereTeamParticipated(int $team_id) : array {
        return $this->getTournamentWhereParticipated('team_id', $team_id);
    }

    private function getTournamentWhereParticipated(string $field, int $id) : array {
        $cache_key = __FUNCTION__ . $field . ' ' . strval($id);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $query = $this->dbh->prepare(
            "SELECT
                tournament_id
             FROM
                postulate_tournament
             WHERE
                team_id IN (
                    SELECT 
                        team_id
                    FROM
                        player_team
                    WHERE
                        $field = :id
                ) AND statut = :statut"
        );
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->bindValue(':statut', \Postulate::Accepted, \PDO::PARAM_STR);
        $query->execute();

        $result = array();
        foreach ($query->fetchAll() as $record) {
            $result[] = intval($record['tournament_id']);
        }

        $this->cache->set($cache_key, $result);
        return $result;
    }

    /**
     * Modifie un tournois dans la base de données.
     * 
     * @param  int        $tournament_id    L'identifiant du tournoi.
     * @param  string     $name             Le nom du tournoi.
     * @param  string     $date             La date de début.
     * @param  string     $end_inscription  La date de fin des inscriptions.
     * @param  int        $duration         La durée en jours.
     * @param  int        $location_id      L'identifiant de la localisation de tournoi.
     * 
     * @author Benoît Huftier
     */
    public function updateTournament(int $tournament_id, string $name, string $date, string $end_inscription, int $duration, int $location_id) : void {
        $query = $this->dbh->prepare(
            'UPDATE tournament SET
                name = :name,
                start_date = :date,
                end_inscription = :end,
                duration_in_day = :duration,
                location_id = :location
             WHERE id = :id'
        );
        
        $query->bindValue(':name', $name);
        $query->bindValue(':date', $date);
        $query->bindValue(':end', $end_inscription);
        $query->bindValue(':duration', $duration);
        $query->bindValue(':location', $location_id);
        $query->bindValue(':id', $tournament_id);

        $query->execute();
    }

    /**
     * Modifie la date de fin des preinscriptions dans un tournois
     * 
     * @param int          $tournament_id        L'identifiant du tournois.
     * @param string       $end_inscription_date La date jusqu'à laquelle les joueurs peuvent s'inscrire.
     * 
     * @author Benoît Huftier
     */
    public function changePreinscriptionsEndDateForTournament(int $tournament_id, string $end_inscription_date) : void {
        $query = $this->dbh->prepare('UPDATE tournament SET end_inscription = :date WHERE id = :id;');
        
        $query->bindValue(':date', $end_inscription_date);
        $query->bindValue(':id', $tournament_id);
        $query->execute();
    }

    /**
     * Récupère tous les matchs d'un tournoi.
     * 
     * @param  int   $tournament_id L'id du tournoi où il faut les matchs.
     * @return array                Un tableau avec tous les matchs du tournoi.
     * 
     * @author Benoît Huftier
     */
    public function getMatchesForTournament(int $tournament_id) : array {
        $cache_key = __FUNCTION__ . strval($tournament_id);
        if ($this->cache->isValid($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $query = $this->dbh->prepare(
            'SELECT *
             FROM team_match
             WHERE tournament_id = :id'
        );

        $query->bindValue(':id', $tournament_id, \PDO::PARAM_INT);
        $query->execute();

        $result = array();
        foreach ($query->fetchAll() as $record) {
            $result[$record['id']] = $record;
        }

        $this->cache->set($cache_key, $result);
        return $result;
    }

    /**
     * Supprime les matchs du tournoi donné.
     * 
     * @param int $tournament_id L'identifiant du tournoi où les matchs doivent être supprimé
     * 
     * @author Benoît Huftier
     */
    public function deleteMatchesForTournament(int $tournament_id) : void {
        $query = $this->dbh->prepare('DELETE FROM team_match WHERE tournament_id = :tournament_id');
        $query->bindValue(':tournament_id', $tournament_id, \PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Modifie un match dans la base de données.
     * 
     * @param  int        $tournament_id    L'identifiant du tournoi.
     * @param  int        $match_id         L'identifiant du match.
     * @param  int        $team1_id         L'identifiant de la première équipe du match.
     * @param  int        $team2_id         L'identifiant de la seconde équipe du match.
     * @param  string     $result           Le résultat du match.
     * @param  string     $date             La date de match.
     * 
     * @author Benoît Huftier
     */
    public function updateMatchForTournament(int $tournament_id, int $match_id, int|null $team1_id, int|null $team2_id, string $result, string $date) : void {
        $query = $this->dbh->prepare(
            'UPDATE team_match SET
                team1_id = :team1_id,
                team2_id = :team2_id,
                date = :date,
                result = :result
             WHERE id = :id
             AND tournament_id = :tournament_id'
        );
        
        $query->bindValue(':team1_id', $team1_id);
        $query->bindValue(':team2_id', $team2_id);
        $query->bindValue(':date', $date);
        $query->bindValue(':result', $result);
        $query->bindValue(':id', $match_id);
        $query->bindValue(':tournament_id', $tournament_id);

        $query->execute();
    }

    public function beginTransaction() : void {
        $this->dbh->beginTransaction();
    }

    public function rollBack() : void {
        $this->dbh->rollBack();
    }

    public function commit() : void {
        $this->dbh->commit();
    }

    // ------------------------------------------------------------------------
    // Modification des rôles.

    /**
     * Modifie les rôles de l'utilisateur donné pour ajouter ou supprimer ceux choisis.
     * 
     * @param  int   $u_id         L'id de l'utilisateur
     * @param  array $roles_to_add Les roles à ajouter.
     * @param  array $roles_to_del Les roles à supprimer.
     * 
     * @author Johann Rosain
     */
    public function modifyRoles(int $u_id, array $roles_to_add, array $roles_to_del) : void {
        $this->beginTransaction();

        try {
            foreach ($roles_to_add as $add) {
                $add_query = $this->dbh->prepare('INSERT INTO user_role VALUES(:uid, (SELECT id FROM role WHERE label = :label))');
                $add_query->bindValue(':uid', $u_id);
                $add_query->bindValue(':label', $add);
                $add_query->execute();
            }

            if (!empty($roles_to_del)) {
                $del_query_str = 'DELETE FROM user_role WHERE user_id = ? AND role_id IN (SELECT id FROM role WHERE label IN (' 
                    . implode(', ', array_fill(0, count($roles_to_del), '?')) . '))';
                $del_query = $this->dbh->prepare($del_query_str);
                $del_query->execute(array_merge(array($u_id), $roles_to_del));
            }
    

            if (in_array(\Role::toString(\Role::Player), $roles_to_add, $strict = true)) {
                $query = $this->dbh->prepare('INSERT INTO player(description, user_id) VALUES(\'\', :uid);');
                $query->bindValue(':uid', $u_id);
                $query->execute();
            }

            if (in_array(\Role::toString(\Role::Player), $roles_to_del, $strict = true)) {
                $query = $this->dbh->prepare('DELETE FROM player WHERE user_id = :uid');
                $query->bindValue(':uid', $u_id);
                $query->execute();
            }

            $this->commit();
        }
        catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    // ------------------------------------------------------------------------
    // Méthodes privées communes.

    /**
     * Parse un retour (simple) d'une requête de la base de données pour ne garder
     * que chaque ligne en forme de tableau.
     * 
     * @return array Toutes les lignes de l'enregistrement passé. 
     * 
     * @author Johann Rosain
     */
    private function parseFetchedArray(array $fetched_array) : array {
        $parsed_array = array();
        foreach ($fetched_array as $nested_array) {
            $current_value = array();
            foreach ($nested_array as $key => $value) {
                if (!is_int($key)) {
                    $current_value[] = $value;
                }
            }
            $parsed_array[] = $current_value;
        }
        return $parsed_array;
    }

    /**
     * Création d'une liste de ? pour une lisaison SQL en fonction de la taille d'un
     * tableau.
     * 
     * Le retour est une chaîne de la forme (?, ?, ?, ..., ?) avec un nombre de ? égal
     * à la taille du tableau.
     * Elle est faite dans le but d'être mis à l'intérieure d'une query SQL.
     * 
     * N'oubliez pas d'appeler bindFromArray avec le même tableau pour lier toutes les
     * valeurs.
     * 
     * @param array $array Le tableau dont la taille est utilisé
     * @return string      La chaîne de binding pour insérer dans la requête
     * 
     * @see bindFromArray
     * 
     * @author Benoît Huftier
     */
    private function createBindingFromArray(array $array) : string {
        $ids = array_fill(0, count($array), '?');
        return '(' . implode(', ', $ids) . ')';
    }

    /**
     * Lie toutes les valeurs d'un tableau à une requête SQL.
     * 
     * La requête doit posséder autant de "?" que la taille du tableau.
     * Les éléments du tableau doivent tous être du même type pour une liaison correcte.
     * 
     * Pour créer autant de "?" que la taille d'un tableau, il faut utiliser la méthode
     * createBindingFromArray.
     * 
     * @param \PDOStatement& $query  La requete préparée mais non executée.
     * @param arrat          $values Le tableau contenant les valeurs à lier.
     * @param int            $type   Le type des valeurs du tableau, doit correspondre à 
     *                               une constante PDO. Default à \PDO::PARAM_STR.
     * @param int            $n      Le numéro de la première valeur à lier. Il est possible
     *                               que la requête possède d'autres liaisons. La valeur par
     *                               défaut est 1, ce qui signifie qu'aucune autre liaison
     *                               n'a été faite avant. 
     *
     * @author Benoît Huftier
     */
    private function bindFromArray(\PDOStatement &$query, array $values, int $type = \PDO::PARAM_STR, int $count = 1) : void {
        // La référence est obligatoire pour bien lier les valeurs
        foreach ($values as &$val) {
            $query->bindParam($count, $val, $type);
            $count++;
        }
    }
}