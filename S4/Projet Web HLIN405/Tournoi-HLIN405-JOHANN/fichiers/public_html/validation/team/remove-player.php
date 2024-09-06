<?php

require_once(__DIR__ . '/verify-ajax-datas.php');

if (!$team->hasPlayer($player)) {
    $result(false, 'Le joueur n\'appartient pas à l\'équipe');
}

global $phdb;
$phdb->playerLeftTeam($player->getPlayerId(), $team->getId());
$phdb->postulate($player->getPlayerId(), $team->getId(), \Postulate::Refused);
$result(true, 'Le joueur ' . $player->getName() . ' a bien été éjecté de l\'équipe');