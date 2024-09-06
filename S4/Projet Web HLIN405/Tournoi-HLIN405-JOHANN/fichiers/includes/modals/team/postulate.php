<div class="non-member-only team-active-only">
    <form action="<?php echo ph_get_route_link('validation/team/postulate.php'); ?>" method="post">
        <input type="hidden" name="team-id" value="<?php echo $team->getId(); ?>" />
        <div class="modal fade" id="postulate-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Postuler dans l'équipe <?php echo $team->getName(); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        Veuillez confirmer votre postulat dans l'équipe suivante : <?php echo $team->getName(); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Confirmer</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>