<div class="row">
    <?php 
    $teams = $tournament->getRegisteredTeams();
    if (empty($teams)) { ?>
        <p class="text-center">
            Aucune équipe n'est inscrite au tournoi.
            <span class="tournament-preinscriptions">
                <a role="button" href="" data-bs-toggle="modal" data-bs-target="#manage-preinscriptions-modal">Gérer les préinscriptions ?</a>
            </span>
            <span class="tournament-forthcoming">
                Pour en inscrire de nouvelles, vous devez
                <a role="button" href="" data-bs-toggle="modal" data-bs-target="#reset-preinscriptions-modal">remettre les préinscriptions.</a>
            </span>
        </p>
    <?php }
    foreach ($teams as $team) {
        include ph_include('team-card.php');
    } ?>
</div>