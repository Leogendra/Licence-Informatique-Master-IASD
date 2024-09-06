<?php

require_once(__DIR__ . '/verify-team.php');

if (\Postulate::Pending === $team->getPostulateTypeForPlayer(ph_get_user())) {
    global $phdb;
    $phdb->removePostulate(ph_get_user()->getPlayerId(), $team->getId());
    ph_set_success_messages(array('Postulat supprimé'));
}

$redirect_team();