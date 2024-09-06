<?php 

require_once __DIR__ . '/verify-management.php';

if (Status::Forthcoming === $tournament->getStatus()) {
    $constraints = new Core\Validation\Constraint\Collection(array(
        'new-preinscriptions-end-date' => new Core\Validation\Constraint\Multiple(array(
            new Core\Validation\Constraint\Required(),
            new Core\Validation\Constraint\DateTime(array(
                'max' => $tournament->getFormattedStartingDate('Y-m-d'),
                'min' => 'now'
            ))
        ))
    ));
    
    $validator = new Core\Validation\FormValidator($_POST, $constraints);
    
    if ($validator->isValid()) {
        global $phdb;

        $date = $_POST['new-preinscriptions-end-date'];
        $phdb->changePreinscriptionsEndDateForTournament($tournament->getId(), $date);
        ph_set_success_messages(array('Préinscriptions remises à la date du ' . date_create($date)->format('d/m/Y')));
    }
    else {
        $form_messages = ph_filter_and($validator->formMessages()['fields'], array('new-preinscriptions-end-date'));
        ph_save_form_data(array('success' => false, 'fields' => $form_messages), array());
    }
}

$redirect_management();