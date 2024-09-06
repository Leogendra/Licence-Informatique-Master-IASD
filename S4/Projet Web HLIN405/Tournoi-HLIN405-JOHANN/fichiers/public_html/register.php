<?php

require_once(__DIR__ . '/site-header.php');

$page = new PH\Templates\BootstrapPage();

$page->addScripts(array(
    ph_create_js_object('same-value.js')
));

$page->addStylesheets(array(
    ph_create_css_object('register.css')
));

$page->setBody(ph_get_body('register.php'));

$page->setTitle("Enregistrement");

$page->render();