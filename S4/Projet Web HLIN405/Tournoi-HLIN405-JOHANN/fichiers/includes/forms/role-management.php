<?php
$roles = array(
  Role::Administrator => Role::toString(Role::Administrator),
  Role::Manager => Role::toString(Role::Manager),
  Role::Player => Role::toString(Role::Player),
);
?>

<form action="" method="POST">
  <input type="hidden" value="<?php echo $user->getId(); ?>" name="user-id">
  <div class="modal-content">
    <div class="modal-header">
      <p><?php echo $user->getName(); ?></p>
    </div>
    <div class="modal-body">
      <p>
        <?php foreach ($roles as $role => $role_string) : ?>
          <div class="form-check">
            <input name="role-<?php echo $role; ?>" type="checkbox" class="form-check-input" id="checkbox" <?php if ($user->getPermissions()->hasFlag($role)) { echo 'checked'; } ?> />
            <label class="form-check-label" for="checkbox"><?php echo $role_string; ?></label>
          </div>
        <?php endforeach; ?>
      </p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-primary" onclick="ph_save_roles(this)">Sauvegarder</button>
      <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Fermer le pop-up</button>
    </div>
  </div>
</form>