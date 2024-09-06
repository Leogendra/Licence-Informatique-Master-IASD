<?php

require_once(__DIR__ . '/verify-team.php');

// Seul le capitaine peut supprimer une équipe
// Il faut aussi que l'équipe soit supprimable
if (ph_get_user()->sameUserThan($team->getCaptain()) && ph_can_team_be_deleted($team)) {
    global $phdb;
    $phdb->deleteTeam($team->getId());
    ph_set_success_messages(array('Équipe supprimée.'));
}

$redirect_team();