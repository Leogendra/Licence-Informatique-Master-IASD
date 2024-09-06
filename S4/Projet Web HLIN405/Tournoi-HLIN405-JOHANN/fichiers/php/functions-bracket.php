<?php

/**
 * Récupère l'arbre d'un tournoi donné.
 * Si les matchs ne sont pas instanciés dans la base de données, ils seront créés.
 * 
 * L'arbre est binaire, complet si on enlève les feuilles de hauteur maximale.
 * La racine correspond à la finale et les feuilles sont les premiers matchs.
 * 
 * Chaque noeud (match) a accès aux données suivantes :
 * - team1 (null|PH\Team) : La première équipe qui participe (null = pas encore d'équipe pour ce match)
 * - team2 (null|PH\Team) : La seconde équipe qui participe (null = pas encore d'équipe pour ce match)
 * - parent1 (null|array) : Le premier match du niveau inférieur, qui amène à ce match (null = le noeud est une feuille)
 * - parent2 (null|array) : Le second match du niveau inférieur, qui amène à ce match (null = le noeud est une feuille)
 * - date (DateTime) : La date du match
 * - result (string) : Le résultat du match
 * - id (int) : L'identifiant du match dans la base de données
 * 
 * @param PH\Tournament $tournament Le tournoi dont on veut l'arbre des matchs
 * @return array                    Un arbre contenant les matchs. Pointant sur la finale (la racine)
 * @return null                     Si l'arbre est vide
 * 
 * @author Benoît Huftier
 */
function ph_get_tournament_tree(PH\Tournament $tournament) : array|null {
    global $phdb;

    // Impossible de créer des matches avec une seule ou aucune équipe
    if (count($tournament->getRegisteredTeams()) <= 1) {
        return null;
    }

    $matches = $phdb->getMatchesForTournament($tournament->getId());

    if (empty($matches)) {
        return ph_create_tournament_tree($tournament);
    }

    try {
        return ph_create_tournament_tree_from_datas($tournament, $matches);
    }
    catch (\Exception $e) {
        if (defined('DEVELOPMENT') && true === DEVELOPMENT) {
            var_dump('Données corrompues pour les matchs du tournoi. L\'erreur retournée est la suivante : ' . $e->getMessage());
        }
        return ph_repair_tournament_tree($tournament);
    }
}

/**
 * Algrithme récursif renvoyant la hauteur de l'arbre donné
 * 
 * @param array $tree L'arbre dont on veut connaitre la hauteur
 * @return int        La hauteur de l'arbre
 * 
 * @author Benoît Huftier
 */
function ph_get_tree_height(array|null $tree) : int {
    if (is_null($tree)) {
        return 0;
    }
    
    return max(
        ph_get_tree_height($tree['parent1']),
        ph_get_tree_height($tree['parent2'])
    ) + 1;
}

/**
 * Retourne chaque round dans un tableau.
 * Tous les tableaus sont renvoyés avec comme index le numéro du round.
 * 
 * @param array|null $tree L'arbre de match que l'on veut convertir en tableau de tableau de round
 * @param int        $size La taille totale de l'arbre, récupérable avec ph_get_tree_height()
 * @return array           Un tableau avec un sous tableau contenant tous les matchs des rounds
 *                         Quand un match n'existe pas, il y a null à la place
 * 
 * @author Benoît Huftier
 */
function ph_create_rounds_from_tree(array|null $tree, int $size) : array {
    if (is_null($tree)) {
        if (1 === $size) {
            return array(1 => array(null));
        }
        return array();
    }

    $array = ph_create_rounds_from_tree($tree['parent1'], $size - 1);
    $array2 = ph_create_rounds_from_tree($tree['parent2'], $size - 1);

    foreach ($array2 as $s => $nodes) {
        foreach ($nodes as $node) {
            $array[$s][] = $node;
        }
    }

    $tree['parent1_id'] = is_null($tree['parent1']) ? null : $tree['parent1']['id'];
    $tree['parent2_id'] = is_null($tree['parent2']) ? null : $tree['parent2']['id'];

    unset($tree['parent1']);
    unset($tree['parent2']);

    $array[$size][] = $tree;

    return $array;
}

/**
 * Récupère les scores en fonction d'une chaîne de caractère récupéré de la bas de données
 * 
 * @param string $result La chaîne à déchiffrer
 * @return array         Un tableau à 2 valeurs, la première est le score de l'équipe 1 et
 *                       la seconde celle de l'équipe 2
 * 
 * @author Benoît Huftier
 */
