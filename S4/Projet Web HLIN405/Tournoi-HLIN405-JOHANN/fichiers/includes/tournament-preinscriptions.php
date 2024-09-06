<?php 
// Définition des variables dont nous avons besoin.

$location = $tournament->getLocation();

// Liens pour la carte
$complement = (empty($location->getAddressComplement()) ? '' : ' ' . $location->getAddressComplement());
$google_link = 'https://www.google.com/maps/search/' . str_replace(' ', '+', $location->getAddress() . $complement) . ',+' . $location->getZipCode() . '+' . $location->getCity();
$mappy_link  = 'https://fr.mappy.com/plan#/' . $location->getAddress() . $complement . ', ' . $location->getZipCode() . ' ' . $location->getCity();

// Est-ce que le joueur est dans une team qui est inscrite ? 
$player_team = '';
$player_team_id = 0;

foreach ($tournament->getRegisteredTeams() as $team) {
    if ($team->hasPlayer(ph_get_user())) {
        $player_team = $team->getName();
        $player_team_id = $team->getId();
        break;
    }
}

// Les équipes desquelles le joueur est capitaine
$player_teams_list = ph_get_teams_where_captain_is(ph_get_user());

$in_refused_teams = function(int $team_id) use($tournament) {
    foreach ($tournament->getRefusedTeams() as $team) {
        if ($team['team']->getId() === $team_id) {
            return true;
        }
    }
    return false;
};

$pending = ph_get_pending($tournament);

if (false === is_null($pending)) {
    $pending_id = $pending->getId();
    $pending_name = $pending->getName();
}
else {
    $pending_id = -1;
    $pending_name = '';
}

$registered_team_id = 0;
foreach ($tournament->getRegisteredTeams() as $team) {
    if ($team->hasPlayer(ph_get_user())) {
        $registered_team_id = $team->getId();
        break;
    }
}
?>

<h3 class="display-6 preregistrations-only">Phases de pré-inscriptions - Informations relative au tournoi</h3>
<h3 class="display-6 forthcoming-only">Tournoi à venir - Informations relative au tournoi</h3>
<hr />
<!-- Début des informations du tournoi -->
<dl class="row">
    <dt class="col-3">Dates</dt>
    <dd class="col-9 mb-3">
        Le tournoi se déroulera du 
        <?php echo $tournament->getFormattedStartingDate('d/m/Y') . ' au ' . $tournament->getFormattedEndingDate('d/m/Y'); ?>, 
        pour une durée totale de <?php echo $tournament->getDuration(); ?> jours.</dd>

    <dt class="col-3">Niveau</dt>
    <dd class="col-9 mb-3">Il est ouvert pour tous les niveaux.</dd>

    <dt class="col-3">Lieu</dt>
    <dd class="col-9 mb-3">
        <p>Le tournoi aura lieu à l'adresse suivante :</p>
        <dl class="row">
            <dt class="col-2">Département</dt> <dd class="col-10"><?php echo $location->getDepartment(); ?></dd>
            <dt class="col-2">Ville</dt> <dd class="col-10"><?php echo $location->getCity(); ?></dd>
            <dt class="col-2">Adresse</dt> <dd class="col-10"><?php echo $location->getAddress(); ?></dd>
            <div class="address-complement-exists">
                <dt class="col-4">Complément d'adresse</dt> <dd class="col-8"><?php echo $location->getAddressComplement(); ?></dd>
            </div>
            <dt class="col-2">Consulter</dt>
            <dd class="col-10">
                Consultez la carte détaillée des alentours
                <ul>
                    <li><a class="link-dark" href="<?php echo $google_link; ?>">Google</a></li>
                    <li><a class="link-dark" href="<?php echo $mappy_link; ?>">Mappy</a></li>
                </ul>
            </dd>
            <div id="map"></div>
        </dl>
    </dd>

    <dt class="col-3">Autre</dt>
    <dd class="col-9">Pour toute information supplémentaire, veuillez contacter <?php echo $tournament->getManager()->getName(); ?> à l'adresse mail suivante : <a href="mailto:<?php echo $tournament->getManager()->getEmail(); ?>"><?php echo $tournament->getManager()->getEmail(); ?></a></dd>
