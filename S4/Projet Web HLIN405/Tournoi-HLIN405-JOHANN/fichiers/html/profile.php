<?php 
$form = new PH\Templates\BootstrapForm(ph_include('forms/update-user-infos.php'), ph_get_validation_result());
?>

<div class="position-relative">
    <!-- Début des infos de l'utilisateur -->
    <div class="row align-items-center justify-content-center">
        <div class="col-12 col-sm-4 col-md-4 text-center">
            <img src="<?php echo ph_get_user()->getProfilePicture(); ?>" width="200px" class="rounded" alt="Image de profil de <?php echo ph_get_user()->getName(); ?>">
        </div>
        <div class="col-12 col-sm-8 col-md-8">
            <dl class="row mt-5">
                <dt class="col-3">Nom affiché</dt>
                <dd class="col-9 mb-3"><?php echo ph_get_user()->getName(); ?></dd>

                <dt class="col-3">Adresse mail</dt>
                <dd class="col-9 mb-3"><?php echo ph_get_user()->getEmail(); ?></dd>
                
                <dt class="col-3 player-only">Description</dt>
                <dd class="col-9 mb-3 player-only"><?php try { echo ph_get_user()->getDescription(); } catch(Exception $e) {} ?></dd>

                <dt class="col-3">Mot de passe</dt>
                <dd class="col-9 mb-3">********</dd>
            </dl>
        </div>
    </div>
    <!-- Fin des infos de l'utilisateur -->
    <!-- Actions --> 
    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#updateInfos">Modifier mes informations</button>
    <!-- Fin des actions -->

    <!-- Modal -->
    <div class="modal fade" id="updateInfos" tabindex="-1" aria-hidden="true">
        <form method="POST" target="_self" action="<?php echo ph_get_route_link('validation/update-user-infos.php'); ?>" autocomplete="on" enctype="multipart/form-data">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateInfosTitle">Changer ses informations</h5>
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
        </form>
    </div>
</div>