<?php 

/**
 * Lit le fichier tables.sql et le met dans une chaîne en enlevant les 
 * commentaires et espaces inutiles.
 * 
 * @return string Schéma de la base de données.
 * 
 * @author Johann Rosain.
 */
function ph_get_db_schema() : string {
    return ph_read_sql_file(__DIR__ . '/tables.sql');
}

/**
 * Récupère le nom d'une table depuis une ligne d'un schéma sql.
 * 
 * @param  string $line Ligne du schéma a analyser.
 * @return string       Le nom de la table, chaîne vide s'il n'y en a pas.
 * 
 * @author Johann Rosain
 */
function ph_get_table_name_from_schema(string $line) : string {
    preg_match('/^CREATE TABLE ([A-Za-z_\-]+)\(.+\);?$/', $line, $match);
    return isset($match[1]) ? $match[1] : '';
}

/**
 * Lit le fichier data.sql et le met dans une chaîne en enlevant les 
 * commentaires et espaces inutiles.
 * 
 * @return string Données par défaut à entrer dans la base de données.
 * 
 * @author Johann Rosain.
 */
function ph_get_default_db_values() : string {
    return ph_read_sql_file(__DIR__ . '/data.sql');
}

/**
 * Lit le fichier placeholder.sql et le met dans une chaîne en enlevant les 
 * commentaires et espaces inutiles.
 * 
 * @return string Données à entrer dans la base de données pour avoir des
 *                valeurs par défauts.
 * 
 * @author Benoit Huftier.
 */
function ph_get_placeholder_db_values() : string {
    return ph_read_sql_file(__DIR__ . '/placeholder.sql');
}

/**
 * Lit le fichier sql $filename et renvoie ses données dans une chaîne qui
 * peut être une requête sql. 
 * 
 * @return string Requête contenue dans le fichier sql.
 * 
 * @author Johann Rosain.
 */
function ph_read_sql_file(string $filename) : string {
    $content = file_get_contents($filename);
    $lines = preg_split('/((\r?\n)|(\r\n?))/', $content);
    $result = '';
    foreach ($lines as $line) {
        $pos = strpos($line, '--');
        if (false !== $pos) {
            $line = substr($line, 0, $pos);
        }
        if (!empty($line)) {
            $result .= trim($line);
        }
    }
    return $result;
}

/**
 * Récupère le nom d'une table depuis une ligne d'une insertion sql.
 * 
 * @param  string $line Ligne du schéma a analyser.
 * @return string       Le nom de la table, chaîne vide s'il n'y en a pas.
 * 
 * @author Johann Rosain
 */
function ph_get_table_name_from_row(string $line) : string {
    preg_match('/^INSERT INTO ([A-Za-z_\-]+)(\(.+\) | )VALUES\(.+\);?$/', $line, $match);
    return isset($match[1]) ? $match[1] : '';
}

/**
 * Récupère la valeur des attributs à insérer depuis une ligne de requête sql.
 * 
 * @param  string $line Ligne du schéma à analyser.
 * @return array        Toutes les valeurs à insérer.
 * 
 * @author Johann Rosain 
 */
function ph_get_row_values(string $line) : array {
    $values = array();
    preg_match("/^INSERT INTO [A-Za-z_\-]+(\(.+\) | )VALUES\(([^)]+)\);?$/", $line, $match);
    if (!empty($match[2])) {
        $values = explode(', ', str_replace("'", "", $match[2]));
        foreach ($values as $key => &$value) {
            if ('null' === strtolower($value)) {
                $value = null;
            }
        }
    }
    return $values;
}

/**
 * Récupère tous les attributs d'une table depuis une ligne de requête de création.
 * 
 * @param  string $line Ligne du schéma à analyser
 * @return array        Tous les attributs de la table.
 * 
 * @author Johann Rosain
 */
function ph_get_attributes_from_schema(string $line) : array {
    $exclude_rules = array(
        'PRIMARY KEY',
    );

    $values = array();
    preg_match('/^CREATE TABLE [A-Za-z_\-]+\((.+)+\);?$/', $line, $match);
    if (!empty($match[1])) {
        $aux = explode(',', $match[1]);
        foreach ($aux as $v) {
            $exclude = false;
            foreach ($exclude_rules as $rule) {
                if (str_contains($v, $rule)) {
                    $exclude = true;
                    break;
                }
            }
            if (!$exclude) {
                $attr = explode(' ', $v)[0];
                if (!empty($attr)) {
                    $values[$attr] = substr($v, strlen($attr));
                }
            }
        }
    }

    return $values;
}

/**
 * Récupère le premier nom disponible pour dump la base de données dans un 
 * fichier dans le dossier actuel.
 * 
 * @return string Chemin vers le fichier de dump.
 * 
 * @author Johann Rosain
 */
function ph_get_next_dump() : string {
    $dump = __DIR__ . '/dump.sql';
    $i = 1;
    while (file_exists($dump)) {
        $dump = __DIR__ . "/dump_$i.sql";
        $i++;
    }
    return $dump;
}