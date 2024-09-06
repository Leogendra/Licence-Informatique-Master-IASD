<?php 
require_once __DIR__ . '/../site-header.php';

// profile-picture n'est pas requis.
ph_redirect_if_not_form_submission(array( 
    'nickname',
    'email',
    'password',
));

$redirect = function(Core\Validation\FormValidator $validator, array $errors) : void {
    ph_save_form_data($validator->formMessages(), $errors);
    header('Location: ' . ph_get_route_link('register.php'));
    exit;
};

// Système de sauvegarde de fichiers
ph_save_current_files();
ph_restore_saved_files();

$constraints = new Core\Validation\Constraint\Collection(array(
    'nickname' => new Core\Validation\Constraint\Length(array(
        'min' => 2,
        'max' => 32,
    )),
    'email' => new Core\Validation\Constraint\Email(),
    'password' => new Core\Validation\Constraint\Length(array(
        'min' => 8,
        'max' => 36,
    )),
    'profile-picture' => new Core\Validation\Constraint\File(array(
        'types' => array('image/*'),
        'max_size' => '8 Mio',
    )),
), array (
    'profile-picture',
));

$posted_datas = array_merge($_POST, $_FILES);
$validator = new Core\Validation\FormValidator($posted_datas, $constraints);

if ($validator->isValid()) {
    if (true === ph_user_does_not_exists($posted_datas['email'])) {
        $pp_path = null;

        if (array_key_exists('profile-picture', $_FILES)) {
            // Enregistrement de la photo de profil sur le serveur.
            $pp_path = ph_save_upload_file($_FILES['profile-picture'], 'users/profile-pictures');
        }

        // Enregistrement de l'utilisateur dans la base de données
        ph_register_user(
            $posted_datas['email'],
            $posted_datas['nickname'],
            password_hash($posted_datas['password'], PASSWORD_BCRYPT),
            $pp_path,
        );

        // Nettoyage des fichiers temporaires et redirection vers la page suivante
        ph_clear_tmp_files();
        ph_set_success_messages(array('Succès de la création de votre compte !'));
        header('Location: ' . ph_get_redirect());
        ph_remove_redirect();
        exit;
    }
    else {
        $redirect($validator, array('Un utilisateur avec la même adresse email est déjà enregistré. Avez vous oublié votre mot de passe ?'));
    }
}
else {
    $redirect($validator, array());
}