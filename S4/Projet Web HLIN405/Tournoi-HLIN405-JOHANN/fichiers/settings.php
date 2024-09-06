<?php

// ----------------------------------------------------------------------------
// Vérification de la version PHP

if (PHP_VERSION_ID < 80000) {
    print('Erreur : PHP8.0 ou supérieur requis pour le site.');
    die;
}

// ----------------------------------------------------------------------------
// Définition des constantes & chargement de l'autoloader

define('ABSPATH', __DIR__);

require_once ABSPATH . '/core/autoloader.php';
require_once ABSPATH . '/php/functions-autoloader.php';

// ----------------------------------------------------------------------------
// Récupération de l'environnement de développement

$devenv = new Core\ConfigurationFileParser(__DIR__ . '/env.cfg');
$devenv->readAndParseFile(function() {
    http_response_code(500);
    echo 'Erreur serveur critique !';
    exit;
});

try {
    $root = $devenv->getConfigurationValue('root');
}
catch(Core\NoSuchConfigurationKeyException $_) {
    die;
}

if ('/' === substr($root, -1)) {
    $root = substr($root, 0, -1);
}
if (!empty($root) && '/' !== $root[0]) {
    $root = '/' . $root;
}

// ----------------------------------------------------------------------------
// Définition des constantes de développement

try {
    $dev = boolval(intval($devenv->getConfigurationValue('development')));
}
catch(Core\NoSuchConfigurationKeyException $_) {
    $dev = false;
} 
define('DEVELOPMENT', $dev);
define('ROOT', $root);

if (true === DEVELOPMENT) {
    ini_set('display_errors', 'On');
}
else {
    ini_set('display_errors', 'Off');
    error_reporting(0);
}

// ----------------------------------------------------------------------------
// Inlcusion des fichiers utiles
require_once ABSPATH . '/php/install.php';
require_once ABSPATH . '/php/functions-misc.php';
require_once ABSPATH . '/php/functions-abspath-links.php';
require_once ABSPATH . '/php/functions-root-links.php';
require_once ABSPATH . '/php/functions-errors.php';
require_once ABSPATH . '/php/functions-user.php';
require_once ABSPATH . '/php/functions-users.php';
require_once ABSPATH . '/php/functions-teams.php';
require_once ABSPATH . '/php/functions-register.php';
require_once ABSPATH . '/php/functions-forms.php';
require_once ABSPATH . '/php/functions-tournament.php';
require_once ABSPATH . '/php/functions-bracket.php';
require_once ABSPATH . '/php/database/functions-schema.php';

// ----------------------------------------------------------------------------
// Suppression des variables utilisées

unset($devenv);
unset($root);
unset($dev);

// ----------------------------------------------------------------------------
// Mise en place de la base de données

require_ph_db();

if (false === ph_db_installed()) {
    ph_install_db();
}
else {
    ph_set_db();
    if (true === ph_db_differs()) {
        ph_update_db_tables();
    }
    if (true === ph_default_values_differs()) {
        ph_update_default_values();
    } 
}

if (defined('RESET_PLACEHOLDERS') && true === RESET_PLACEHOLDERS) {
    ph_update_placeholder_values();
}

// ----------------------------------------------------------------------------
// Lancement de la session

session_start();