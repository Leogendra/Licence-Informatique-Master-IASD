<?php 
$managers = ph_get_all_managers();
$cities   = ph_get_all_cities();
$zips     = ph_get_all_zips();
$types    = ph_get_tournament_types();

$deactivate_if_not_tournament = function(int $id) {
    if (ph_is_tournament($id)) {
        echo 'selected';
    }
    else {
        echo 'disabled';
    }
}
?>
<form action="<?php echo ph_get_route_link('validation/tournament-creation.php'); ?>" target="_self" method="POST" autocomplete="on">
    <div class="row">
        <h3>Informations</h3>
    </div>
    <div class="row">
        <div class="mb-3">
            <label for="tournament-name" class="form-label">Nom du tournoi</label>
            <input name="tournament-name" type="text" class="form-control" id="tournament-name" placeholder="Mon tournoi" >
        </div>
    </div>
    <div class="row">
        <div class="mb-3">
            <label for="tournament-type" class="form-label">Type de tournoi</label>
            <select class="form-select" name="tournament-type" id="tournament-type">
                <?php 
                // Désactivation si ce n'est pas un tournoi pour la v1 du site.
                foreach ($types as $id => $value) : ?>
                    <option value="<?php echo $id; ?>" <?php $deactivate_if_not_tournament($id); ?>><?php echo $value; ?></option>;
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="mb-3">
            <label for="tournament-manager" class="form-label">Gestionnaire du tournoi</label>
            <select id="manager-options" class="form-select" name="tournament-manager">
                <option value=''>Gestionnaire du tournoi</option>
                <?php foreach ($managers as $id => $value) : ?>
                    <option value="<?php echo $id; ?>"><?php echo $value; ?></option>;
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <h3>Date</h3>
    </div>
    <div class="row">
        <div class="mb-3">
            <div id="starting-date">
                <label for="starting-date" class="form-label">Date de début</label>
                <div class="input-group date" data-provide="datepicker">
                    <input name="starting-date" type="date" class="form-control date-withicon" id="starting-date-input">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="mb-3">
            <div id="end-inscriptions-date">
                <label for="end-inscriptions-date-input" class="form-label">Date de fin des inscriptions</label>
                <div class="input-group date" data-provide="datepicker">
                    <input name="end-inscriptions-date" type="date" class="form-control date-withicon" id="end-inscriptions-date-input">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <label class="form-label">Sélectionner méthode</label>
            <select class="form-select" id="duration-set-method-selector">
                <option value="1">Durée en jours</option>
                <option value="2">Date de fin</option>
            </select>
        </div>
        <div class="col-9">
            <div class="mb-3">
                <div id="duration">
                    <label for="duration" class="form-label">Durée</label>
                    <div class="input-group mb-2">
                        <input name="duration" type="number" class="form-control" id="duration-input">
                        <div class="input-group-append">
                            <div class="input-group-text">jours</div>
                        </div>
                    </div>
                </div>
                <div id="ending-date">
                    <label for="ending-date" class="form-label">Date de fin</label>
                    <div class="input-group date" data-provide="datepicker">
                        <input name="ending-date" type="date" class="form-control date-withicon" id="ending-date-input">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <h3>Lieux</h3>
    </div>

    <div class="row">
        <div class="mb-3">
            <label for="City" class="form-label">Ville</label>
            <input class="form-control" list="city-options" name="city" id="city" placeholder="Ville" autocomplete="off">
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
            <input name="adress" type="text" class="form-control" id="adress" placeholder="Adresse">
        </div>
        <div class="col-md-4 mb-3">
            <label for="postal" class="form-label">Code postal</label>
            <input class="form-control" list="postal-options" name="postal" id="postal" placeholder="Code postal" autocomplete="off">
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
            <input name="adress-complement" type="text" class="form-control" id="adress-complement" placeholder="Complément d'adresse">
        </div>
    </div>    
    <button class="btn btn-primary" type="submit">Envoyer</button>
</form>