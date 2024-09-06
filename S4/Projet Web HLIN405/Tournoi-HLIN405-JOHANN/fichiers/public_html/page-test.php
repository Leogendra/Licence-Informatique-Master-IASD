<?php

require_once(__DIR__ . '/site-header.php');

$page = new PH\Templates\BootstrapPage();

$page->setBody(ph_get_body('page-test.php'));

$page->setTitle("page-test");

$page->addStylesheets(array(ph_create_css_object('role-management.css')));

$page->render();

