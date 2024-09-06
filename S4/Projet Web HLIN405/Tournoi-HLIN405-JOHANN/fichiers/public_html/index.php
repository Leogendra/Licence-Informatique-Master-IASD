<?php

require_once(__DIR__ . '/site-header.php');

// Création de la page
$page = new PH\Templates\BootstrapPage();

// Gestion des permissions
$page->setPermissionsNeeded(Role::All);

// Si l'utilisateur n'à pas les droits renvoie un message d'erreur
$page->forbidAccessIfNotPermitted();

// Ajout du fichier HTML
$page->setBody(ph_get_body('home.php'));

// Ajout d'un titre
$page->setTitle('Accueil');

$page->addStylesheets(array(ph_create_css_object('home.css')));

// Gestion des zones de permissions
$page->addRestrictedSections(array(
    Role::Administrator => array('admin-only'),
));

// Rendu de la page
$page->render();