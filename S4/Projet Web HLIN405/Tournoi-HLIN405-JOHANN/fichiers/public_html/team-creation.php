<?php

require_once(__DIR__ . '/site-header.php');

$page = new PH\Templates\BootstrapPage();
$page->setPermissionsNeeded(Role::Player | Role::Administrator);
$page->forbidAccessIfNotPermitted();
$page->setBody(ph_get_body('team-creation.php'));
$page->setTitle('Créer mon équipe');
$page->render();

