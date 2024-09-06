<?php

namespace PH\Display;

/**
 * Classe d'affichage de l'arbre d'un tournoi.
 * Il faut penser à inclure les fichiers suivant après avoir afficher un tournoi :
 * 
 * - tournament-tree.css
 * - tournament-tree.js (pour le manager)
 */
class TournamentTree {
    private \PH\Tournament $tournament;
    private null|array $tree;
    private int $height;
    private array $rounds;
    private bool $can_manage;

    private const team_width = 250;
    private const team_height = 85;

    /**
     * Affichage de l'arbre du tournoi $tournament.
     * Ce constructeur va construire l'arbre s'il n'existe pas et qu'il peut le construire.
     * 
     * @param PH\Tournament $tournament Le tournoi dont on veut afficher l'arbre
     * @param bool          $can_manage Affichage de l'arbre avec les options de management (uniquement pour les managers)
     * 
     * @author Benoît Huftier
     */
    public function __construct(\PH\Tournament $tournament, bool $can_manage = false) {
        $this->tournament = $tournament;
        $this->can_manage = $can_manage;
        $this->tree = ph_get_tournament_tree($this->tournament);
        $this->height = ph_get_tree_height($this->tree);
        $this->rounds = ph_create_rounds_from_tree($this->tree, $this->height);

        if (!is_null($this->tree)) {
            $this->displayTree();
        }
        else {
            $this->displayMessage();
        }
    }

    private function displayMessage() : void {
        echo '<p class="text-center">Le tournoi ne possède pas assez d\'équipes pour afficher un arbre. Il en faut au moins 2.</p>';
    }

    private function displayTree() : void {
        $viewport_width = self::team_width * ($this->height + 1);

        if ($this->can_manage) {
            $this->displayActions();
            echo '<form method="POST" novalidate action="' . ph_get_route_link('validation/tournament-management/update-tournament-tree.php') . '">';
            echo     '<input type="hidden" name="tournament-id" value="' . $this->tournament->getId() . '" />';
        }
        echo '<div class="tree-viewport">';
        echo     '<div style="width: ' . $viewport_width . 'px">';
        foreach ($this->rounds as $round => $matches) {
            $this->displayRound($round, $matches);
        }
        echo     '</div>';
        echo '</div>';
        if ($this->can_manage) {
            echo    '<input class="btn btn-primary" type="submit" value="Enregistrer les modifications" />';
            echo '</form>';
        }
    }

    private function displayActions() : void {
        echo '<div class="d-flex align-items-center py-3">';
        echo     '<div class="px-1">';
        echo         '<button type="button" onclick="shuffleTree()" class="btn btn-primary">Remplir aléatoirement</button>';
        echo     '</div>';
        echo     '<div class="px-1">';
        echo         '<button type="button" onclick="resetTree()" class="btn btn-primary">Remettre à zéro</button>';
        echo     '</div>';
        echo '</div>';
    }

    private function displayRound(int $round, array $matches) : void {
        $height = self::team_height * pow(2, $round - 1);
        $margin = $height / 2;
        $final  = $round === $this->height;

        echo '<div class="float-start" style="width: ' . self::team_width . 'px">';
        foreach ($matches as $match) {
            $no_match = is_null($match);
            echo '<div';
            echo      ' class="' . ($this->can_manage ? 'manager-match' : 'public-match') . ($no_match ? ' no-match' : '') . ' match-wrapper position-relative"';
            echo      ' style="width: ' . self::team_width . 'px;';
            echo             ' height: ' . $height . 'px;';
            echo             ' margin: ' . $margin . 'px 0;"';
            echo      $no_match ? '' : ' id="match-' . $match['id'] . '"';
            echo      $no_match || $this->can_manage ? '' : ' data-bs-toggle="modal" data-bs-target="#game-' . $match['id'] . '-details-modal"';
            echo '>';
            if (!$no_match) {
                $this->displayTeams($match);
            }
            if ($final) {
                $this->displayWinner();
            }
            echo '</div>';
            if (!$no_match) {
                $this->displayModal($match);
            }
        }
        echo '</div>';
    }

