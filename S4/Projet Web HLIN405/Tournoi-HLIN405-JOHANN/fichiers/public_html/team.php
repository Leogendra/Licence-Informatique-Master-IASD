<?php

require_once(__DIR__ . '/site-header.php');

if (false === array_key_exists('id', $_GET) || false === is_numeric($_GET['id'])) {
    header('Location: ' . ph_get_route_link('teams.php'));
    exit;
}

try {
    global $team;
    $team = PH\Team::createFromId($_GET['id']);
}
catch (Exception $_) {
    header('Location: ' . ph_get_route_link('teams.php'));
    exit;
}

// Est-ce que l'équipe est activée
$team_active = $team->isActive();
// Si l'utilisateur est un joueur
$is_player = ph_get_user()->isPlayer();
// Si le joueur est déjà dans l'équipe
$player_on_team = $is_player && $team->hasPlayer(ph_get_user());
// Si le joueur est capitaine de l'équipe
$team_captain = $is_player && $team->getCaptain()->sameUserThan(ph_get_user());
// Si le joueur a postulé pour la team mais n'est pas encore accepté
$postulate_player = $is_player && \Postulate::Pending === $team->getPostulateTypeForPlayer(ph_get_user());
// Si le joueur a postulé et s'est fait rejeté par le capitaine
$refused_player = $is_player && \Postulate::Refused === $team->getPostulateTypeForPlayer(ph_get_user());
// Membre non capitaine
$non_captain_member = $player_on_team && !$team_captain;
// Joueur qui n'a jamais postulé
$other_player = $is_player && !$refused_player && !$postulate_player && !$player_on_team;
// Si l'équipe peut être supprimée ou non
$team_deletable = ph_can_team_be_deleted($team);

$page = new PH\Templates\BootstrapPage();
$page->setPermissionsNeeded(Role::All);
$page->forbidAccessIfNotPermitted();
$page->setBody(ph_get_body('team.php'));
$page->setTitle($team->getName() . ' : page d\'équipe');
$page->addStylesheets(array(
    ph_create_css_object('team.css')
));
$page->addScripts(array(
    ph_create_js_object('functions-ajax.js'),
    ph_create_js_object('functions-postulate-error.js'),
    ph_create_js_object('functions-postulate.js'),
    ph_create_js_object('team-update.js'),
    new \Core\Tags\ScriptJS('https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js')
));
$page->setSpecialSections(array(
    'team-active-only' => $team_active,
    'team-inactive-only' => !$team_active,
    'team-deletable-only' => $team_deletable,
    'team-non-deletable-only' => !$team_deletable,
    'captain-only' => $team_captain,
    'member-only' => $non_captain_member,
    'postulate-only' => $postulate_player,
    'blocked-only' => $refused_player,
    'non-member-only' => $other_player
));

$page->render();
