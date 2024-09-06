
<div class="captain-only team-active-only">
    <form action="<?php echo ph_get_route_link('validation/team/change-captain.php'); ?>" method="post">
        <input type="hidden" name="team-id" value="<?php echo $team->getId(); ?>" />
        <div class="modal fade" id="change-captain-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Changer capitaine</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div>Sélectionner un nouveau capitaine d'équipe.</div>
                        <div class="mb-3"><strong>Attention, vous donnerez alors tous les droits à un autre joueur et vous ne serez plus capitaine !</strong></div>
                        <?php foreach ($team->getPlayers() as $player) : ?>
                            <div class="my-2 form-check">
                                <input
                                    class="form-check-input checked"
                                    type="radio"
                                    name="new-captain-id"
                                    value="<?php echo $player->getId(); ?>"
                                    id="new-captain-<?php echo $player->getId(); ?>"
                                    <?php echo $player->sameUserThan($team->getCaptain()) ? 'checked' : ''; ?>
                                />
                                <label class="form-check-label" for="new-captain-<?php echo $player->getId(); ?>">
                                    <?php echo $player->getName(); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" data-bs-target="#change-captain-security-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Changer de capitaine</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="change-captain-security-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        Êtes vous bien certain de renoncer à tous vos droits en faveur de ce joueur ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#change-captain-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Non</button>
                        <button type="submit" class="btn btn-danger">Oui</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>