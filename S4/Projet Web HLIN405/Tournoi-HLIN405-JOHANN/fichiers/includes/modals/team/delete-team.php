
<div class="captain-only team-inactive-only team-deletable-only">
    <form action="<?php echo ph_get_route_link('validation/team/delete-team.php'); ?>" method="post">
        <input type="hidden" name="team-id" value="<?php echo $team->getId(); ?>" />
        <div class="modal fade" id="delete-team-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Suppression de l'équipe</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes vous bien certain de vouloir définitivement supprimer l'équipe <?php echo $team->getName(); ?> ?</p>
                        <p>Cela signifie qu'il n'y aura plus aucun historique de l'équipe, plus aucun joueur n'aura souvenir d'y être passé et toute trace de l'équipe sera supprimée de notre base de données.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-danger" data-bs-target="#delete-team-security-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Supprimer l'équipe</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete-team-security-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sûr sûr sûr ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        Une dernière confirmation, au cas où ce serait un mauvais clic !
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Noooon</button>
                        <button type="submit" class="btn btn-danger">Supprimer l'équipe</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>