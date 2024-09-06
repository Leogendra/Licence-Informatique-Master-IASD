<div class="captain-only">
    <form action="<?php echo ph_get_route_link('validation/team/toggle-activation.php'); ?>" method="post">
        <input type="hidden" name="team-id" value="<?php echo $team->getId(); ?>" />
        <input type="hidden" name="activation" value="<?php echo !$team->isActive(); ?>" />
        <div class="modal fade" id="toggle-activation-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Voulez vous vraiment <?php echo $team->isActive() ? 'désactiver' : 'réactiver'; ?> l'équipe "<?php echo $team->getName(); ?>"</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p>Une équipe désactivée n'est pas dissoute. Elle est encore visible dans les historiques et vous pouvez la réactivée dans la page de l'équipe.</p>
                        <p>Une équipe désactivée ne peut pas postuler à un tournois, et personne ne peut la rejoindre.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                        <button type="submit" class="btn btn-danger"><?php echo $team->isActive() ? 'Désactiver' : 'Réactiver'; ?></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>