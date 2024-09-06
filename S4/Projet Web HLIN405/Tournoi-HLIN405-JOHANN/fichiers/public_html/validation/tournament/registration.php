<?php 

require_once __DIR__ . '/verify-tournament.php';

$postulate_status = $tournament->getPostulateStatusOfTeam($team);

if (Status::PreRegistrations === $tournament->getStatus() && Postulate::None === $postulate_status) {
    global $phdb;

    $phdb->registerTournament($tournament->getId(), $team->getId());
    ph_set_success_messages(array('Préinscription envoyée avec succès.'));
}

$redirect_tournament();