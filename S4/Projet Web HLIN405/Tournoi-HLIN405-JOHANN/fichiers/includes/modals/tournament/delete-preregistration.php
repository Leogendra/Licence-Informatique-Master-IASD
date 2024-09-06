<div class="postulate-pending preregistrations-only">
    <form action="<?php echo ph_get_route_link('validation/tournament/delete-pending.php'); ?>" method="POST">
        <input type="hidden" name="tournament-id" value="<?php echo $tournament->getId(); ?>" />
        <input type="hidden" name="team-id" value="<?php echo $pending_id ?>" />
        <div class="modal fade" id="delete-postulate-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Supprimer la demande d'inscription ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        Êtes vous certain de vouloir supprimer la demande d'inscription pour l'équipe « <?php echo $pending_name; ?> » ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                        <button type="submit" class="btn btn-danger">Oui</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>