<?php
require_once(__DIR__ . '/../site-header.php');

$page = new PH\Templates\BootstrapPage();
if (false === DEVELOPMENT) {
    $page->setPermissionsNeeded(Role::Administrator);
    $page->forbidAccessIfNotPermitted();
}
$page->setBody(ph_get_body('repair-body.php'));
$page->setTitle("Page de réparation de la base de données.");
$page->render();