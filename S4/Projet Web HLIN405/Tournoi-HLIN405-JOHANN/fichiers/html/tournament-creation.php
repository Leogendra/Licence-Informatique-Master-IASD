<?php 
$form = new PH\Templates\BootstrapForm(ph_include('forms/tournament-creation.php'), ph_get_validation_result());
?>

<div class="row align-items-center">
    <div class="col align-self-center">
        <div class="row">
            <h2>Création de tournoi</h2>
        </div>
        <?php $form->render(); ?> 
    </div>
</div>