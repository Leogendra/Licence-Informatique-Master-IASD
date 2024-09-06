<?php
$pending = $tournament->getPendingTeams();
$actual = $tournament->getAcceptedTeams();
$blocked = $tournament->getRefusedTeams();
?>

<div class="modal fade" id="manage-preinscriptions-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestion des préinscriptions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    Attention, ajouter ou supprimer une équipe du tournoi va remettre l'arbre à 0 !
                </div>
                <!-- Début div d'erreur -->
                <div id="error-div" class="d-none alert alert-danger fade show" role="alert">
                    <div class="error-text"></div>
                    <button type="button" class="btn-close position-absolute top-50 end-0 translate-middle" id="error-close-button"></button>
                </div>
                <!-- Fin div d'erreur -->

                <!-- Début barre de navigation -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">Équipes en attente</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="actual-tab" data-bs-toggle="tab" data-bs-target="#actual" type="button" role="tab" aria-controls="actual" aria-selected="false">Équipes actuelles</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="blocked-tab" data-bs-toggle="tab" data-bs-target="#blocked" type="button" role="tab" aria-controls="blocked" aria-selected="false">Équipes bloquées</button>
                    </li>
                </ul>
                <!-- Fin barre de navigation -->

                <div class="tab-content">
                    <!-- Début onglet "Équipes en attente" -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                        <h5 class="my-4">Liste des postulats en attente</h5>
                        <?php if (empty($pending)): ?>
                            <p>Aucune équipe ne s'est préinscrite pour le moment</p>
                        <?php else: ?>
                            <?php foreach ($pending as $team_datas) {
                                include ph_include('tournament-preinscriptions/pending.php');
                            } ?>
                        <?php endif; ?>
                    </div>
                    <!-- Fin onglet "Équipes en attente" -->

                    <!-- Début onglet "Équipes actuelles" -->
                    <div class="tab-pane fade" id="actual" role="tabpanel" aria-labelledby="actual-tab">
                        <h5 class="my-4">Équipes inscrites au tournoi</h5>
                        <?php if (empty($actual)): ?>
                            <p>Aucune équipe n'est inscrite au tournoi</p>
                        <?php else: ?>
                            <?php foreach ($actual as $team_datas) {
                                include ph_include('tournament-preinscriptions/actual.php');
                            } ?>
                        <?php endif; ?>
                    </div>
                    <!-- Fin onglet "Équipes actuelles" -->

                    <!-- Début onglet "Équipes bloquées" -->
                    <div class="tab-pane fade" id="blocked" role="tabpanel" aria-labelledby="blocked-tab">
                        <h5 class="my-4">Liste des équipes bloquées</h5>
                        <?php if (empty($blocked)): ?>
                            <p>Aucune équipe bloquée</p>
                        <?php else: ?>
                            <?php foreach ($blocked as $team_datas) {
                                include ph_include('tournament-preinscriptions/blocked.php');
                            } ?>
                        <?php endif; ?>
                    </div>
                    <!-- Fin onglet "Équipes bloquées" -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>