<?php 

require_once __DIR__ . '/verify-tournament.php';

$postulate_status = $tournament->getPostulateStatusOfTeam($team);

if (Postulate::Accepted === $postulate_status) {
    global $phdb;

    $phdb->deleteRegistration($tournament->getId(), $team->getId());
    ph_set_success_messages(array('Inscription supprimée avec succès.'));
}

$redirect_tournament();