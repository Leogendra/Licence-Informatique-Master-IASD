<?php

define('PH_MAX_QUERY_TEAM', 10);

/**
 * Permet de récupérer toutes les équipes qui matchent avec une certaine chaîne.
 * 
 * Le nombre d'équipe retournées est définie dans PH_MAX_QUERY_TEAM
 * 
 * @param  array $conds Le tableau de conditions de la recherche d'une équipe.
 * @param  int   $page  Le numéro de la page pour récupérer les bonne valeurs de la BDD.
 *                      (Premier index à 1).
 * @return array        Un tableau avec deux valeurs :
 *                         - "nb_total_teams" : Le nombre total de team qui matche avec
 *                           la chaîne fournie.
 *                         - "teams" : Un nombre max de teams qui matche avec la chaîne
 *                           fournie depuis la page $page.
 * 
 * @author Benoît Huftier
 */
function ph_get_teams_which_match(array $conds, int $page, array &$formatted_conds) : array {
    global $phdb;

    $formatted_conds = ph_format_teams_conds($conds);

    $nb_total_teams = $phdb->getTeamsNumberWhereCondsMatch($formatted_conds);
    $teams = array();

    // Pour éviter une requête inutile à la base de données
    if ($nb_total_teams > 0) {
        $teams_datas = $phdb->getTeamsWhereCondsMatch($formatted_conds, PH_MAX_QUERY_TEAM, $page);
        $teams = ph_create_teams_from_datas($teams_datas);
    }

    return array(
        'nb_total_teams' => $nb_total_teams,
        'teams' => $teams
    );
}

/**
 * Formatte les conditions de recherche de la barre d'équipes.
 */
function ph_format_teams_conds(array $conds) : array {
    $accepted_keys = array(
        'name',
        'captain',
        'activity',
        'nb-players',
    );

    $need_comparator_treatment = array(
        'nb-players',
    );

    return ph_process_search_data($conds, $accepted_keys, $need_comparator_treatment);
}

/**
 * Création des équipes dont les données sont fournies.
 * 
 * @param array $teams_datas Un tableau de données bien formé pour faires des équipes.
 * @return array             Toutes les équipes dont les données ont été fournies.
 *                           Les clés sont les ids des équipes.
 * 
 * @author Benoît Huftier
 */
function ph_create_teams_from_datas(array $teams_datas) : array {
    // On récupère tous les joueurs
    $players = ph_get_teams_players(array_keys($teams_datas));
    $teams = array();

    // Création de toutes les équipes
    foreach ($teams_datas as $team_id => $team_datas) {
        $captain_id = $team_datas['captain'];

        $team_datas['players'] = $players[$team_id];
        $team_datas['captain'] = $players[$team_id][$captain_id];
        $team_datas['location'] = PH\Location::fromRawData(array(
            'id'        => intval($team_datas['location_id']),
            'city'      => $team_datas['city'],
            'code'      => $team_datas['code'],
            'address1'  => $team_datas['address1'],
            'address2'  => $team_datas['address2']
        ));

        $teams[$team_id] = new PH\Team($team_datas);
    }

    return $teams;
}

/**
 * Récupère tous les objets User qui représentent différentes équipes.
 * 
 * @param array $team_ids Les ids des équipes dont les joueurs vont être récupérés.
 * @return array          Tableau de tableau contenant tous les joueurs, chaque équipe
 *                        possède sont propre tableau avec tous ses joueurs, la clé est
 *                        l'id de l'équipe. Chaque joueur est indexé par son identifiant.
 * 
 * @author Benoît Huftier 
 */
function ph_get_teams_players(array $team_ids) : array {
    global $phdb;

    $teams_players_datas = $phdb->getPlayersForTeams($team_ids);

    // Tous les joueurs en gardant en clé les ids
    $players_datas = array();
    foreach ($teams_players_datas as $p_datas) {
        $players_datas = array_replace($players_datas, $p_datas);
    }

    $players_datas = ph_add_roles_to_users_datas($players_datas);
    $players = ph_create_multiple_users_with_datas($players_datas);

    // On place chaque joueur dans son équipe, en sachant qu'un joueur peut être
    // dans plusieurs équipes
    $teams_players = array();
    foreach ($teams_players_datas as $team_id => $players_datas) {
        foreach ($players_datas as $user_id => $_) {
            $teams_players[$team_id][$user_id] = $players[$user_id];
        }
    }

    return $teams_players;
}

/**
 * Récupère tous les capitaines des équipes.
 * 
 * @return array Un tableau avec en clé l'id et en valeur le nom.
 * 
 * @author Johann Rosain
 */
function ph_get_all_captains() : array {
    global $phdb;

    return $phdb->getAllCaptains();
}

/**
 * Renvoie si l'équipe donnée peut être supprimée ou non
 * On considère qu'une équipe ne peut être supprimée que si elle n'a participé à aucun tournoi
 * Si l'équipe n'a fait que postulé à un tournoi, c'est bon, elle peut être supprimée
 * 
 * @param PH\Team L'équipe que l'on veut vérifier
 * @return bool   Si oui ou non l'équipe peut être supprimée
 * 
 * @author Benoît Huftier
 */
function ph_can_team_be_deleted(PH\Team $team) : bool {
    global $phdb;

    $tournaments = $phdb->getTournamentWhereTeamParticipated($team->getId());

    return empty($tournaments);
}