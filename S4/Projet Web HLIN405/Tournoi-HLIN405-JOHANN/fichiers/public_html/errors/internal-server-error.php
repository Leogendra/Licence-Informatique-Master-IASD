<?php
require_once(__DIR__ . '/../site-header.php');

$page = new PH\Templates\BootstrapPage();
$page->setBody(ph_get_body('errors/internal-server-error-body.php'));
$page->setTitle('500 Internal Server Error');
$page->render();