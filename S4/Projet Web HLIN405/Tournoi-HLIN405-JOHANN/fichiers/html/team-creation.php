<?php
$form = new PH\Templates\BootstrapForm(ph_include('forms/team-creation.php'), ph_get_validation_result());
?>

<div class="row align-items-center">
    <div class="col align-self-center">
        <div class="my-4 row text-center">
            <h2>Création d'équipe</h2>
        </div>
        <?php $form->render(); ?> 
    </div>
</div>