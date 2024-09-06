<?php

require_once(__DIR__ . '/site-header.php');

$page = new PH\Templates\BootstrapPage();
$page->setPermissionsNeeded(Role::Connected);
$page->forbidAccessIfNotPermitted();
$page->setBody(ph_get_body('profile.php'));
$page->setTitle(ph_get_user()->getName() . ' : profil');
$page->setSpecialSections(array(
    Role::Player => array('player-only'),
));
$page->addScripts(array(
    ph_create_js_object('same-value.js'),
    ph_create_js_object('profile.js')
));

$page->render();
