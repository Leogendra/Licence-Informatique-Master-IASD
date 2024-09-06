<?php
global $team;

$cities = ph_get_all_cities();
$zips = ph_get_all_zips();
$players = array();

if (ph_get_user()->isAdmin()) {
    $players = ph_get_all_players();
}
?>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="team-name" class="form-label">Nom de l'équipe</label>
        <input name="team-name" type="text" class="form-control" id="team-name" value="<?php echo $team->getName(); ?>">
    </div>
    <div class="admin-only col-md-6 mb-3">
        <label for="captain-email" class="form-label">Capitaine de l'équipe</label>
        <input class="form-control" list="captain-options" name="captain-email" id="captain-email" placeholder="Capitaine" autocomplete="off">
        <datalist id="captain-options">;
            <?php foreach ($players as $player) : ?>
                <option value="<?php echo $player->getEmail(); ?>"><?php echo $player->getName(); ?></option>;
            <?php endforeach; ?>
        </datalist>
    </div>
    <div class="player-only col-md-6 mb-3">
        <label for="captain" class="form-label">Capitaine de l'équipe</label>
        <input disabled class="form-control" id="captain" value="<?php echo ph_get_user()->getName(); ?>">
    </div>
    <div class="col-8 col-md-9 col-lg-10 mb-3">
        <label for="profile-picture" class="form-label">Photo de profil de l'équipe <abbr title="Laisser vide pour ne pas changer">*</abbr></label>
        <input name="profile-picture" class="form-control" type="file" id="profile-picture" accept=".jpg,.gif,.png" />
    </div>
    <div class="col-4 col-md-3 col-lg-2 mb-3">
        <label for="level" class="form-label">Niveau</label>
        <input name="level" type="number" class="form-control" id="level" value="<?php echo $team->getLevel(); ?>">
    </div>
</div>

<div class="row mb-3">
    <h3>Contacts</h3>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="phone" class="form-label">Téléphone</label>
        <input name="phone" type="text" class="form-control" id="phone" value="<?php echo $team->getContactInformations()['phone']; ?>">
    </div>
    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <input name="email" type="text" class="form-control" id="email" value="<?php echo $team->getContactInformations()['email']; ?>">
    </div>
</div>

<?php $location = $team->getContactInformations()['location']; ?>

<div class="row">
    <div class="mb-3">
        <label for="City" class="form-label">Ville</label>
        <input class="form-control" list="city-options" name="city" id="city" value="<?php echo $location->getCity(); ?>" autocomplete="off">
        <datalist id="city-options">;
            <?php foreach ($cities as $id => $value) : ?>
                <option value="<?php echo $value; ?>"><?php echo $value; ?></option>;
            <?php endforeach; ?>
        </datalist>
    </div>
</div>
<div class="row">
    <div class="col-md-8 mb-3">
        <label for="adress" class="form-label">Adresse</label>
        <input name="adress" type="text" class="form-control" id="adress" value="<?php echo $location->getAddress(); ?>">
    </div>
    <div class="col-md-4 mb-3">
        <label for="postal" class="form-label">Code postal</label>
        <input class="form-control" list="postal-options" name="postal" id="postal" value="<?php echo $location->getZipCode(); ?>" autocomplete="off">
        <datalist id="postal-options">;
            <?php foreach ($zips as $id => $value) : ?>
                <option value="<?php echo $value; ?>"><?php echo $value; ?></option>;
            <?php endforeach; ?>
        </datalist>
    </div>
</div>
<div class="row">
    <div class="mb-3">
        <label for="adress-complement" class="form-label">Complément d'adresse</label>
        <input name="adress-complement" type="text" class="form-control" id="adress-complement" value="<?php echo $location->getAddressComplement(); ?>">
    </div>
</div>