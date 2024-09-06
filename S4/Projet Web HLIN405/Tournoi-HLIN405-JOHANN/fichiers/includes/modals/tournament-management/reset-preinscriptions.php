<?php
global $tournament;
$min_date = date_create('now +1 day')->format('Y-m-d');
$max_date = $tournament->getFormattedStartingDate('Y-m-d');
?>

<form action="<?php echo ph_get_route_link('validation/tournament-management/reset-preinscriptions.php'); ?>" method="post">
    <input type="hidden" name="tournament-id" value="<?php echo $tournament->getId(); ?>" />
    <div class="modal fade" id="reset-preinscriptions-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Accepter de nouveau les préinscriptions ?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p>Remettre les préinscriptions va permettre à toutes les équipes de s'inscrire au tournoi de nouveau.</p>
                    <p>Attention, si vous changez les équipes du tournoi, les matchs seront tous remis à 0.</p>
                    <label for="new-preinscriptions-end-date" class="form-label">Date de préinscriptions</label>
                    <div class="input-group date" data-provide="datepicker">
                        <input
                            name="new-preinscriptions-end-date"
                            type="date"
                            min="<?php echo $min_date; ?>"
                            max="<?php echo $max_date; ?>"
                            value="<?php echo $min_date; ?>"
                            class="form-control date-withicon"
                            id="new-preinscriptions-end-date"
                        />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                    <button type="submit" class="btn btn-danger">Oui</button>
                </div>
            </div>
        </div>
    </div>
</form>