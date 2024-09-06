<?php 
$form = new PH\Templates\BootstrapForm(ph_include('forms/update-team-infos.php'), ph_get_validation_result());
?>
<div class="captain-only">
    <form method="POST" target="_self" action="<?php echo ph_get_route_link('validation/team/update-team-infos.php'); ?>" autocomplete="on" enctype="multipart/form-data">
        <input type="hidden" name="team-id" value="<?php echo $team->getId(); ?>" />
        <div class="modal fade" id="toggle-update-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="toggle-update-modal">Changer les informations de l'équipe</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php $form->render(); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>