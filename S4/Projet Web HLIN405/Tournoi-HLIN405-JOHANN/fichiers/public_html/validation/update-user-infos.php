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
    header('Location: ' . ph_get_route_link('profile.php'));
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
    'description' => new Core\Validation\Constraint\Required(),
    'profile-picture' => new Core\Validation\Constraint\File(array(
        'types' => array('image/*'),
        'max_size' => '8 Mio',
    )),
), array (
    'profile-picture',
    'description',
    'password',
));

$posted_datas = array_merge($_POST, $_FILES);
$validator = new Core\Validation\FormValidator($posted_datas, $constraints);

if ($validator->isValid()) {
    if (ph_get_user()->getEmail() === $posted_datas['email'] || true === ph_user_does_not_exists($posted_datas['email'])) {
        $pp_path = null;

        if (UPLOAD_ERR_OK === $_FILES['profile-picture']['error']) {
            // Enregistrement de la photo de profil sur le serveur.
            $pp_path = ph_save_upload_file($_FILES['profile-picture'], 'users/profile-pictures');
            unlink(ABSPATH . '/public_html' . ph_get_user()->getProfilePicture());
        }

        global $phdb;

        if (ph_get_user()->getEmail() !== $posted_datas['email'] || ph_get_user()->getName() !== $posted_datas['nickname'] || !empty($posted_datas['password']) || !is_null($pp_path)) {
            if (is_null($pp_path)) {
                $pp_path = basename(ph_get_user()->getProfilePicture());
            }
            try {
                // Mise à jour de l'utilisateur dans la base de données
                $phdb->updateUser(
                    ph_get_user()->getId(),
                    $posted_datas['email'],
                    $posted_datas['nickname'],
                    $pp_path,
                );

                if (!empty($posted_datas['password'])) {
                    $phdb->updatePassword(ph_get_user()->getId(), password_hash($posted_datas['password'], PASSWORD_BCRYPT));
                }
            }
            catch (Exception $e) {
                $result($validator, array('Une erreur est survenue lors de la mise à jour des données de l\'utilisateur.'));
            }
        }

        if (array_key_exists('description', $posted_datas) && ph_get_user()->getDescription() !== $posted_datas['description']) {
            try {
                $phdb->updatePlayer(
                    ph_get_user()->getPlayerId(),
                    $posted_datas['description']
                );
            }
            catch (Exception $e) {
                $result($validator, array('Une erreur est survenue lors de la mise à jour des données du joueur.'));
            }
        }

        // Nettoyage des fichiers temporaires et redirection vers la page suivante
        ph_clear_tmp_files();
        ph_set_success_messages(array('Données du compte modifiées avec succès.'));
        ph_disconnect_user();
        ph_connect_user($posted_datas['email']);
        header('Location: ' . ph_get_route_link('profile.php'));
        exit;
    }
    else {
        $redirect($validator, array('Un utilisateur avec la même adresse email est déjà enregistré.'));
    }
}
else {
    $redirect($validator, array());
}