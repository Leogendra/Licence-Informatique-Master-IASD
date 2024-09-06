<?php

require_once(__DIR__ . '/site-header.php');

$page = new PH\Templates\BootstrapPage();

$page->setPermissionsNeeded(Role::Administrator);

$page->forbidAccessIfNotPermitted();

$page->setBody(ph_get_body('role-management.php'));

$page->setTitle("Gestion des rôles utilisateur.");

$page->addStylesheets(array(ph_create_css_object('role-management.css')));
$page->addScripts(array(
    ph_create_js_object('class-filter.js'),
    ph_create_js_object('players-search.js'),
    new \Core\Tags\ScriptJS('https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'),
    ph_create_js_object('functions-ajax.js'),
    ph_create_js_object('roles.js'),
));

$page->render();

