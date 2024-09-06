<?php

require_once(__DIR__ . '/verify-ajax-datas.php');

if (\Postulate::Pending !== $team->getPostulateTypeForPlayer($player)) {
    $result(false, 'Le joueur n\'a pas postulé à l\'équipe');
}

global $phdb;
$phdb->refusePostulate($player->getPlayerId(), $team->getId());
$result(true, 'Le joueur ' . $player->getName() . ' a bien été refusé');