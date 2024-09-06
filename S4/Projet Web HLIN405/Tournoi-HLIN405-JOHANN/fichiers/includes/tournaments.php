<?php 

// Pour que cette page fonctionne correctement, veuillez vérifier que vous avez défini les variables suivantes dans la page qui l'inclue :
    // - $file: string  (le fichier qui inclue ce fichier)
    // - $page: int     (la page actuelle de $_GET['page'])
    // - $title: string (le titre affiché dans le <h2>)
    // - $limit: int    (le nombre de tournois par page)
    // - $total_tournaments: int (le nombre total de tournois)

$total_pages = 0 === count($tournaments) ? 1 : (int) ceil($total_tournaments / $limit);
$prev_page = 1 !== $page;

$next_page = $total_pages !== $page;

$pagination_link = function(int $page) use($file) : string {
    $_GET['page'] = $page;
    return ph_get_route_link($file, $_GET);
};

?>

<div class="row align-items-center">
    <div class="col align-self-center">
        <h2 class="text-center"><?php echo $title; ?></h2>
        <div class="my-5">
            <h3 class="mb-5">Critères de recherche</h3>
            <!-- Début de la barre de recherche -->
            <form method="GET" id="search-form">
                <select class="form-select search-select" style="display:none">
                    <option value="name">Nom du tournoi</option>
                    <option value="starting-date">Date de début</option>
                    <option value="ending-date">Date de fin</option>
                    <option value="duration">Durée</option>
                    <option value="type">Type</option>
                    <option value="department">Département</option>
                    <option value="city">Ville</option>
                    <option value="status">Status</option>
                    <option value="manager" class="manager-search">Gestionnaire</option>
                </select>
                <div class="d-flex flex-row-reverse">
                    <button type="submit" class="btn btn-primary p-2">Chercher</button>
                    <div class="p-1"></div>
                    <button type="button" class="btn btn-primary p-2" id="add-filter">Ajouter un filtre</button>
                    <div class="p-1"></div>
                    <a class="btn btn-primary p-2" href="<?php echo ph_get_route_link($file); ?>">Remettre à zéro</a>
                </div>
            </form>
            <!-- Fin de la barre de recherche -->
            <!-- Début du tableau des tournois -->
            <div class="my-5">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nom du tournoi</th>
                            <th>Fin des inscriptions</th>
                            <th>Date de début</th>
                            <th>Date de fin (durée)</th>
                            <th>Type</th>
                            <th>Département</th>
                            <th>Ville</th>
                            <th>Status</th>
                            <th>Gestionnaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_values($tournaments) as $n => $tournament) : ?>
                            <?php $href = ph_get_route_link($link, array('id' => $tournament->getId())); ?>
                            <tr style="transform: rotate(0);">
                                <th scope="row">
                                    <a href="<?php echo $href; ?>" class="stretched-link">
                                        <?php echo (($page - 1) * $limit) + $n + 1; ?>
                                    </a>
                                </th>
                                <td><?php echo $tournament->getName(); ?></td>
                                <td><?php echo $tournament->getFormattedEndInscriptions('d/m/Y'); ?></td>
                                <td><?php echo $tournament->getFormattedStartingDate('d/m/Y'); ?></td>
                                <td><?php echo $tournament->getFormattedEndingDate('d/m/Y') . ' (' . $tournament->getDuration() . ' jours)'; ?></td>
                                <td><?php echo $tournament->getType(); ?></td>
                                <td><?php echo $tournament->getLocation()->getDepartment(); ?></td>
                                <td><?php echo $tournament->getLocation()->getCity(); ?></td>
                                <td><?php echo $tournament->getStatusString(); ?></td>
                                <td><?php echo $tournament->getManager()->getName(); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Fin du tableau des tournois -->
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
    'type' => array(Type::toString(Type::Coupe), Type::toString(Type::Championnat), Type::toString(Type::Poules)),
    'status' => array(Status::toString(Status::OnGoing), Status::toString(Status::Forthcoming), Status::toString(Status::PreRegistrations), Status::toString(Status::Finished)),
    'manager' => ph_get_all_tournaments_managers(),
); 

?>
    window.localStorage.enums = '<?php echo ph_get_json_encode($enums); ?>';
<?php if (!empty($conds_array)) : ?>
    window.localStorage.searchData = '<?php echo ph_get_json_encode($conds_array); ?>';
<?php else : ?>
    window.localStorage.searchData = '';
<?php endif; ?>
</script>