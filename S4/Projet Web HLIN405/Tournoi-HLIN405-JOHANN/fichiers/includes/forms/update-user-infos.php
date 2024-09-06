

<div class="mb-3">
    <label for="email" class="form-label">Adresse email</label>
    <input name="email" type="email" class="form-control" id="email" value="<?php echo ph_get_user()->getEmail(); ?>" >
</div>

<div class="mb-3">
    <label for="nickname" class="form-label">Pseudonyme</label>
    <input name="nickname" type="text" class="form-control" id="nickname" value="<?php echo ph_get_user()->getName(); ?>">
</div>

<div class="mb-3 player-only">
    <label for="description" class="form-label">Description</label>
    <input name="description" type="text" class="form-control" id="description" value="<?php try { echo ph_get_user()->getDescription(); } catch (Exception $e) {} ?>">
</div>

<div class="mb-3">
    <label for="password" class="form-label">Mot de passe <abbr title="Laisser vide pour ne pas changer">*</abbr></label>
    <input name="password" type="password" class="form-control" id="password">
</div>

<div class="mb-3">
    <label for="password-repeat" class="form-label">Mot de passe (vérification)</label>
    <input type="password" class="form-control same-value" id="password-repeat" for="password">
</div>

<div class="mb-3">
    <label for="profile-picture" class="form-label">Photo de profil <abbr title="Laisser vide pour ne pas changer">*</abbr></label>
    <input name="profile-picture" class="form-control" type="file" id="profile-picture" accept=".jpg,.gif,.png" />
</div>