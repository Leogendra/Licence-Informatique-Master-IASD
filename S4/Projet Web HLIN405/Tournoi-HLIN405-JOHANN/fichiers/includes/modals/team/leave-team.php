<div class="member-only">
    <form action="<?php echo ph_get_route_link('validation/team/leave-team.php'); ?>" method="post">
        <input type="hidden" name="team-id" value="<?php echo $team->getId(); ?>" />
        <div class="modal fade" id="leave-team-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quitter l'équipe</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        Êtes vous bien certain de vouloir quitter l'équipe <?php echo $team->getName(); ?> ?
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