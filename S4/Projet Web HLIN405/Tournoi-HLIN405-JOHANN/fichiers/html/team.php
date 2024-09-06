<?php
global $team;

$disable_if_inactive = function() use($team) : void {
    if (!$team->isActive()) {
        echo 'disabled';
    }
};

$title_if_inactive = function(string $title) use($team) : void {
    if (!$team->isActive()) {
        echo 'title="' . htmlentities($title) . '"';
    }
};
?>

<!-- Début boutons d'action -->
<div class="my-4 player-only">
    <div class="d-flex align-items-center captain-only">
        <div class="me-auto d-flex align-items-center">
            <div class="action-button px-1" <?php $title_if_inactive('Une équipe désactivée ne peut pas s\'inscrire à un tournois'); ?>>
                <button onclick="window.location.href='<?php echo ph_get_route_link('tournaments.php', array('starting-date' => (new DateTime('now'))->format('Y-m-d'), 'starting-date-comparator' => '>')); ?>'" type="button" class="btn btn-primary" <?php $disable_if_inactive(); ?>>Inscription tournois</a>
            </div>
            <div class="action-button px-1" <?php $title_if_inactive('Activez l\'équipe pour pouvoir changer de capitaine'); ?>>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#change-captain-modal" <?php $disable_if_inactive(); ?>>Changer capitaine</button>
            </div>
            <div class="action-button px-1" <?php $title_if_inactive('Activez l\'équipe pour pouvoir gérer les postulats'); ?>>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manage-postulate-modal" <?php $disable_if_inactive(); ?>>Gérer postulats</button>
            </div>
        </div>
        <div class="action-button team-active-only px-1">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#toggle-update-modal">Modifier les informations de l'équipe</button>
        </div>
        <div class="action-button team-active-only px-1">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#toggle-activation-modal">Désactiver l'équipe</button>
        </div>
        <div class="action-button team-inactive-only px-1">
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#toggle-activation-modal">Réactiver l'équipe</button>
        </div>
        <div class="action-button team-inactive-only team-deletable-only px-1">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-team-modal">Supprimer l'équipe</button>
        </div>
        <div class="action-button team-inactive-only team-non-deletable-only px-1" title="Vous ne pouvez pas supprimer cette équipe, elle a déjà participée à un tournoi">
            <button type="button" class="btn btn-danger disabled">Supprimer l'équipe</button>
        </div>
    </div>
    <div class="d-flex align-items-center member-only">
        <div class="action-button ms-auto">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#leave-team-modal">Quitter l'équipe</button>
        </div>
    </div>
    <div class="d-flex align-items-center postulate-only">
        <div class="me-auto"></div>
        <div class="px-1">
            En attente d'admission...
        </div>
        <div class="action-button px-1">
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#delete-postulate-modal">Supprimer demande d'admission</button>
        </div>
    </div>
    <div class="d-flex align-items-center blocked-only">
        <div class="action-button ms-auto" title="Vous avez déjà été refusé">
            <button type="button" class="btn btn-secondary disabled">Postuler dans l'équipe</button>
        </div>
    </div>
    <div class="d-flex align-items-center non-member-only">
        <div class="action-button ms-auto" <?php $title_if_inactive('Vous ne pouvez pas postuler dans une équipe inactive'); ?>>
            <button type="button" title="Postuler pour devenir membre de l'équipe" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#postulate-modal" <?php $disable_if_inactive(); ?>>Postuler dans l'équipe</button>
        </div>
    </div>
</div>
<!-- Fin boutons d'action -->

<div class="position-relative">
    <!-- Début banderole d'équipe -->
    <div class="row align-items-center justify-content-center">
        <div class="col-12 col-sm-6 col-md-4 text-center">
            <img src="<?php echo $team->getProfilePicture(); ?>" width="200px" class="rounded" alt="Image de profil de l'équipe <?php echo $team->getName(); ?>">
        </div>
        <div class="col-12 col-sm-6 col-md-8">
            <h2 class="text-center text-md-start mt-3"><?php echo $team->getName(); ?></h2>
        </div>
    </div>
    <!-- Fin banderole d'équipe -->

    <h3 class="text-center">L'équipe</h3>

    <!-- Début présentation des joueurs de l'équipe -->
    <div id="players">
        <div class="row justify-content-center mt-5">
            <?php
            $player = $team->getCaptain();
            include ph_include('player-card.php');
            ?>
        </div>
        <div class="row">
            <?php foreach ($team->getPlayers() as $player) {
                if ($player !== $team->getCaptain()) {
                    include ph_include('player-card.php');
                }
            } ?>
        </div>
    </div>
    <!-- Fin présentation des joueurs de l'équipe -->

    <!-- Début overlay team désactivée -->
    <table id="deactivate-team" class="team-inactive-only position-absolute w-100 h-100 top-0">
        <tr>
            <td class="text-center align-middle text-uppercase">Non active</td>
        </tr>
    </table>
    <!-- Fin overlay team désactivée -->
</div>

<?php
// Popups
include ph_include('modals/team/change-captain.php');
include ph_include('modals/team/toggle-activation.php');
include ph_include('modals/team/leave-team.php');
include ph_include('modals/team/delete-postulate.php');
include ph_include('modals/team/postulate.php');
include ph_include('modals/team/delete-team.php');
include ph_include('modals/team/manage-postulate.php');
include ph_include('modals/team/update-team-infos.php');
