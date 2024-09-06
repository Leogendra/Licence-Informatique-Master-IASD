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
if (!isset($_POST['tournament_id']) || !isset($_POST['team_id'])) {
    $result(false, 'Données manquantes');
}

try {
    $tournament = PH\Tournament::fromId($_POST['tournament_id']);
}
catch (\Exception $_) {
    $result(false, 'Le tournoi n\'existe pas');
}

try {
    $team = PH\Team::createFromId($_POST['team_id']);
}
catch (\Exception $_) {
    $result(false, 'L\'équipe n\'existe pas');
}

// Seul le capitaine peut faire des changements sur l'équipe
if (!ph_get_user()->sameUserThan($tournament->getManager())) {
    $result(false, 'Autorisation non valide');
}