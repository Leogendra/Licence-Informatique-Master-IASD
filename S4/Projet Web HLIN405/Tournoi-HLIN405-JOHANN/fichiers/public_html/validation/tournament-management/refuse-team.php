<?php

require_once(__DIR__ . '/verify-ajax-datas.php');

if (\Postulate::Pending !== $tournament->getPostulateStatusOfTeam($team)) {
    $result(false, 'L\équipe n\'a pas postulé au tournoi');
}

global $phdb;
$phdb->updateRegistration($tournament->getId(), $team->getId(), \Postulate::Refused);
$result(true, 'L\'équipe "' . $team->getName() . '" a bien été refusée');