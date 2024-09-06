<?php 
require_once __DIR__ . '/../site-header.php';

ph_redirect_if_not_form_submission(array( 
    'email',
    'password',
));

$redirect = function(Core\Validation\FormValidator $validator, array $errors) : void {
    ph_save_form_data($validator->formMessages(), $errors);
    header('Location: ' . ph_get_route_link('login.php'));
    exit;
};

$email_key = 'email';
$passwd_key = 'password';

$constraints = new Core\Validation\Constraint\Collection(array(
    $email_key => new Core\Validation\Constraint\Email(),
    $passwd_key => new Core\Validation\Constraint\IsString(),
));

$validator = new Core\Validation\FormValidator($_POST, $constraints);

if ($validator->isValid()) {
    $email = $_POST[$email_key];
    $passwd = $_POST[$passwd_key];
    if (true === ph_exists_matching_user($email, $passwd)) {
        ph_connect_user($email);
        header('Location: ' . ph_get_redirect());
        ph_remove_redirect();
        exit;
    }
    else {
        $redirect($validator, array('Adresse email ou mot de passe erroné.'));
    }
}
else {
    $redirect($validator, array());
}