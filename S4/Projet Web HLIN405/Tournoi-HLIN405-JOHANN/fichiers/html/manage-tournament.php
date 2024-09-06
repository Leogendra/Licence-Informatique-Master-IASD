<?php
global $tournament;
?>

<div class="my-5 text-center">
    <h2>Gestion du tournoi <?php echo $tournament->getName(); ?></h2>
</div>

<div class="tournament-forthcoming">
    <?php include ph_include('manage-tournament-forthcoming.php'); ?>
</div>

<!-- Début des modifications du tournois --> 
<div class="tournament-preinscriptions mt-3">
    <?php include ph_include('manage-tournament-preinscriptions.php'); ?>
</div>
<!-- Fin des modifications du tournois -->

<h3 class="my-5 text-center">L'arbre du tournoi</h3>

<!-- Début de l'arbre du tournoi -->
<div class="mt-3" id="tournament-tree">
    <?php new PH\Display\TournamentTree($tournament, true); ?>
</div>
<!-- Fin arbre tournoi --> 

<h3 class="my-5 text-center">Les équipes inscrites</h3>

<div class="my-5" id="teams">
    <?php include ph_include('manage-tournament-teams.php'); ?>
</div>