<?php 
global $tournament;
?>

<div class="d-md-flex align-items-center">
    <h2 class="h1"><?php echo $tournament->getName(); ?></h2>

    <div class="team-manager-only ms-auto">
        <a type="button" class="btn btn-primary" href="<?php echo ph_get_route_link('manage-tournament.php', array('id' => $tournament->getId())); ?>">Allez à la page de gestion du tournois</a>
    </div>
</div>

<!-- Début de l'arbre du tournoi -->
<div class="tournament-tree mt-3">
    <?php new PH\Display\TournamentTree($tournament); ?>
</div>
<!-- Fin arbre tournoi --> 

<!-- Début des préinscriptions --> 
<div class="preinscriptions mt-3">
    <?php include ph_include('tournament-preinscriptions.php'); ?>
</div>
<!-- Fin des préinscriptions -->