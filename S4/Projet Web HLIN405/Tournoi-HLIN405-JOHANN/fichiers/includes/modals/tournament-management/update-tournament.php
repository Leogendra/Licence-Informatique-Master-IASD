
<?php 
$form = new PH\Templates\BootstrapForm(ph_include('forms/tournament-update.php'), ph_get_validation_result());
?>

<div class="modal fade" id="update-tournament-form-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Modification du tournoi</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <?php $form->render(); ?> 
            </div>
        </div>
    </div>
</div>