<?php 
$page = (isset($_GET['page']) && $_GET['page'] > 0) ? intval($_GET['page']) : 1;

try {
    $conds_array = ph_get_tournaments_which_match($_GET);
}
catch (Exception $e) {
    $conds_array = array();
}

$tournaments = ph_get_tournaments($conds_array, $page);

$file = 'tournaments.php';
$link = 'tournament.php';
$title = 'Consulter les tournois';
$limit = 10;
$total_tournaments = isset($conds_array) ? ph_get_total_tournaments($conds_array) : 0;

include ph_include('tournaments.php');