<?php

require_once(__DIR__ . '/verify-team.php');

// Vérification du nouveau capitaine
if (!isset($_POST['new-captain-id'])) {
    $redirect_team();
}

try {
    $new_captain = PH\User::createFromId($_POST['new-captain-id']);
}
catch (\Exception $_) {
    $redirect_team();
}

// Seul le capitaine peut donner son rôle de capitaine
if (ph_get_user()->sameUserThan($team->getCaptain())) {
    global $phdb;
    $phdb->changeTeamCaptain($team->getId(), $new_captain->getPlayerId());
    ph_set_success_messages(array('Le capitaine de l\'équipe est maintenant ' . $new_captain->getName()));
}

$redirect_team();