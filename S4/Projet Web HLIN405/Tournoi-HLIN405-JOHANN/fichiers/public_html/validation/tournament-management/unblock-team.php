<?php

require_once(__DIR__ . '/verify-ajax-datas.php');

if (\Postulate::Refused !== $tournament->getPostulateStatusOfTeam($team)) {
    $result(false, 'L\'équipe n\'a pas été bloquée');
}

global $phdb;
$phdb->deleteRegistration($tournament->getId(), $team->getId());
$result(true, 'L\'équipe "' . $team->getName() . '" a bien été débloquée');