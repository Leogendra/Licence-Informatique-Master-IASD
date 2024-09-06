<?php

require_once(__DIR__ . '/verify-team.php');

if ($team->hasPlayer(ph_get_user())) {
    global $phdb;
    $phdb->playerLeftTeam(ph_get_user()->getPlayerId(), $team->getId());
    ph_set_success_messages(array('Vous avez quitté l\'équipe'));
}

$redirect_team();