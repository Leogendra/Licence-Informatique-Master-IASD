<?php

require_once(__DIR__ . '/verify-ajax-datas.php');

if (\Postulate::Pending !== $team->getPostulateTypeForPlayer($player)) {
    $result(false, 'Le joueur n\'a pas postulé à l\'équipe');
}

global $phdb;
$phdb->acceptPostulate($player->getPlayerId(), $team->getId());
$phdb->playerJoinTeam($player->getPlayerId(), $team->getId());
$result(true, 'Le joueur ' . $player->getName() . ' a bien été ajouté à l\'équipe');