<?php 
$page = isset($_GET['page'])   && $_GET['page'] > 0 ? (int) $_GET['page'] : 1;

$conds_array = array();

$datas = ph_get_teams_which_match($_GET, $page, $conds_array);
$nb_total_teams = $datas['nb_total_teams'];
$teams = $datas['teams'];

$total_pages = 0 === count($teams) ? 1 : (int) ceil($nb_total_teams / 10);
$prev_page = 1 !== $page;
$next_page = $total_pages !== $page;

$pagination_link = function(int $page) : string {
    return ph_get_route_link('teams.php',
        array_merge(
            $_GET, 
            array('page' => $page)
        )
    );
};

?>

<div class="row align-items-center">
    <div class="col align-self-center">
        <h2 class="text-center">Les équipes</h2>
        <div class="my-5">
            <!-- Début de la barre de recherche -->
            <!--<form method="get">
                <input type="text" class="form-control" id="search" name="search" placeholder="Rechercher des équipes" value="<?php #echo $search; ?>"/>
                <div class="mt-3 text-center">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    <a type="button" class="btn btn-primary player-admin-only" href="<?php #echo ph_get_route_link('team-creation.php'); ?>">Créer mon équipe</button>
                </div>
            </form>-->
            <form method="GET" id="search-form">
                <select class="form-select search-select" style="display:none">
                    <option value="name">Nom de l'équipe</option>
                    <option value="captain">Capitaine</option>
                    <option value="nb-players">Nombre de joueurs</option>
                    <option value="activity">En activité</option>
                </select>
                <div class="d-flex flex-row-reverse">
                    <button type="submit" class="btn btn-primary p-2">Chercher</button>
                    <div class="p-1"></div>
                    <button type="button" class="btn btn-primary p-2" id="add-filter">Ajouter un filtre</button>
                    <div class="p-1"></div>
                    <a class="btn btn-primary p-2" href="<?php echo ph_get_route_link('teams.php'); ?>">Remettre à zéro</a>
                </div>
            </form>
            <!-- Fin de la barre de recherche -->
            <!-- Début du tableau des équipes -->
            <div class="my-5">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nom de l'équipe</th>
                            <th>Capitaine</th>
                            <th>Joueurs</th>
                            <th>Activité</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_values($teams) as $n => $team) : ?>
                            <?php $href = ph_get_route_link('team.php', array('id' => $team->getId()));
                            /*
                            // Version a essayé si bugué
                            <tr>
                                <th scope="row" class="p-0"><a class="d-block w-100 h-100 p-2" href="<?php echo $href ?>"><?php echo $n + 1; ?></a></th>
                                <td class="p-0"><a class="d-block w-100 h-100 p-2" href="<?php echo $href ?>"><?php echo $team->getName(); ?></a></td>
                                <td class="p-0"><a class="d-block w-100 h-100 p-2" href="<?php echo $href ?>"><?php echo $team->getCaptain()->getName(); ?></a></td>
                                <td class="p-0"><a class="d-block w-100 h-100 p-2" href="<?php echo $href ?>"><?php echo count($team->getPlayers()); ?></a></td>
                                <td class="p-0"><a class="d-block w-100 h-100 p-2" href="<?php echo $href ?>"><?php echo $team->isActive() ? 'En activité' : 'Fermé'; ?></a></td>
                            </tr>
                            */
                            ?>
                            <tr class="<?php echo ($team->isActive() ? '' : 'deactivate-team') . ($team->hasPlayer(ph_get_user()) ? ' player-team' : ''); ?>" style="transform: rotate(0);">
                                <th scope="row">
                                    <a href="<?php echo $href; ?>" class="stretched-link">
                                        <?php echo $n + 1; ?>
                                    </a>
                                </th>
                                <td><?php echo $team->getName(); ?></td>
                                <td><?php echo $team->getCaptain()->getName(); ?></td>
                                <td><?php echo count($team->getPlayers()); ?></td>
                                <td><?php echo $team->isActive() ? 'En activité' : 'Fermé'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Fin du tableau des équipes -->
            <!-- Début de la barre de navigation -->
            <nav aria-label="Barre de pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $prev_page ? '' : 'disabled'; ?>">
                        <a class="page-link" href="<?php echo $prev_page ? $pagination_link($page - 1) : ''; ?>" aria-label="Précédent">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''?>">
                            <a class="page-link" href="<?php echo $i !== $page ? $pagination_link($i) : '#'; ?>">
                                <span><?php echo $i; ?></span>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo $next_page ? '' : 'disabled'; ?>">
                        <a class="page-link" href="<?php echo $next_page ? $pagination_link($page + 1) : ''; ?>" aria-label="Suivant">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- Fin de la barre de navigation -->
        </div>
    </div>
</div>

<script>
<?php 
$enums = array(
    'captain' => ph_get_all_captains(),
    'activity' => array(true => 'En activité', false => 'Fermée'),
); 

?>
    window.localStorage.enums = '<?php echo ph_get_json_encode($enums); ?>';
<?php if (!empty($conds_array)) : ?>
    window.localStorage.searchData = '<?php echo ph_get_json_encode($conds_array); ?>';
<?php else : ?>
    window.localStorage.searchData = '';
<?php endif; ?>
</script>