<?php

require_once(__DIR__ . '/verify-team.php');

$type = $team->getPostulateTypeForPlayer(ph_get_user());

if ((\Postulate::None === $type || \Postulate::Accepted == $type) && !$team->hasPlayer(ph_get_user())) {
    global $phdb;
    $phdb->postulate(ph_get_user()->getPlayerId(), $team->getId());
    ph_set_success_messages(array('Demande de postulat envoyé avec succès'));
}

$redirect_team();