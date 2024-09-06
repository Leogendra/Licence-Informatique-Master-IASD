<?php
require_once(__DIR__ . '/../site-header.php');

$page = new PH\Templates\BootstrapPage();
$page->setBody(ph_get_body('errors/not-found-body.php'));
$page->setTitle('404 Not Found');
$page->render();