<?php 
try {
    $accepted_keys = array(
        'name',
        'email',
        'role-1',
        'role-2',
        'role-3',
    );

    $conds_array = ph_process_search_data($_GET, $accepted_keys, array());
}
catch (Exception $e) {
    $conds_array = array();
}

$users = ph_get_all_users($conds_array);
?>

<div class="row align-items-center">
    <div class="col align-self-center">
        <div class="row">
            <h2>Gestion des Utilisateurs</h2>
        </div>

        <div class="row mb-3">
            <div class="mb-3">
            <div id="success-message"></div>
            <div id="fail-message"></div>
            <form method="GET" id="search-form">
                <select class="form-select search-select" style="display:none">
                    <option value="name">Nom</option>
                    <option value="email">Adresse mail</option>
                    <option value="role-1">Rôle 1</option>
                    <option value="role-2">Rôle 2</option>
                    <option value="role-3">Rôle 3</option>
                </select>
                <div class="d-flex flex-row-reverse">
                    <button type="submit" class="btn btn-primary p-2">Chercher</button>
                    <div class="p-1"></div>
                    <button type="button" class="btn btn-primary p-2" id="add-filter">Ajouter un filtre</button>
                    <div class="p-1"></div>
                    <a class="btn btn-primary p-2" href="<?php echo ph_get_route_link('role-management.php'); ?>">Remettre à zéro</a>
                </div>
            </form>
                <table id="table" class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>email</th>
                        <th>rôle</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : 
                    $popup_id = 'popup-' . $user->getId();
                    $user_roles = Role::toArray($user->getPermissions());
                    ?>
                    <tr>
                        <td><?php echo $user->getName(); ?></td>
                        <td><?php echo $user->getEmail(); ?></td>
                        <td>
                            <?php
                            $pass = false;

                            foreach ($user_roles as $role) {
                                if ($pass) {
                                    echo ', ';
                                }
                                echo $role;
                                $pass = true;
                            } ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $popup_id; ?>" >Gérer les rôles</button>
                            <div id="<?php echo $popup_id; ?>" class="modal">
                                <div class="modal-dialog">
                                    <?php
                                    include ph_include('forms/role-management.php');
                                    ?> 
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

<script>
<?php 
$enums = array(
    'roles' => array(Role::toString(Role::Administrator), Role::toString(Role::Manager), Role::toString(Role::Player)),
); 

?>
    window.localStorage.enums = '<?php echo ph_get_json_encode($enums); ?>';
<?php if (!empty($conds_array)) : ?>
    window.localStorage.searchData = '<?php echo ph_get_json_encode($conds_array); ?>';
<?php else : ?>
    window.localStorage.searchData = '';
<?php endif; ?>
</script>