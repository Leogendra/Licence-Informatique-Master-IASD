<?php 

require_once __DIR__ . '/verify-management.php';

if (Status::PreRegistrations === $tournament->getStatus()) {
    global $phdb;

    $date = date_create()->format('Y-m-d');
    $phdb->changePreinscriptionsEndDateForTournament($tournament->getId(), $date);
    ph_set_success_messages(array('Préinscriptions arrêtées avec succès.'));
}

$redirect_management();