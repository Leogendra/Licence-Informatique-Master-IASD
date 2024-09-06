<?php

require_once(__DIR__ . '/../../site-header.php');

$result = function(bool $success, string $message) {
    echo json_encode(array(
        'success' => $success,
        'message' => $message
    ));
    exit;
};

// Vérification des données
if (!isset($_POST['team_id']) || !isset($_POST['player_user_id'])) {
    $result(false, 'Données manquantes');
}

try {
    $team = PH\Team::createFromId($_POST['team_id']);
}
catch (\Exception $_) {
    $result(false, 'L\'équipe n\'existe pas');
}

try {
    $player = PH\User::createFromId($_POST['player_user_id']);
}
catch (\Exception $_) {
    $result(false, 'Le joueur n\'existe pas');
}

// Seul le capitaine peut faire des changements sur l'équipe
if (!ph_get_user()->sameUserThan($team->getCaptain())) {
    $result(false, 'Autorisation non valide');
}