function ph_get_scores(string $result) : array {
    $scores = array(0, 0);

    if (!empty($result)) {
        $pos = strpos($result, '/');
        if (false === $pos) {
            $scores[0] = intval($result);
        }
        else {
            $scores[0] = intval(substr($result, 0, $pos));
            $scores[1] = intval(substr($result, $pos + 1));
        }
    }

    return $scores;
}

/**
 * Renvoie une chaîne à mettre dans la base de donnée en fonction de 2 scores
 * 
 * @param int $score1 Le score de l'équipe 1 du match
 * @param int $score2 Le score de l'équipe 2 du match
 * @return string     Une chaîne à stocker dans la base de données pour avoir les 2 scores
 * 
 * @author Benoît Huftier
 */
function ph_create_score_string(int $score1, int $score2) : string {
    return strval($score1) . '/' . strval($score2);
}

/**
 * Créer un tableau de matchs en fonction des données renvoyées par la base de données.
 * 
 * @param PH\Tournament $tournament Le tournoi dont il est question.
 * @param array         $datas      Les données des matchs renvoyées par la base de données.
 * @return array                    L'arbre binaire bien formé des matchs du tournoi.
 * @throws \Exception               Si les données ne permettent pas de créer un arbre.
 * 
 * @author Benoît Huftier
 */
function ph_create_tournament_tree_from_datas(PH\Tournament $tournament, array $datas) : array {
    $teams = $tournament->getRegisteredTeams();
    $matches = array();

    foreach ($datas as $match_id => $d) {
        $matches[$match_id] = ph_verify_match_datas($teams, $d);
    }

    // On vérifie que toutes les équipes sont dans le tournoi
    foreach ($matches as $match) {
        $team1 = $match['team1'];
        $team2 = $match['team2'];

        if (!is_null($team1) && array_key_exists($team1->getId(), $teams)) {
            unset($teams[$team1->getId()]);
        }

        if (!is_null($team2) && array_key_exists($team2->getId(), $teams)) {
            unset($teams[$team2->getId()]);
        }
    }

    if (!empty($teams)) {
        throw new \Exception('Des équipes qui participent au tournoi ne sont pas dans l\'arbre.');
    }

    return ph_create_tree_from_array($matches);
}

/**
 * Vérifie que les données d'un match sont correcte et renvoie un tableau des données
 * bien formées.
 * 
 * @param array $teams Toutes les équipes du tournoi
 * @param array $datas Les données à vérifier
 * @return array       $datas bien formé
 * @throws \Exception  Si les équipes mises dans la bdd ne font pas partie du tournoi.
 * 
 * @author Benoît Huftier
 */
function ph_verify_match_datas(array $teams, array $datas) : array {
    // Récupération des ids des parents
    $parent1 = null;
    $parent2 = null;
    $p = $datas['parents_id'];

    if (!empty($p)) {
        $pos = strpos($p, '-');
        if (false === $pos) {
            $parent1 = intval($p);
        }
        else {
            $parent1 = intval(substr($p, 0, $pos));
            $parent2 = intval(substr($p, $pos + 1));
        }
    }

    // Récupération des équipes qui se rencontrent
    $team1 = null;
    $team2 = null;

    $t1_id = $datas['team1_id'];
    $t2_id = $datas['team2_id'];

    if (!is_null($t1_id)) {
        if (!array_key_exists($t1_id, $teams)) {
            throw new \Exception('Une équipe n\'existe pas dans le tournoi, mais fait parti d\'un match');
        }

        $team1 = $teams[$t1_id];
    }

    if (!is_null($t2_id)) {
        if (!array_key_exists($t2_id, $teams)) {
            throw new \Exception('Une équipe n\'existe pas dans le tournoi, mais fait parti d\'un match');
        }

        $team2 = $teams[$t2_id];
    }

    // Renvoie de toutes les données dans un tableau
    return array(
        'date' => date_create($datas['date']),
        'result' => $datas['result'],
        'team1' => $team1,
        'team2' => $team2,
        'parent1' => $parent1,
        'parent2' => $parent2,
        'id' => intval($datas['id']),
    );
}

