<?php
require_once(__DIR__ . '/../site-header.php');

$page = new PH\Templates\BootstrapPage();
$page->setBody(ph_get_body('errors/forbidden-body.php'));
$page->setTitle('403 Forbidden');
$page->render();