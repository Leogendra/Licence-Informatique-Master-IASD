<div class="d-flex align-items-center">
    <div class="px-1">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#update-tournament-form-modal">Modifier informations</button>
    </div>
    <div class="px-1">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manage-preinscriptions-modal">Gérer les préinscriptions</button>
    </div>
    <div class="px-1">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stop-preinscriptions-modal">Mettre fin aux préinscriptions</button>
    </div>
</div>

<?php include ph_include('modals/tournament-management/update-tournament.php'); ?>
<?php include ph_include('modals/tournament-management/manage-preinscriptions.php'); ?>
<?php include ph_include('modals/tournament-management/stop-preinscriptions.php'); ?>