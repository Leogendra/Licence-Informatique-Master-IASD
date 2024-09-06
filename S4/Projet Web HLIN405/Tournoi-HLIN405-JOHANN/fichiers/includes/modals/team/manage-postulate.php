<?php
$pending_postulates = $team->getPendingPlayers();
$actual_players = $team->getPlayers();
$blocked_postulates = $team->getRefusedPlayers();
?>

<div class="captain-only team-active-only">
    <div class="modal fade" id="manage-postulate-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gestion des postulats</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <!-- Début div d'erreur -->
                    <div id="error-div" class="d-none alert alert-danger fade show" role="alert">
                        <div class="error-text"></div>
                        <button type="button" class="btn-close position-absolute top-50 end-0 translate-middle" id="error-close-button"></button>
                    </div>
                    <!-- Fin div d'erreur -->

                    <!-- Début barre de navigation -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-postulates-tab" data-bs-toggle="tab" data-bs-target="#pending-postulates" type="button" role="tab" aria-controls="pending-postulates" aria-selected="true">Postulats en attente</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="actual-players-tab" data-bs-toggle="tab" data-bs-target="#actual-players" type="button" role="tab" aria-controls="actual-players" aria-selected="false">Membres actuels</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="blocked-players-tab" data-bs-toggle="tab" data-bs-target="#blocked-players" type="button" role="tab" aria-controls="blocked-players" aria-selected="false">Postulats bloqués</button>
                        </li>
                    </ul>
                    <!-- Fin barre de navigation -->

                    <div class="tab-content">
                        <!-- Début onglet "Postulat en attente" -->
                        <div class="tab-pane fade show active" id="pending-postulates" role="tabpanel" aria-labelledby="pending-postulates-tab">
                            <h5 class="my-4">Liste des postulats en attente</h5>
                            <?php if (empty($pending_postulates)): ?>
                                <p>Aucun postulat en attente</p>
                            <?php else: ?>
                                <?php foreach ($pending_postulates as $postulate) {
                                    include ph_include('team-postulate/pending.php');
                                } ?>
                            <?php endif; ?>
                        </div>
                        <!-- Fin onglet "Postulat en attente" -->

                        <!-- Début onglet "Membres actuels" -->
                        <div class="tab-pane fade" id="actual-players" role="tabpanel" aria-labelledby="actual-players-tab">
                            <h5 class="my-4">Joueurs actuels dans l'équipe</h5>
                            <?php foreach ($actual_players as $player) {
                                include ph_include('team-postulate/player.php');
                            } ?>
                        </div>
                        <!-- Fin onglet "Membres actuels" -->

                        <!-- Début onglet "Postulats bloqués" -->
                        <div class="tab-pane fade" id="blocked-players" role="tabpanel" aria-labelledby="blocked-players-tab">
                            <h5 class="my-4">Liste des joueurs bloqués</h5>
                            <?php if (empty($blocked_postulates)): ?>
                                <p>Aucun joueur bloqué</p>
                            <?php else: ?>
                                <?php foreach ($blocked_postulates as $postulate) {
                                    include ph_include('team-postulate/blocked.php');
                                } ?>
                            <?php endif; ?>
                        </div>
                        <!-- Fin onglet "Postulats bloqués" -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>