    private function displayTeams(array $match_datas) : void {
        $scores = ph_get_scores($match_datas['result']);
        $team1_score = $scores[0];
        $team2_score = $scores[1];
        $match_id = $match_datas['id'];
        $team1 = $match_datas['team1'];
        $team2 = $match_datas['team2'];

        $team1_image = $this->createImageForTeam($team1);
        $team2_image = $this->createImageForTeam($team2);

        if ($this->can_manage) {
            $team_name_width = self::team_width - 90;
            $team1_parent_data = '';
            $team2_parent_data = '';

            if (is_null($match_datas['parent1_id'])) {
                $team1_input = $this->createSelectForTeam($team1, 'tree-datas[' . $match_id . '][team1-id]');
            }
            else {    
                $team1_input = $this->createAutomaticTeam($team1, 'tree-datas[' . $match_id . '][team1-id]');
                $team1_parent_data = ' data-parent="' . $match_datas['parent1_id'] . '"';
            }

            if (is_null($match_datas['parent2_id'])) {
                $team2_input = $this->createSelectForTeam($team2, 'tree-datas[' . $match_id . '][team2-id]');
            }
            else {
                $team2_input = $this->createAutomaticTeam($team2, 'tree-datas[' . $match_id . '][team2-id]');
                $team2_parent_data = ' data-parent="' . $match_datas['parent2_id'] . '"';
            }

            echo '<div class="team-wrapper d-flex team-1 position-absolute"' . $team1_parent_data . '>';
            echo     $team1_image;
            echo     '<div class="input-group px-1">';
            echo         $team1_input;
            echo         '<input';
            echo             ' class="form-control text-center team-result team-1-result"';
            echo             ' type="text"';
            echo             ' name="tree-datas[' . $match_id . '][score1]"';
            echo             ' onchange="onResultChange(this)"';
            echo             ' value="' . $team1_score . '"';
            echo         ' />';
            echo     '</div>';
            echo '</div>';
            echo '<div class="team-wrapper d-flex team-2 position-absolute"' . $team2_parent_data . '>';
            echo     $team2_image;
            echo     '<div class="input-group px-1">';
            echo         $team2_input;
            echo         '<input';
            echo             ' class="form-control text-center team-result team-2-result"';
            echo             ' type="text"';
            echo             ' name="tree-datas[' . $match_id . '][score2]"';
            echo             ' onchange="onResultChange(this)"';
            echo             ' value="' . $team2_score . '"';
            echo         ' />';
            echo     '</div>';
            echo '</div>';
            echo '<div class="position-absolute match-date">';
            echo     '<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#game-' . $match_id . '-details-modal">';
            echo         $match_datas['date']->format('d/m');
            echo     '</button>';
            echo '</div>';
        }
        else {
            $team1_text = is_null($team1) ? '' : $team1->getName();
            $team2_text = is_null($team2) ? '' : $team2->getName();
            $team1_winner = $team1_score > $team2_score;
            $team2_winner = $team2_score > $team1_score;

            echo '<div class="team-wrapper d-flex align-items-center team-1 position-absolute' . (is_null($team1) ? ' no-team' : '') . ($team1_winner ? ' winner-team' : '') . '">';
            echo     $team1_image;
            echo     '<div class="px-2">';
            echo         $team1_text;
            echo     '</div>';
            echo '</div>';
            echo '<div class="score score-team-1 position-absolute">';
            echo     $team1_score;
            echo '</div>';
            echo '<div class="team-wrapper d-flex align-items-center team-2 position-absolute' . (is_null($team2) ? ' no-team' : '') . ($team2_winner ? ' winner-team' : '') . '">';
            echo     $team2_image;
            echo     '<div class="px-2">';
            echo         $team2_text;
            echo     '</div>';
            echo '</div>';
            echo '<div class="score score-team-2 position-absolute">';
            echo     $team2_score;
            echo '</div>';
            echo '<div class="position-absolute match-date">';
            echo     $match_datas['date']->format('d/m');
            echo '</div>';
        }
    }

    private function displayWinner() : void {
        $winner = '';
        $pp = $this->createImageForTeam(null);
        $team1 = $this->tree['team1'];
        $team2 = $this->tree['team2'];
        
        if (!is_null($team1) && !is_null($team2)) {
            $final_score = ph_get_scores($this->tree['result']);
            $team1_score = $final_score[0];
            $team2_score = $final_score[1];

            if ($team1_score > $team2_score) {
                $winner = $team1->getName();
                $pp = $this->createImageForTeam($team1);
            }
            else if ($team2_score > $team1_score) {
                $winner = $team2->getName();
                $pp = $this->createImageForTeam($team2);
            }
        }

        echo '<div id="winner" class="d-flex align-items-center position-absolute' . (empty($winner) ? ' no-team' : '') . '" data-parent="' . $this->tree['id'] . '">';
        echo     $pp;
        echo     '<div class="px-2 team-name">';
        echo         $winner;
        echo     '</div>';
        if (!empty($winner)) {
             echo '<div class="p-1 cup position-absolute end-0">';
             echo     '<img class="rounded" width="35px" src="' . ph_get_resource_link('cup.png') . '" alt="Coupe du vainqueur" />';
             echo '</div>';
        }
        echo '</div>';
    }

