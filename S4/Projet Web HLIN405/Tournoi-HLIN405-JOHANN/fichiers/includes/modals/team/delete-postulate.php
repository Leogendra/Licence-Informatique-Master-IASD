<div class="postulate-only">
    <form action="<?php echo ph_get_route_link('validation/team/delete-postulate.php'); ?>" method="post">
        <input type="hidden" name="team-id" value="<?php echo $team->getId(); ?>" />
        <div class="modal fade" id="delete-postulate-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Supprimer postulat ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        Êtes vous certain de vouloir supprimer votre postulat pour l'équipe : <?php echo $team->getName(); ?> ?
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