/**
 * Création d'un arbre avec un tableau de matchs
 * 
 * @param array $matches Les matchs bien formés
 * @return array         Un arbre des matchs
 * @throws \Exception    Si l'arbre n'est pas un arbre binaire
 * 
 * @author Benoît Huftier
 */
function ph_create_tree_from_array(array $matches) : array {
    $tree = array();

    // On met d'abord toutes les feuilles dans l'arbre
    // On utilise un array_keys pour pouvoir unset la variable
    foreach (array_keys($matches) as $id) {
        if (is_null($matches[$id]['parent1']) && is_null($matches[$id]['parent2'])) {
            $tree[$id] = $matches[$id];
            unset($matches[$id]);
        }
    }
    
    // Tant que le tableau temporaire n'est pas vide, on regarde tous les matchs qui ont leurs parents
    // dans l'arbre. Si ces derniers sont dans l'arbre, on les place en parent de l'élément, on les
    // supprime de l'arbre et on ajoute l'élément à l'arbre.
    // À la fin de cette boucle, le tableau temporaire est vidé et il ne doit y avoir plus qu'un seul
    // élément dans l'arbre : la racine

    // Cette variable est là pour éviter les boucles infinies. Si le tableau temporaire n'est pas modifié,
    // c'est que des données sont mauvaises dans la bdd. 
    $count = count($matches) + 1;

    while ($count > count($matches)) {
        $count = count($matches);

        foreach (array_keys($matches) as $id) {
            $parent1_id = $matches[$id]['parent1'];

            if (array_key_exists($parent1_id, $tree)) {
                if (is_null($matches[$id]['parent2'])) {
                    $parent1 = $tree[$parent1_id];
                    unset($tree[$parent1_id]);

                    $tree[$id] = $matches[$id];
                    $tree[$id]['parent1'] = $parent1;

                    unset($matches[$id]);
                }
                else {
                    $parent2_id = $matches[$id]['parent2'];
                    
                    if (array_key_exists($parent2_id, $tree)) {
                        $parent1 = $tree[$parent1_id];
                        unset($tree[$parent1_id]);

                        $parent2 = $tree[$parent2_id];
                        unset($tree[$parent2_id]);
        
                        $tree[$id] = $matches[$id];
                        $tree[$id]['parent1'] = $parent1;
                        $tree[$id]['parent2'] = $parent2;
        
                        unset($matches[$id]);
                    }
                }
            }
        }
    }

    if (!empty($matches) || count($tree) > 1) {
        throw new \Exception('L\'arbre du tournoi n\'est pas bien formé dans la base de données');
    }
    
    return array_shift($tree);
}

/**
 * Permet de réparé un arbre de tournois quand ce dernier est mauvais dans la base de données
 * Cela peut se produire si une équipe rejoint ou quitte le tournois alors que les matchs avaient
 * déjà été programmé.
 * 
 * @param PH\Tournament $tournament Le tournoi pour lequel on veut créer un arbre.
 * @return array                    L'arbre des matchs du tournoi.
 * 
 * @author Benoît Huftier
 */
function ph_repair_tournament_tree(PH\Tournament $tournament) : array {
    global $phdb;

    $phdb->deleteMatchesForTournament($tournament->getId());
    return ph_create_tournament_tree($tournament);
}

/**
 * Instantie les matchs d'un tournoi dans la base de données et renvoie l'arbre
 * correspondant.
 * 
 * @param PH\Tournament $tournament Le tournoi pour lequel on veut créer un arbre.
 * @return array                    L'arbre des matchs du tournoi.
 * 
 * @author Benoît Huftier
 */
function ph_create_tournament_tree(PH\Tournament $tournament) : array {
    // On trie les équipes en fonction de leur niveau, les plus petits niveaux d'abord
    $teams = $tournament->getRegisteredTeams();
    usort($teams, fn(PH\Team $a, PH\Team $b) => $a->getLevel() > $b->getLevel());

    // Le nombre de tour (puissance de 2), en comptant le premier tour pas forcément complet
    $nb_turns = intval(ceil(log(count($teams), 2)));

    // On considère la date par défaut d'un match à la date de début du tournoi;
    $date = date_create($tournament->getFormattedStartingDate('Y-m-d h:i:s'));

    // Création de l'arbre
    $matches_tree = ph_create_matches_tree($nb_turns, count($teams), $teams, $date);

    // On met les valeurs dans la BDD
    ph_create_db_match_from_tree($tournament, $matches_tree);

    return $matches_tree;
}

