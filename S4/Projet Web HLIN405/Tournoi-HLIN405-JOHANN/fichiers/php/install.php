<?php

/**
 * Charge le fichier de base de données et crée la variable globale $phdb.
 * 
 * @author Johann Rosain
 */
function require_ph_db() : void {
    global $phdb;

    if (isset($phdb)) {
        return;
    }

    if ((!defined('DB_NAME') || empty(DB_NAME))
        || (!defined('DB_USER') || empty(DB_USER))
        || (!defined('DB_PASSWORD') || empty(DB_PASSWORD))
        || (!defined('DB_HOST') || empty(DB_HOST))
        || (!defined('DB_CHARSET') || empty(DB_CHARSET))
        || (!defined('DB_COLLATE') || empty(DB_COLLATE))) {
        ph_error_display('Erreur : une variable de base de données n\'a pas été configurée dans config.php');
    }

    $phdb = new PH\PHDB(DB_USER, DB_PASSWORD, DB_HOST, DB_NAME, DB_CHARSET, DB_COLLATE);
}

/**
 * Si la base de données est installée et existe, dit à $phdb de l'utiliser.
 * 
 * @author Johann Rosain
 */
function ph_set_db() : void {
    global $phdb;
    $phdb->useDb();
}

/**
 * Vérifie si la base de données a bien été installée sur le serveur.
 * 
 * @return bool Vrai si la base de données avec le nom spécifié existe.
 * 
 * @author Johann Rosain
 */
function ph_db_installed() : bool {
    global $phdb;
    return $phdb->exists();
}

/**
 * Installe la base de données sur le serveur spécifié. 
 * 
 * @author Johann Rosain
 */
function ph_install_db() : void {
    global $phdb;

    ph_exit_if_no_admin();

    // TODO: hasher le mot de passe administrateur ici.
    $create_super_user = 'INSERT INTO user(id, email, passwd, name) VALUES(1, \'' . ADMIN_EMAIL . '\', \'' . password_hash(ADMIN_PASSWD, PASSWORD_BCRYPT) . '\', \'' . ADMIN_NAME . '\');';
    $create_super_user .= 'INSERT INTO user_role VALUES(1, 1);';

    $phdb->createDatabase();
    $phdb->runInstallation(ph_get_db_schema() . ph_get_default_db_values() . $create_super_user);
}

/**
 * Vérifie si la base de données est complète, c'est à dire si toutes les tables
 * du fichier qui contient le schéma sont présentes dans la base de données
 * paramétrée dans config.php et inversement.
 * 
 * @return bool Vrai si les schémas ne correspondent pas.
 * 
 * @author Johann Rosain 
 */
function ph_db_differs() : bool {
    try {
        return strlen(ph_get_missing_tables()) > 0 
               || strlen(ph_get_unwanted_tables()) > 0
               || strlen(ph_get_missing_columns()) > 0
               || strlen(ph_get_unwanted_columns()) > 0;
    }
    catch (Exception $_) {
        ph_db_repair();
    }
    return false;
}

/**
 * Complète la base de données avec les tables qu'il manque.
 * 
 * @author Johann Rosain
 */
function ph_update_db_tables() : void {
    global $phdb;

    try {
        $update_schema = implode(
            ';', 
            array(
                ph_get_missing_tables(), 
                ph_get_unwanted_tables(), 
                ph_get_missing_columns(), 
                ph_get_unwanted_columns(),
        ));
        $phdb->runInstallation($update_schema);
    }
    catch (Exception $_) {
        ph_db_repair();
    }
}

/**
 * Récupère toutes les tables qu'il manque dans la base de données par rapport 
 * au schéma local.
 * 
 * @return string Schema de toutes les tables qui manquent similaire à celui
 *                produit par ph_get_db_schema().
 * 
 * @author Johann Rosain
 */
function ph_get_missing_tables() : string {
    global $phdb;

    $all_db_tables = $phdb->getAllTablesInfos();
    $all_schema_tables = array();

    foreach(array_filter(explode(';', ph_get_db_schema())) as $table) {
        if (!array_key_exists(ph_get_table_name_from_schema($table), $all_db_tables)) {
            $all_schema_tables[] = $table;
        }
    }

    return implode(';', $all_schema_tables);
}

