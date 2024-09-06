<?php 
$form = new PH\Templates\BootstrapForm(ph_include('forms/page-test.php'), ph_get_validation_result());

$users = ph_get_all_users();
$user_roles = Role::toArray(ph_get_user()->getPermissions());
$roles = array(
  Role::Administrator => Role::toString(Role::Administrator),
  Role::Manager => Role::toString(Role::Manager),
  Role::Player => Role::toString(Role::Player),
);

?>

<div class="row align-items-center">
    <div class="col align-self-center">
        <div class="row">
            <h2>Gestion des Utilisateurs</h2>
        </div>

        <div class="row mb-3">
            <div class="mb-3">
                <label for="user-table" class="form-label">Utilisateurs</label>
                <table id="table" class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>email</th>
                        <th>rôle</th>
                        <th>bonjour</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $id) : ?>
                    <tr>
                        <td><?php echo $id->getName(); ?></td>
                        <td><?php echo $id->getEmail(); ?></td>
                        <td>
                            <?php foreach($user_roles as $role) :
                                echo $role;
                            endforeach; ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#popup" >Gérer les rôles</button>
                            <div id="popup" class="modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <p><?php echo $id->getName(); ?></p>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                <?php foreach ($roles as $role => $role_string) : ?>

                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="checkbox" <?php if ($id->getPermissions()->hasFlag($role)) { echo 'checked'; } ?> />
                                                        <label class="form-check-label" for="checkbox"><?php echo $role_string; ?></label>
                                                    </div>

                                                <?php endforeach; ?>
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary">Sauvegarder</button>
                                            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Fermer le pop-up</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

