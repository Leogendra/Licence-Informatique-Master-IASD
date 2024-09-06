<?php 

/**
 * Crée la liste des tournois à partir des critères donnés. 
 * Les critères doivent être formatés comme ceci : 
 * array(
 *     'starting-date' => array('<|<=|==|>=|>', date-string),
 *     'name' => string,
 *     'duration' => array('<|<=|==|>=|>', int),
 *     'type' => string, 
 *     'department' => int, 
 *     'city' => string
 * )
 * 
 * @param  array $conds Les critères formatés comme ci-dessus. 
 * @param  int   $page  La page à récupérer.
 * @return array        Un tableau de tournois. 
 * 
 * @author Johann Rosain
 */
function ph_get_tournaments(array $conds, int $page = 1) : array {
    global $phdb;

    $tournaments = $phdb->getTournaments($conds, 10, $page);
    $result = array();

    foreach ($tournaments as $tournament) {
        $result[] = PH\Tournament::fromRawData(array(
            'id' => $tournament['tournament_id'],
            'name' => $tournament['tournament_name'],
            'starting-date' => $tournament['start_date'],
            'end-inscription' => $tournament['end_inscription'],
            'duration' => $tournament['duration'], 
            'type' => $tournament['type'],
            'location' => array(
                'id' => $tournament['location_id'],
                'city' => $tournament['city_name'],
                'code' => intval($tournament['code']),
                'address1' => $tournament['address1'],
                'address2' => $tournament['address2']
            ),
            'manager' => $tournament['manager']
        ));
    }

    return $result;
}

/**
 * Compte le nombre de tournois qui remplissent les critères donnés. 
 * 
 * @param  array $conds Les critères formatés comme pour ph_get_tournaments()
 * @return int          Le nombre total de tournois qui remplissent les critères.
 * 
 * @author Johann Rosain
 */
function ph_get_total_tournaments(array $conds) : int {
    global $phdb;

    return $phdb->countTournaments($conds);
}

/**
 * Récupère tous les gestionnaires de tournoi.
 * 
 * @return array Un tableau formatté pour avoir id => nom de chaque gestionnaire.
 * 
 * @author Johann Rosain
 */
function ph_get_all_tournaments_managers() : array {
    global $phdb;
    return $phdb->getAllManagers();
}

/**
 * Récupère toutes les équipes où le capitaine est l'utilisateur donné. 
 * 
 * @param  PH\User $user L'utilisateur qui est capitaine.
 * @return array         Un tableau avec id => nom de l'équipe.
 * 
 * @author Johann Rosain
 */
function ph_get_teams_where_captain_is(PH\User $user) : array {
    global $phdb;

    try {
        return $phdb->getTeamsWhereCaptainIs($user->getPlayerId());
    }
    catch (Exception $e) {
        return array();
    }
}

/**
 * Récupère l'équipe de l'utilisateur qui est capitaine qui est préinscrite mais pas encore acceptée.
 * 
 * @return PH\Team|null L'équipe si elle est trouvée, null sinon.
 * 
 * @author Johann Rosain
 */
function ph_get_pending(PH\Tournament $tournament) : PH\Team|null {
    foreach ($tournament->getPendingTeams() as $team) {
        $team = $team['team'];
        if (ph_get_user()->sameUserThan($team->getCaptain())) {
            return $team;
        }
    }
    return null;
}

/**
 * Récupère l'id de tous les tournois auquel l'utilisateur a participé avec ses équipes.
 * 
 * @return array Un tableau avec les id des tournois.
 * 
 * @author Johann Rosain
 */
function ph_get_participated_tournaments() : array {
    global $phdb;

    $user = ph_get_user();
    return $phdb->getTournamentWherePlayerParticipated($user->getPlayerId());
}

/**
 * Retourne le tableau de conditions de la recherche des tournois
 * 
 * @param array $conds Les conditions
 * @return array       Le tableau de filtres 
 * 
 * @author Johann Rosain
 */
function ph_get_tournaments_which_match(array $conds) : array {
    $accepted_keys = array(
        'starting-date',
        'ending-date',
        'duration',
        'name',
        'type',
        'department',
        'city',
        'status',
        'manager',
    );

    $need_comparator_treatment = array(
        'starting-date',
        'ending-date',
        'duration'
    );

    return ph_process_search_data($conds, $accepted_keys, $need_comparator_treatment);
}