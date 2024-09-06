<?php 
$page = (isset($_GET['page']) && $_GET['page'] > 0) ? intval($_GET['page']) : 1;

try {
    $conds_array = array_merge(
        array('manager' => ph_get_user()->getId()),
        ph_get_tournaments_which_match($_GET)
    );
}
catch (Exception $e) {
    $conds_array = array();
}

$tournaments = ph_get_tournaments($conds_array, $page);

$file = 'manage-tournaments.php';
$link = 'manage-tournament.php';
$title = 'Mes tournois';
$limit = 10;
$total_tournaments = isset($conds_array) ? ph_get_total_tournaments($conds_array) : 0;

include ph_include('tournaments.php');