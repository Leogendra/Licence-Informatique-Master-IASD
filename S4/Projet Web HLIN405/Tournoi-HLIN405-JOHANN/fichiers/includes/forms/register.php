<form method="POST" target="_self" action="<?php echo ph_get_route_link('validation/register.php'); ?>" autocomplete="on" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input name="email" type="email" class="form-control" id="email" placeholder="nom@exemple.fr" >
        </div>

        <div class="mb-3">
            <label for="nickname" class="form-label">Pseudonyme</label>
            <input name="nickname" type="text" class="form-control" id="nickname" placeholder="">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input name="password" type="password" class="form-control" id="password" placeholder="">
        </div>

        <div class="mb-3">
            <label for="password-repeat" class="form-label">Mot de passe (vérification)</label>
            <input type="password" class="form-control same-value" id="password-repeat" for="password">
        </div>

        <div class="mb-3">
            <label for="profile-picture" class="form-label">Photo de profil</label>
            <input name="profile-picture" class="form-control" type="file" id="profile-picture" accept=".jpg,.gif,.png" />
        </div>

    <button class="btn btn-primary" type="submit">Envoyer</button>
</form>