</dl>
<!-- Fin des informations du tournoi -->
<hr />
<!-- Division pour savoir si un joueur est dans une équipe inscrite --> 
<div class="player-only">
    <div class="in-registered-team">
        <p class="lead">Félicitation, vous êtes bien inscrit au tournoi, avec l'équipe « <a class="link-dark" href="<?php echo ph_get_route_link('team.php', array('id' => $player_team_id)); ?>"><?php echo $player_team; ?></a> ».</p>
        <!-- Début de la division pour suppression de l'inscription -->
        <div class="captain-only preregistrations-only">
            <form method="POST" action="<?php echo ph_get_route_link('validation/tournament/delete-registration.php'); ?>">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-registration-modal">Supprimer l'inscription</button><br />
                <i class="small">Vous pourrez toujours relancer une autre demande d'inscription</i>
            </form>
        </div>
        <!-- Fin de la division pour la suppression de l'inscription -->
    </div>
    <p class="not-in-registered-team lead">Vous n'êtes dans aucune équipe inscrite au tournoi.</p>
</div>
<!-- Fin division pour savoir si un joueur est dans une équipe inscrite -->
<!-- Début de la division de préinscription pour le capitaine -->
<div class="captain-only not-in-registered-team">
    <h3 class="display-6">Inscrivez votre équipe</h3>
    <hr />
    <!-- Div pour supprimer une inscription pending -->
    <div class="postulate-pending">
        <p class="lead">Vous vous êtes déjà préinscrit avec l'équipe « <a class="link-dark" href="<?php echo ph_get_route_link('team.php', array('id' => $pending_id)); ?>"><?php echo $pending_name; ?></a> ».</p>
        <button type="button" class="btn btn-warning preregistrations-only" data-bs-toggle="modal" data-bs-target="#delete-postulate-modal">Supprimer la demande d'inscription</button>
    </div>
    <!-- Fin de la div pour supprimer une inscription pending -->
    <!-- Div pour préinscrire une équipe -->
    <div class="not-postulate-pending preregistrations-only">
        <form method="POST" action="<?php echo ph_get_route_link('validation/tournament/registration.php'); ?>">
            <select class="form-select mb-3" id="team-to-register" name="team-id">
                <option value="-1">Choisir l'équipe à inscrire</option>
                <?php foreach($player_teams_list as $id => $name) : $refused = $in_refused_teams($id); ?>
                <option value="<?php echo $id; ?>" 
                        <?php echo $refused ? 'disabled' : ''; ?>>
                    <?php echo $refused ? $name . ' (inscription refusée)' : $name; ?>
                </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="tournament-id" value="<?php echo $tournament->getId(); ?>" />
            <button type="submit" class="btn btn-primary">Inscrire mon équipe</button>
        </form>
    </div>
    <!-- Fin de la div pour préinscrire une équipe -->
    <div class="forthcoming-only lead">Impossible de s'inscrire au tournoi, la phase de préinscriptions s'est terminée le <?php echo $tournament->getFormattedEndInscriptions('d/m/Y'); ?>.</div>
</div>
<!-- Fin de la division de préinscription pour le capitaine -->

<?php 

include ph_include('modals/tournament/delete-preregistration.php');
include ph_include('modals/tournament/delete-registration.php');

?>

<script>
    let address = '<?php echo htmlentities($location->getAddress() . ' ' . $location->getAddressComplement()); ?>';
    let url = 'https://nominatim.openstreetmap.org/search?street=' + address + '&city=<?php echo $location->getCity(); ?>&country=France&postalcode=<?php echo $location->getZipCode(); ?>&format=json';

    fetch(url)
        .then(res => res.json())
        .then((out) => {
            let mapDom = document.querySelector('#map');
            mapDom.setAttribute('style', 'height: 500px');
            out = out[0];
            let map = L.map('map').setView(L.latLng(out.lat, out.lon), 15);
            let marker = L.marker(L.latLng(out.lat, out.lon)).addTo(map);
            marker.bindPopup(address).openPopup();

            let layer = new L.StamenTileLayer('terrain');
            map.addLayer(layer);
        })
        .catch(err => {
            console.error('Impossible de charger la carte.');
        });
</script>