<?php

require_once(__DIR__ . '/site-header.php');

$page = new PH\Templates\BootstrapPage();

$page->setPermissionsNeeded(Role::Administrator);

$page->forbidAccessIfNotPermitted();

$page->setBody(ph_get_body('tournament-creation.php'));

$page->setTitle("Tournament Creation");

$page->addStylesheets(array(ph_create_css_object('tournament-creation.css')));

$page->addScripts(array(
    ph_create_js_object('tournament-creation.js')
));

$page->render();