/**
 * Créer un arbre de matchs en fonction du nombre d'équipes présentes.
 * Les équipes les plus faibles se rencontrent au premier tour.
 * Les résultats sont tous vides et les matchs ayant deux "ancêtres" n'ont aucunes données.
 * 
 * @param int      $nb_turns   Le nombre de tour restant avant d'arriver au feuille, le tour 1 c'est la feuille.
 * @param int      $nb_teams   Le nombre d'équipe à placer dans l'arbre. Ce nombre doit être entre 2^{$nb_turns - 1} et 2^{$nb_turns}.
 * @param array    $teams      Les équipes triées de la moins forte à la plus forte, passé par référence. À la fin le tableau est vide.
 * @param DateTime $date       La date de tous les matchs par défaut.
 * @return array               Un arbre contenant tous les matchs du tournoi.
 * 
 * @author Benoît Huftier
 */
function ph_create_matches_tree(int $nb_turns, int $nb_teams, array &$teams, DateTime $date) : array {
    $matches_tree =  array(
        'date' => $date,
        'result' => '',
        'team1' => null,
        'team2' => null,
        'parent1' => null,
        'parent2' => null
    );

    // Il y a 4 possibilités :
    // - Tour 1 et 2 équipes : Les deux équipes se rencontrent au tour 1 et sont parmis les plus faibles
    // - Tour 2 et 2 équipes : Les deux équipes se rencontrent au tour 2 et sont parmis les meilleures
    // - Tour 2 et 3 équipes : Deux équipes faibles se rencontre au tour 1 et une équipe parmis les meilleures attend la gagnante au tour 2
    // - Sinon on continue la récursion, aucune équipe ne participe au match pour le moment
    if (1 === $nb_turns) {
        $matches_tree['team1'] = array_shift($teams);
        $matches_tree['team2'] = array_shift($teams);
    }
    else {
        switch ($nb_teams) {
            case 3:
                $matches_tree['team2'] = array_pop($teams);
                $matches_tree['parent1'] = ph_create_matches_tree(1, 2, $teams, $date);
                break;
            case 2:
                $matches_tree['team1'] = array_pop($teams);
                $matches_tree['team2'] = array_pop($teams);
                break;
            default:
                $nb_teams_rec_1 = intval($nb_teams / 2);
                $nb_teams_rec_2 = $nb_teams - $nb_teams_rec_1;
        
                $matches_tree['parent1'] = ph_create_matches_tree($nb_turns - 1, $nb_teams_rec_1, $teams, $date);
                $matches_tree['parent2'] = ph_create_matches_tree($nb_turns - 1, $nb_teams_rec_2, $teams, $date);
                break;
        }
    }

    return $matches_tree;
}

/**
 * Ajoute des matchs dans la base de données en fonction d'un arbre donné.
 * Chaque feuille est ajouté récursivement, jusqu'à ce que la racine soit insérée.
 * 
 * @param PH\Tournament $tournament Le tournoi pour lequel on veut inséré des matchs
 * @param array         $match      L'arbre des matchs du tournoi. Il est envoyé en tant que référence
 *                                  pour que les ids soit ajoutées au tableau.
 */
function ph_create_db_match_from_tree(PH\Tournament $tournament, array &$match) : int {
    global $phdb;

    $team1_id = is_null($match['team1']) ? null : $match['team1']->getId();
    $team2_id = is_null($match['team2']) ? null : $match['team2']->getId();

    $parent1_id = is_null($match['parent1']) ? null : ph_create_db_match_from_tree($tournament, $match['parent1']);
    $parent2_id = is_null($match['parent2']) ? null : ph_create_db_match_from_tree($tournament, $match['parent2']);

    $result = ph_create_score_string(0, 0);
    $parents_id = null;
    
    if (!is_null($parent1_id) && !is_null($parent2_id)) {
        $parents_id = $parent1_id . '-' . $parent2_id;
    }
    else if (!is_null($parent1_id)) {
        $parents_id = strval($parent1_id);
    }
    else if (!is_null($parent2_id)) {
        $parents_id = strval($parent2_id);
    }

    $match_id = $phdb->insertMatchForTournament(
        $team1_id,
        $team2_id,
        $tournament->getId(),
        $match['date']->format('Y-m-d h:i:s'),
        $result,
        $parents_id
    );

    $match['id'] = $match_id;
    return $match_id;
}