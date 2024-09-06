<?php 

require_once __DIR__ . '/verify-management.php';

if (array_key_exists('tree-datas', $_POST)) {
    global $phdb;

    foreach ($_POST['tree-datas'] as $match_id => $match_datas) {
        if (array_key_exists('team1-id', $match_datas) && is_numeric($match_datas['team1-id']) &&
            array_key_exists('team2-id', $match_datas) && is_numeric($match_datas['team2-id']) &&
            array_key_exists('score1', $match_datas) && is_numeric($match_datas['score1']) &&
            array_key_exists('score2', $match_datas) && is_numeric($match_datas['score2']) &&
            array_key_exists('date', $match_datas) && false !== strtotime($match_datas['date'])) {
            $phdb->updateMatchForTournament(
                $tournament->getId(),
                $match_id,
                ('0' === $match_datas['team1-id'] ? null : $match_datas['team1-id']),
                ('0' === $match_datas['team2-id'] ? null : $match_datas['team2-id']),
                ph_create_score_string($match_datas['score1'], $match_datas['score2']),
                date_create($match_datas['date'])->format('Y-m-d')
            );
        }
    }
}

$redirect_management();