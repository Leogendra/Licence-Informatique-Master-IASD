<?php

require_once(__DIR__ . '/../../site-header.php');

// Redirections
$redirect_home = function() : void {
    header('Location: ' . ph_get_route_link('index.php'));
    exit;
};

$redirect_team = function() : void {
    header('Location: ' . ph_get_route_link('team.php', array('id' => $_POST['team-id'])));
    exit;
};

// Vérification de l'équipe
if (!isset($_POST['team-id'])) {
    $redirect_home();
}

try {
    $team = PH\Team::createFromId($_POST['team-id']);
}
catch (\Exception $_) {
    $redirect_home();
}