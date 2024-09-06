<?php 

require_once __DIR__ . '/site-header.php';

if (false === array_key_exists('id', $_GET) || false === is_numeric($_GET['id'])) {
    header('Location: ' . ph_get_route_link('manage-tournaments.php'));
    exit;
}

try {
    global $tournament;
    $tournament = PH\Tournament::fromId($_GET['id']);
}
catch (Exception $e) {
    header('Location: ' . ph_get_route_link('manage-tournaments.php'));
    exit;
}

if (!$tournament->getManager()->sameUserThan(ph_get_user())) {
    header('Location: ' . ph_get_route_link('manage-tournaments.php'));
    exit;
}

$page = new PH\Templates\BootstrapPage();
$page->setPermissionsNeeded(Role::Manager);
$page->forbidAccessIfNotPermitted();
$page->setBody(ph_get_body('manage-tournament.php'));
$page->setTitle('Gérer un tournoi');
$page->addScripts(array(
    ph_create_js_object('tournament-tree.js')
));
$page->addStyleSheets(array(
    ph_create_css_object('tournament-tree.css')
));
$page->setSpecialSections(array(
    'tournament-forthcoming' => Status::Forthcoming === $tournament->getStatus(),
    'tournament-preinscriptions' => Status::PreRegistrations === $tournament->getStatus(),
));

if (Status::PreRegistrations === $tournament->getStatus()) {
    $page->addScripts(array(
        ph_create_js_object('tournament-creation.js'),
        ph_create_js_object('tournament-preinscriptions.js'),
        ph_create_js_object('functions-ajax.js'),
        ph_create_js_object('functions-postulate-error.js'),
        ph_create_js_object('functions-preinscription.js'),
        new \Core\Tags\ScriptJS('https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js')
    ));
}

$page->render();