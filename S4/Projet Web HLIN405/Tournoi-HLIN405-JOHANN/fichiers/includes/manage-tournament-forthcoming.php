<?php 
$form = new PH\Templates\BootstrapForm(ph_include('modals/tournament-management/reset-preinscriptions.php'), ph_get_validation_result()); 
?>

<div class="d-flex align-items-center">
    <div class="px-1">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reset-preinscriptions-modal">Remettre les préinscriptions</button>
    </div>
</div>

<?php
$form->render();