/**
 * Récupère toutes les tables qu'il y a en trop dans la base de données par 
 * rapport au schéma local.
 * 
 * @return string Schema de toutes les tables qui sont en trop similaire à 
 *                celui produit par ph_get_db_schema().
 * 
 * @author Johann Rosain
 */
function ph_get_unwanted_tables() : string {
    global $phdb;

    $all_local_tables = array();
    foreach (array_filter(explode(';', ph_get_db_schema())) as $table) {
        $all_local_tables[] = ph_get_table_name_from_schema($table);
    }

    $all_schema_tables = array();
    foreach($phdb->getAllTablesInfos() as $table => $columns) {
        if (!in_array($table, $all_local_tables, $strict = true)) {
            $all_schema_tables[] = "DROP TABLE $table";
        }
    }

    return implode(';', $all_schema_tables);
}

/**
 * Récupère toutes les colonnes à ajouter qu'il manque dans la base de données 
 * au schéma local.
 * 
 * @return string Suite de requêtes pour altérer les tables auxquelles il 
 *                manquerait des colonnes
 * 
 * @author Johann Rosain
 */
function ph_get_missing_columns() : string {
    global $phdb;

    $all_db_tables = $phdb->getAllTablesInfos();
    $all_local_tables = array();

    foreach (array_filter(explode(';', ph_get_db_schema())) as $table) {
        $all_local_tables[ph_get_table_name_from_schema($table)] = ph_get_attributes_from_schema($table);
    }

    $alter_tables = array();

    foreach ($all_db_tables as $table => $attributes) {
        if (isset($all_local_tables[$table])) {
            foreach (array_keys($all_local_tables[$table]) as $attr) {
                if (!in_array($attr, $attributes, $strict = true)) {
                    $alter_tables[] = "ALTER TABLE $table ADD $attr " . $all_local_tables[$table][$attr];
                }
            }
        }
    }

    return implode(';', $alter_tables);
}

/**
 * Récupère toutes les colonnes qu'il y a en trop dans la base de données par 
 * rapport au schéma local.
 * 
 * @return string Suite de requêtes pour altérer les tables auxquelles il 
 *                y aurait trop de colonnes.
 * 
 * @author Johann Rosain
 */
function ph_get_unwanted_columns() : string {
    global $phdb;

    $all_db_tables = $phdb->getAllTablesInfos();
    $all_local_tables = array();

    foreach (array_filter(explode(';', ph_get_db_schema())) as $table) {
        $all_local_tables[ph_get_table_name_from_schema($table)] = array_keys(ph_get_attributes_from_schema($table));
    }
    
    $alter_tables = array();

    foreach ($all_db_tables as $table => $attributes) {
        if (isset($all_local_tables[$table])) {
            $local_attributes = $all_local_tables[$table];
            foreach ($attributes as $attr) {
                if (!in_array($attr, $local_attributes, $strict = true)) {
                    $alter_tables[] = "ALTER TABLE $table DROP COLUMN $attr";
                }
            }
        }
    }
    
    return implode(';', $alter_tables);
}

/**
 * Vérifie si les valeurs des tables suivantes :
 *  - role
 *  - tournament_type
 *  - outcome_type
 *  - score_tournament
 * Correspondent avec celles en local. De plus, vérifie qu'il existe un super
 * utilisateur avec la configuration donnée dans config.php.
 * 
 * @return bool Vrai si ça ne correspond pas ou que le compte n'est pas trouvé.
 * 
 * @author Johann Rosain 
 */
function ph_default_values_differs() : bool {
    try {
        return strlen(ph_get_missing_default_values()) > 0
            || strlen(ph_update_admin_if_changed()) > 0;
    }
    catch (Exception $_) {
        ph_db_repair();
    }
    return false;
}

/**
 * Complète la base de données avec les valeur par défaut manquantes.
 * 
 * @author Johann Rosain
 */
function ph_update_default_values() : void {
    global $phdb;

    try {
        $update_schema = ph_get_missing_default_values() . ';' . ph_update_admin_if_changed();
        $phdb->runInstallation($update_schema);
    }
    catch (Exception $_) {
        ph_db_repair();
    }
}

/**
 * Récupère toutes les valeurs par défault qu'il manque dans la base de données
 * par rapport au schéma local.
 * 
 * @return string Requête sql de toutes les valeurs par défault qu'il manque.
 * 
 * @author Johann Rosain
 */
