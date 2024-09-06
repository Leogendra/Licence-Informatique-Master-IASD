<?php

require_once(__DIR__ . '/verify-ajax-datas.php');

if (\Postulate::Refused !== $tournament->getPostulateStatusOfTeam($team)) {
    $result(false, 'L\équipe n\'a pas été bloquée');
}

global $phdb;
$phdb->updateRegistration($tournament->getId(), $team->getId(), \Postulate::Accepted);
$result(true, 'L\'équipe "' . $team->getName() . '" a bien été acceptée au tournoi');