<div class="in-registered-team captain-only preregistrations-only">
    <form action="<?php echo ph_get_route_link('validation/tournament/delete-registration.php'); ?>" method="POST">
        <input type="hidden" name="team-id" value="<?php echo $registered_team_id; ?>" />
        <input type="hidden" name="tournament-id" value="<?php echo $tournament->getId(); ?>" />
        <div class="modal fade" id="delete-registration-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Supprimer l'inscription ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        Êtes vous certain de vouloir supprimer l'inscription de l'équipe « <?php echo $player_team; ?> » ?
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