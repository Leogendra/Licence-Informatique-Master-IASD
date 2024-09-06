<?php 

require_once __DIR__ . '/verify-tournament.php';

$postulate_status = $tournament->getPostulateStatusOfTeam($team);

if (Postulate::Pending === $postulate_status) {
    global $phdb;

    $phdb->deleteRegistration($tournament->getId(), $team->getId());
    ph_set_success_messages(array('Préinscription supprimée avec succès.'));
}

$redirect_tournament();