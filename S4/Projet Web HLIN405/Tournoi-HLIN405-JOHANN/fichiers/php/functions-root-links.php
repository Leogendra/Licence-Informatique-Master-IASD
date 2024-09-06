<?php

if (!defined('ABSPATH')) {
    exit;
}

// ----------------------------------------------------------------------------
// Définition des constantes pour les assets

define('ASSETS_DIR', ROOT . '/assets');
define('CSS_DIR', ASSETS_DIR . '/css');
define('JS_DIR', ASSETS_DIR . '/js');
define('RESOURCES_DIR', ASSETS_DIR . '/resources');
define('UPLOADS_DIR', ASSETS_DIR . '/uploads');

/**
 * @param  string $filename Chemin d'un fichier css depuis le dossier des assets css.
 * @return string           Chemin relatif du fichier css à inclure.
 * 
 * @author Benoît Huftier
 */
function ph_get_css_link(string $filename) : string {
    return CSS_DIR . '/' . $filename;
}

/**
 * @param  string $filename Chemin d'un fichier js depuis le dossier des assets js.
 * @return string           Chemin relatif du fichier js à inclure.
 * 
 * @author Benoît Huftier
 */
function ph_get_js_link(string $filename) : string {
    return JS_DIR . '/' . $filename;
}

/**
 * @param  string $filename Chemin d'un fichier ressource (images, json, etc) depuis le
 *                          dossier des ressources.
 * @return string           Chemin relatif de la ressource à inclure.
 * 
 * @author Benoît Huftier
 */
function ph_get_resource_link(string $filename) : string {
    return RESOURCES_DIR . '/' . $filename;
}

/**
 * @param  string $filename Chemin d'un fichier ressource (images, json, etc) depuis le
 *                          dossier des uploads (généralement donné par la bdd).
 * @return string           Chemin relatif de la ressource à inclure.
 * 
 * @author Benoît Huftier
 */
function ph_get_upload_link(string $filename) : string {
    return UPLOADS_DIR . '/' . $filename;
}

/**
 * @param  string $filename Chemin du fichier depuis la racine du site.
 * @param  array  $args     Arguments à ajouter en get sous forme de tableaux.
 * @return string           Route choisie pour le fichier en question.
 * 
 * @author Benoît Huftier
 */
function ph_get_route_link(string $filename, array $args = array()) : string {
    $extension_pos = strrpos($filename, ".");
    $filename = substr($filename, 0, $extension_pos);

    // L'index c'est la page principale
    if ('index' === $filename) {
        $filename = '';
    }

    $id_routes = array(
        'team',
        'tournament',
        'manage-tournament',
    );

    // Les pages de tournoi et d'équipe avec leur identifiant
    if (in_array($filename, $id_routes, $strict = true) && array_key_exists('id', $args)) {
        $filename .= '/' . $args['id'];
        unset($args['id']);
    }

    foreach ($args as $key => &$value) {
        $value = $key . '=' . $value;
    }

    $args = implode('&', $args);

    return ROOT . '/' . $filename . (empty($args) ? '' : '?' . $args);
}

/**
 * @param  string $filename  Chemin d'un fichier css depuis le dossier des assets css.
 * @return Core\Tags\LinkCSS Objet CSS à inclure dans une page
 * 
 * @author Benoît Huftier
 */
function ph_create_css_object(string $filename) : Core\Tags\LinkCSS {
    $filename = ph_get_css_link($filename);
    return new Core\Tags\LinkCSS($filename);
}

/**
 * @param  string $filename   Chemin d'un fichier js depuis le dossier des assets js.
 * @return Core\Tags\ScriptJS Objet JS à inclure dans une page
 * 
 * @author Benoît Huftier
 */
function ph_create_js_object(string $filename) : Core\Tags\ScriptJS {
    $filename = ph_get_js_link($filename);
    return new Core\Tags\ScriptJS($filename);
}