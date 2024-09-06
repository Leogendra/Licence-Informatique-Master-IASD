<form action="<?php echo ph_get_route_link('validation/tournament-management/stop-preinscriptions.php'); ?>" method="post">
    <input type="hidden" name="tournament-id" value="<?php echo $tournament->getId(); ?>" />
    <div class="modal fade" id="stop-preinscriptions-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Stopper la phase de préinscriptions ?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p>Plus aucune équipe ne pourra postuler au tournois et vous aurez donc <span id="nb-registered-teams"><?php echo count($tournament->getRegisteredTeams()); ?></span> équipes d'inscrites.</p>
                    <p>Êtes vous certain de vouloir arrêter les inscriptions maintenant ? La date de fin était censé être le <?php echo $tournament->getFormattedEndInscriptions('d/m/Y'); ?>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                    <button type="submit" class="btn btn-danger">Oui</button>
                </div>
            </div>
        </div>
    </div>
</form>