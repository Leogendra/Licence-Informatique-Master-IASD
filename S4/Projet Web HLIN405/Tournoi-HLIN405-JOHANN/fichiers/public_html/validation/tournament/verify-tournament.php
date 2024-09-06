<?php

require_once(__DIR__ . '/../../site-header.php');

// Redirections
$redirect_back = function() : void {
    header('Location: ' . ph_get_route_link('tournaments.php'));
    exit;
};

$redirect_tournament = function() : void {
    header('Location: ' . ph_get_route_link('tournament.php', array('id' => $_POST['tournament-id'])));
    exit;
};

// Vérification du tournoi
if (!isset($_POST['tournament-id']) || !isset($_POST['team-id'])) {
    $redirect_back();
}

try {
    $tournament = PH\Tournament::fromId($_POST['tournament-id']);
}
catch (Exception $_) {
    $redirect_back();
}

try {
    $team = PH\Team::createFromId($_POST['team-id']);
}
catch (Exception $_) {
    $redirect_tournament();
}

if (false === $team->getCaptain()->sameUserThan(ph_get_user())) {
    $redirect_tournament();
}