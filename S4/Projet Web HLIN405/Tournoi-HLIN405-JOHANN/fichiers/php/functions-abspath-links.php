<?php

if (!defined('ABSPATH')) {
    exit;
}

// ----------------------------------------------------------------------------
// Définition des constantes pour les inclusions

define('INCLUDES_PATH', ABSPATH . '/includes');
define('HTML_PATH', ABSPATH . '/html');
define('UPLOAD_PATH', ABSPATH . '/public_html/assets/uploads');

/**
 * @param  string $filename Chemin du fichier depuis le dossier d'inclusion.
 * @return string           Chemin absolu complet du fichier à inclure.
 * 
 * @author Benoît Huftier
 */
function ph_include(string $filename) : string {
    return INCLUDES_PATH . '/' . $filename;
}

/**
 * @param  string $filename Chemin du fichier depuis le dossier html contenant les body.
 * @return string           Chemin absolu complet du fichier à inclure.
 * 
 * @author Benoît Huftier
 */
function ph_get_body(string $filename) : string {
    return HTML_PATH . '/' . $filename;
}

/**
 * @param  string $filename Chemin du fichier depuis le dossier uploads.
 * @return string           Chemin absolu complet du fichier à récupérer.
 * 
 * @author Benoît Huftier
 */
function ph_get_upload_path(string $filename) : string {
    return UPLOAD_PATH . '/' . $filename;
}