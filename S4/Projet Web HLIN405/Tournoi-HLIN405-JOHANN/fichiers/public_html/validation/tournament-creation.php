<?php 
require_once __DIR__ . '/../site-header.php';

ph_redirect_if_not_form_submission(array(
    'tournament-name',
    'tournament-type',
    'tournament-manager',
    'starting-date',
    'end-inscriptions-date',
    'duration',
    'city',
    'adress',
    'postal',
    'adress-complement'
));

ph_redirect_if_not(Role::Administrator);

$redirect = function(array $form_messages, array $errors = array()) : void {
    ph_save_form_data($form_messages, $errors);
    header('Location: ' . ph_get_route_link('tournament-creation.php'));
    exit;
};

$constraints = new Core\Validation\Constraint\Collection(array(
    'tournament-name' => new Core\Validation\Constraint\Required(),
    'tournament-type' => new Core\Validation\Constraint\IsNumber(),
    'tournament-manager' => new Core\Validation\Constraint\Required(),
    'starting-date' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\DateTime(array(
            'format' => 'Y-m-d',
            'min' => 'now | +1 day',
        )),
    )),
    'end-inscriptions-date' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\DateTime(array(
            'format' => 'Y-m-d',
            'max' => $_POST['starting-date']
        )),
    )),
    'duration' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\IsNumber(),
        // Entre 1 jours (minimum) et 30 jours (maximum). Pourra être allongé ou raccourci ultérieurement.
        new Core\Validation\Constraint\Regex('/^([1-9]|1[0-9]|2[0-9]|30)$/', 'La durée doit être entre 1 et 30 jours.'),
    )),
    'city' => new Core\Validation\Constraint\Required(),
    'adress' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\Length(array('max' => 72)),
    )),
    'adress-complement' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\Length(array('max' => 72)),
    )),
    'postal' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\IsNumber(),
        new Core\Validation\Constraint\Length(array('equals' => 5))
    )),
), array(
    'adress-complement',
));

$validator = new Core\Validation\FormValidator($_POST, $constraints);

if ($validator->isValid()) {
    global $phdb;

    try {
        $city_id = $phdb->insertCityIfNotExists(
            $_POST['city']
        );
        $zip_id = $phdb->insertZipIfNotExists(
            $_POST['postal'],
            $city_id
        );
        $location_id = $phdb->insertLocationIfNotExists(
            $_POST['adress'],
            empty($_POST['adress-complement']) ? null : $_POST['adress-complement'],
            $zip_id
        );
        $phdb->insertTournament(
            $_POST['tournament-name'],
            $_POST['starting-date'],
            $_POST['end-inscriptions-date'],
            $_POST['duration'],
            $_POST['tournament-manager'],
            $location_id,
            $_POST['tournament-type']
        );
    }
    catch (Exception $e) {
        $redirect($validator->formMessages(), array($e->getMessage()));
    }

    ph_set_success_messages(array('Le nouveau tournoi a bien été créé.'));
    header('Location: ' . ph_get_route_link('tournament-creation.php'));
    exit;
}
else {
    $form_messages = ph_filter_and($validator->formMessages()['fields'], array('starting-date', 'postal', 'end-inscriptions-date'));
    $redirect(array('success' => false, 'fields' => $form_messages));
}