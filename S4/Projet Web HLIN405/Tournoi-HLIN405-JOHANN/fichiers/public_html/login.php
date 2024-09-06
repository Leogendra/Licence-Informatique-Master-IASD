<?php

require_once(__DIR__ . '/site-header.php');

$page = new PH\Templates\BootstrapPage();

$page->setBody(ph_get_body('login.php'));

$page->setTitle("Connexion");

$page->addStylesheets(array(ph_create_css_object('login.css')));

$page->render();

