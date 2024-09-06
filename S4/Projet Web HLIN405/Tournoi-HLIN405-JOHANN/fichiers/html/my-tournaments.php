<?php 
$page = (isset($_GET['page']) && $_GET['page'] > 0) ? intval($_GET['page']) : 1;

try {
    $filter = ph_get_participated_tournaments();

    if (!empty($filter)) {
        $conds_array = array_merge(
            array('filter' => $filter), 
            ph_get_tournaments_which_match($_GET)
        );
    }

}
catch (Exception $e) {
    $conds_array = array();
}

if (!empty($filter)) {
    $tournaments = ph_get_tournaments($conds_array, $page);
}
else {
    $tournaments = array();
}

$file = 'my-tournaments.php';
$link = 'tournament.php';
$title = 'Consulter mes tournois';
$limit = 10;
$total_tournaments = isset($conds_array) ? ph_get_total_tournaments($conds_array) : 0;

// Pour pas que la barre de recherche bug.
unset($conds_array['filter']);

include ph_include('tournaments.php');