function ph_get_missing_default_values() : string {
    global $phdb;

    $default_values = array(
        'role' => $phdb->getAllRoles(),
        'tournament_type' => $phdb->getAllTournamentTypes(),
        'outcome_type' => $phdb->getAllOutcomeTypes(),
        'score_tournament' => $phdb->getAllTournamentScores()
    );

    $all_missing_values = array();

    foreach(array_filter(explode(';', ph_get_default_db_values())) as $row) {
        $table_name = ph_get_table_name_from_row($row);
        if (!empty($table_name)) {
            $values = ph_get_row_values($row);
            // TODO : regarder pourquoi la bdd me renvoie des chaînes au lieu des entiers pour les id ici
            if (!in_array($values, $default_values[$table_name])) {
                $all_missing_values[] = $row;
            }
        }
    }

    return implode(';', $all_missing_values);
}

/**
 * Lit le fichier `placeholders.sql` et lance toutes les requêtes qui y sont.
 * Chaque tuple inséré dans la base de données est préalablement DELETE pour éviter tout conflit.
 * 
 * @throws \PDOException Si le fichier est mal configuré, cela peut être dû à un tuple non supprimé
 *                       ou si une requête est mal écrite.
 * 
 * @author Benoît Huftier
 */
function ph_update_placeholder_values() : void {
    global $phdb;

    $placeholders = explode(';', ph_get_placeholder_db_values());
    // Suppression des lignes vides.
    $placeholders = array_filter($placeholders);
    
    foreach($placeholders as $query) {
        $phdb->query($query);
    }
}

/**
 * Récupère la requête pour modifier le compte administrateur s'il diffère entre
 * la base de données et les paramètres locaux.
 * 
 * @return string Requête sql de l'altération du compte administrateur.
 * 
 * @author Johann Rosain
 */
function ph_update_admin_if_changed() : string {
    global $phdb;

    ph_exit_if_no_admin();

    $query = '';

    if ($phdb->adminDiffers(ADMIN_EMAIL, ADMIN_PASSWD, ADMIN_NAME)) {
        $query = 'UPDATE user SET email = \'' . ADMIN_EMAIL . '\', passwd = \'' . password_hash(ADMIN_PASSWD, PASSWORD_BCRYPT) . '\', name = \'' . ADMIN_NAME . '\' WHERE id = 1';
    }

    return $query;
}

/**
 * Affiche un message d'erreur si le compte admin n'est pas spécifié, et 
 * arrête le site.
 * 
 * @author Johann Rosain
 */
function ph_exit_if_no_admin() : void {
    if ((!defined('ADMIN_EMAIL') || empty(ADMIN_EMAIL))
        || (!defined('ADMIN_NAME') || empty(ADMIN_NAME))
        || (!defined('ADMIN_PASSWD') || empty(ADMIN_PASSWD))) {
        ph_error_display('Erreur : le compte administrateur n\'est pas configuré dans config.php');
    }
}

/**
 * Lances la page de réparation si le site est en développement. Sinon, renvoie
 * sur la page d'erreur interne du serveur.
 * 
 * @author Johann Rosain
 */
function ph_db_repair() : void {
    try {
        if ((defined('DEVELOPMENT') && true === DEVELOPMENT) || ph_get_user()->getPermissions()->hasFlag(Role::Administrator)) {
            if ($_SERVER['REDIRECT_URL'] !== ph_get_route_link('admin/repair.php') && $_SERVER['REQUEST_URI'] !== ph_get_route_link('error/forbidden.php')) {
                header('Location: ' . ph_get_route_link('admin/repair.php'));
                exit;
            }
        }
        else {
            ph_error_redirect(500);
        }
    }
    catch (Exception $e) {
        ph_error_redirect(500);
    }
}

/**
 * Crée un fichier de backup des données dans la bdd, wipe la bdd, et la recrée
 * à partir des fichiers dans php/database.
 * 
 * @return string Nom du fichier où les données de la bdd ont été sauvegardées.
 * 
 * @author Johann Rosain
 */
function ph_db_force_repair() : string {
    global $phdb;

    $dump = ph_get_next_dump();
    $phdb->dumpInto($dump);
    $phdb->dropDatabase();
    ph_install_db();

    return $dump;
}