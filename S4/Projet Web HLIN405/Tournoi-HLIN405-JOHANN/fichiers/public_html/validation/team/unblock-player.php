<?php

require_once(__DIR__ . '/verify-ajax-datas.php');

if (\Postulate::Refused !== $team->getPostulateTypeForPlayer($player)) {
    $result(false, 'Le joueur n\'a pas été bloqué');
}

global $phdb;
$phdb->unblockPlayer($player->getPlayerId(), $team->getId());
$result(true, 'Le joueur ' . $player->getName() . ' a bien été débloqué');