    private function displayModal(array $match_datas) : void {
        echo '<div id="game-' . $match_datas['id'] . '-details-modal" class="modal fade" tabindex="-1" aria-hidden="true">';
        echo     '<div class="modal-dialog modal-dialog-centered">';
        echo         '<div class="modal-content">';
        echo             '<div class="modal-header">';
        echo                 '<h5 class="modal-title">';
        echo                     $this->can_manage ? 'Modifier la date' : 'Détails du match';
        echo                 '</h5>';
        echo                 '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>';
        echo             '</div>';
        echo             '<div class="modal-body">';
        if ($this->can_manage) {
            $this->displayManagerModal($match_datas);
        }
        else {
            $this->displayPublicModal($match_datas);
        }
        echo             '</div>';
        echo             '<div class="modal-footer">';
        echo                 '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>';
        echo             '</div>';
        echo         '</div>';
        echo     '</div>';
        echo '</div>';
    }

    private function displayManagerModal($match_datas) : void {
        $date = $match_datas['date']->format('Y-m-d');
        $min_date = $this->tournament->getFormattedStartingDate('Y-m-d');
        $max_date = $this->tournament->getFormattedEndingDate('Y-m-d');

        echo '<input';
        echo     ' name="tree-datas[' . $match_datas['id'] . '][date]"';
        echo     ' type="date"';
        echo     ' min="' . $min_date . '"';
        echo     ' max="' . $max_date . '"';
        echo     ' value="' . $date . '"';
        echo     ' class="form-control"';
        echo     ' id="new-preinscriptions-end-date"';
        echo     ' onchange="onDateChange(this)"';
        echo '/>';
    }

    private function displayPublicModal($match_datas) : void {
        $team1_name = is_null($match_datas['team1']) ? '' : $match_datas['team1']->getName();
        $team2_name = is_null($match_datas['team2']) ? '' : $match_datas['team2']->getName();
        $team1_url = is_null($match_datas['team1']) ? '#' : ph_get_route_link('team.php', array('id' => $match_datas['team1']->getId()));
        $team2_url = is_null($match_datas['team2']) ? '#' : ph_get_route_link('team.php', array('id' => $match_datas['team2']->getId()));
        $date_text = $match_datas['date']->format('d/m');
        $scores = ph_get_scores($match_datas['result']); 
        
        echo '<dl class="row">';
        echo     '<dt class="col-3 mb-3"">Équipe 1 :</dt>';
        echo     '<dd class="col-9 mb-3"><a href="' . $team1_url . '">' . $team1_name . '</a></dd>';
        echo     '<dt class="col-3 mb-3"">Équipe 2 :</dt>';
        echo     '<dd class="col-9 mb-3"><a href="' . $team2_url . '">' . $team2_name . '</a></dd>';
        echo     '<dt class="col-3 mb-3"">Score :</dt>';
        echo     '<dd class="col-9 mb-3">' . $scores[0] . ' - ' . $scores[1] . '</dd>';
        echo     '<dt class="col-3 mb-3"">Date :</dt>';
        echo     '<dd class="col-9 mb-3">';
        echo         $date_text;
        echo     '</dd>';
        echo '</dl>';
    }

    private function createImageForTeam(\PH\Team|null $team) : string {
        $img_src = is_null($team) ? ph_get_resource_link('no-team-pp.png') : $team->getProfilePicture();
        $img_alt = is_null($team) ? 'Aucune équipe' : 'Blason de l\'équipe ' . $team->getName();

        $img  = '<div class="p-1">';
        $img .=     '<img class="rounded team-pp" width="30px" src="' . $img_src . '" alt="' . $img_alt . '" />';
        $img .= '</div>';

        return $img;
    }
        

    private function createSelectForTeam(\PH\Team $team, string $name) : string {
        $teams = $this->tournament->getRegisteredTeams();

        $width = self::team_width - 120;

        $select  = '<select';
        $select .=     ' class="form-select team-select"';
        $select .=     ' name="' . $name . '"';
        $select .=     ' onchange="onTeamChange(this)"';
        $select .=     ' style="width: ' . $width . 'px"';
        $select .= '>';
        foreach ($teams as $team_id => $t) {
            $select .= '<option value="' . $team_id . '"' . ($team_id === $team->getId() ? ' selected' : '') . '>' . $t->getName() . '</option>';
        }
        $select .= '</select>';

        return $select;
    }

    private function createAutomaticTeam(\PH\Team|null $team, string $name) : string {
        $team_name = is_null($team) ? '' : $team->getName();
        $team_id = is_null($team) ? 0 : $team->getId();

        $width = self::team_width - 120;

        $fields  = '<input type="hidden" class="automatic-team-id" name="' . $name . '" value="' . $team_id . '" />';
        $fields .= '<input';
        $fields .=     ' class="form-control automatic-team-name"';
        $fields .=     ' type="text"';
        $fields .=     ' value="' . $team_name . '"';
        $fields .=     ' style="width: ' . $width . 'px"';
        $fields .=     ' disabled';
        $fields .= ' />';

        return $fields;
    }
}