<?php

spl_autoload_register('ph_autoloader');

/**
 * Cette fonction permet d'inclure les fichiers du dossier php lorsqu'une classe du
 * namespace PH est appelée.
 * C'est un complément de l'autoloader du coeur.
 * 
 * @param string $class_name Le nom de la classe a inclure.
 * 
 * @author Benoît Huftier
 */
function ph_autoloader(string $class_name) : void {
    if ('Role' === $class_name) {
        require_once __DIR__ . '/enum-roles.php';
    }
    else if ('Type' === $class_name) {
        require_once __DIR__ . '/enum-type.php';
    }
    else if ('Status' === $class_name) {
        require_once __DIR__ . '/enum-status.php';
    }
    else if ('Postulate' === $class_name) {
        require_once __DIR__ . '/enum-postulate.php';
    }
    else {
        $classes = explode('\\', $class_name);
        if (isset($classes) && 'ph' === strtolower($classes[0])) {
            $classes[0] = 'PHP';
        }
        $class_name = implode('\\', $classes);
        \Core\Autoloader::classLoader($class_name);
    }
}