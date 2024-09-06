<?php

require_once(__DIR__ . '/verify-team.php');

// Vérification de la variable d'activation
if (!isset($_POST['activation'])) {
    $redirect_team();
}

// Seul le capitaine peut activer ou désactiver une équipe
if (ph_get_user()->sameUserThan($team->getCaptain())) {
    global $phdb;
    $phdb->changeTeamActivation($team->getId(), $_POST['activation']);

    if ($_POST['activation']) {
        ph_set_success_messages(array('Équipe réactivée.'));
    }
    else {
        ph_set_success_messages(array('Équipe désactivée.'));
    }
}

$redirect_team();