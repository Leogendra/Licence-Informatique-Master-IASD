<?php 

require_once __DIR__ . '/site-header.php';

if (false === array_key_exists('id', $_GET) || false === is_numeric($_GET['id'])) {
    header('Location: ' . ph_get_route_link('tournaments.php'));
    exit;
}

try {
    global $tournament;
    $tournament = PH\Tournament::fromId(intval($_GET['id']));
}
catch (Exception $e) {
    header('Location: ' . ph_get_route_link('tournaments.php'));
    exit;
}

$display_tournament_tree = (Status::Forthcoming !== $tournament->getStatus() && Status::PreRegistrations !== $tournament->getStatus());
$registered_team = false;


foreach ($tournament->getRegisteredTeams() as $team) {
    if ($team->hasPlayer(ph_get_user())) {
        $registered_team = true;
        break;
    }
}

$has_pending_team = (false === is_null(ph_get_pending($tournament)));


$page = new PH\Templates\BootstrapPage();
$page->setPermissionsNeeded(Role::All);
$page->forbidAccessIfNotPermitted();
$page->setBody(ph_get_body('tournament.php'));
$page->setTitle($tournament->getName() . ' : consultation du tournoi');
$page->setSpecialSections(array(
    'tournament-tree' => $display_tournament_tree,
    'preinscriptions' => !$display_tournament_tree,
    'address-complement-exists' => empty($tournament->getLocation()->getAddressComplement()),
    'in-registered-team' => $registered_team,
    'not-in-registered-team' => !$registered_team,
    'captain-only' => ph_is_captain(ph_get_user()),
    'team-manager-only' => ph_get_user()->sameUserThan($tournament->getManager()),
    'postulate-pending' => $has_pending_team,
    'not-postulate-pending' => !$has_pending_team,
    'preregistrations-only' => Status::PreRegistrations === $tournament->getStatus(),
    'forthcoming-only' => Status::Forthcoming === $tournament->getStatus(),
));

if ($display_tournament_tree) {
    $page->addStylesheets(array(
        ph_create_css_object('tournament-tree.css')
    ));
}

if ($tournament->getStatus() === Status::PreRegistrations || $tournament->getStatus() === Status::Forthcoming) {
    $map_js = new \Core\Tags\ScriptJS('https://unpkg.com/leaflet@1.7.1/dist/leaflet.js');
    $map_js->setIntegrity('sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==');
    $map_js->setCrossOrigin('anonymous');

    $map_css = new \Core\Tags\LinkCSS('https://unpkg.com/leaflet@1.7.1/dist/leaflet.css');
    $map_css->setIntegrity('sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==');
    $map_css->setCrossOrigin('anonymous');

    $page->addStylesheets(array(
        $map_css,
    ));
    $page->addScripts(array(
        $map_js,
        new Core\Tags\ScriptJS('http://maps.stamen.com/js/tile.stamen.js?v1.3.0'),
    ));
}

$page->render();