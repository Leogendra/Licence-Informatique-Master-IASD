<?php 

require_once __DIR__ . '/verify-team.php';

ph_redirect_if_not(Role::Administrator | Role::Player);

if (ph_get_user()->isPlayer()) {
    $_POST['captain-email'] = ph_get_user()->getEmail();
}

if (!ph_get_user()->sameUserThan($team->getCaptain())) {
    $redirect_home();
}

ph_redirect_if_not_form_submission(array(
    'team-name',
    'captain-email',
    'level',
    'phone',
    'email',
    'city',
    'adress',
    'postal',
    'adress-complement'
));

$redirect = function(array $form_messages, array $errors = array()) use($redirect_team) : void {
    ph_save_form_data($form_messages, $errors);
    $redirect_team();
};

// Système de sauvegarde de fichiers
ph_save_current_files();
ph_restore_saved_files();

$constraints = new Core\Validation\Constraint\Collection(array(
    'team-name' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\Length(array('min' => 3, 'max' => 30)),
    )),
    'captain-email' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\Email(),
    )),
    'level' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\IsNumber(),
    )),
    'phone' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\Phone(),
    )),
    'email' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\Email(),
    )),
    'city' => new Core\Validation\Constraint\Required(),
    'adress' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\Length(array('max' => 72)),
    )),
    'postal' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\IsNumber(),
        new Core\Validation\Constraint\Length(array('equals' => 5))
    )),
    'adress-complement' => new Core\Validation\Constraint\Multiple(array(
        new Core\Validation\Constraint\Required(),
        new Core\Validation\Constraint\Length(array('max' => 72)),
    )),
    'profile-picture' => new Core\Validation\Constraint\File(array(
        'types' => array('image/*'),
        'max_size' => '8 Mio',
    )),
), array(
    'profile-picture',
    'adress-complement'
));

$posted_datas = array_merge($_POST, $_FILES);
$validator = new Core\Validation\FormValidator($posted_datas, $constraints);

if ($team->getName() !== $posted_datas['team-name'] && !empty($phdb->getTeamFromName($posted_datas['team-name']))) {
    $validator->addErrorToField('team-name', 'Ce nom d\'équipe est déjà utilisé');
}

if ($validator->isValid()) {
    global $phdb;

    try {
        $captain_id = ph_get_user()->getPlayerId();
        $city_id = $phdb->insertCityIfNotExists(
            $_POST['city']
        );
        $zip_id = $phdb->insertZipIfNotExists(
            $_POST['postal'],
            $city_id
        );
        $location_id = $phdb->insertLocationIfNotExists(
            $_POST['adress'],
            $_POST['adress-complement'],
            $zip_id
        );
        $contact_id = $phdb->insertContactIfNotExists(
            $_POST['phone'],
            $_POST['email'],
            $location_id
        );

        $pp_path = null;

        if (array_key_exists('profile-picture', $_FILES)) {
            // Enregistrement de la photo de profil sur le serveur.
            $pp_path = ph_save_upload_file($_FILES['profile-picture'], 'teams/profile-pictures');
            unlink(ABSPATH . '/public_html' . $team->getProfilePicture());
        }

        $team_id = $phdb->updateTeam(
            $team->getId(),
            $_POST['team-name'],
            $_POST['level'],
            $pp_path,
            $contact_id
        );
    }
    catch (Exception $e) {
        $redirect($validator->formMessages(), array($e->getMessage()));
    }

    ph_clear_tmp_files();
    ph_set_success_messages(array('L\'équipe a bien été mise à jour.'));
    $redirect_team();
}
else {
    $form_messages = ph_filter_and($validator->formMessages()['fields'], array('team-name', 'captain-email', 'level', 'phone', 'email', 'postal'));
    $redirect(array('success' => false, 'fields' => $form_messages));
}