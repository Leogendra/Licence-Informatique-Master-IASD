<?php

require_once(__DIR__ . '/../../site-header.php');

// Redirections
$redirect_back = function() : void {
    header('Location: ' . ph_get_route_link('tournaments.php'));
    exit;
};

$redirect_management = function() : void {
    header('Location: ' . ph_get_route_link('manage-tournament.php', array('id' => $_POST['tournament-id'])));
    exit;
};

// Vérification du tournoi
if (!isset($_POST['tournament-id'])) {
    $redirect_back();
}

try {
    $tournament = PH\Tournament::fromId($_POST['tournament-id']);
}
catch (Exception $_) {
    $redirect_back();
}

if (false === $tournament->getManager()->sameUserThan(ph_get_user())) {
    $redirect_management();
}