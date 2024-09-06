<?php

if (false === defined('PH_USER_SESSION_KEY')) {
    define('PH_USER_SESSION_KEY', 'ph_user');
}

function ph_get_public_user() : PH\User {
    return PH\User::createPublicUser();
}

/**
 * Instantie l'utilisateur s'il est connecté.
 * 
 * @author Benoît Huftier
 */
function ph_get_user() : PH\User {
    if (!isset($_SESSION[PH_USER_SESSION_KEY])) {
        return ph_get_public_user();
    }

    if (!is_string($_SESSION[PH_USER_SESSION_KEY])) {
        ph_disconnect_user();
        return ph_get_public_user();
    }

    try {
        $user = PH\User::createFromEmail($_SESSION[PH_USER_SESSION_KEY]);
    }
    catch (Exception $_) {
        ph_disconnect_user();
        $user = ph_get_public_user();
    }

    return $user;
}

/**
 * Création de l'utilisateur en fonction de son identifiant.
 * N'appelez cette méthode que lorsque l'utilisateur se connecte !!
 * 
 * @param  string $email l'email de l'utilisateur en question.
 * 
 * @author Benoît Huftier
 */
function ph_connect_user(string $email) : void {
    $_SESSION[PH_USER_SESSION_KEY] = $email;
}

/**
 * Déconnecte l'utilisateur.
 * 
 * @author Johann Rosain
 */
function ph_disconnect_user() : void {
    unset($_SESSION[PH_USER_SESSION_KEY]);
}

/**
 * Vérifie si un utilisateur a le même email / mot de passe dans la base de données.
 * 
 * @param  string $email  L'adresse mail de l'utilisateur.
 * @param  string $passwd Le mot de passe, NON CRYPTÉ, de l'utilisateur.
 * @return bool           Vrai si l'utilisateur existe.
 * 
 * @author Johann Rosain
 */
function ph_exists_matching_user(string $email, string $passwd) : bool {
    global $phdb;

    $db_user = $phdb->getUserFromEmail($email);

    if (true === empty($db_user)) {
        return false;
    }

    $db_passwd = $db_user['passwd'];

    return password_verify($passwd, $db_passwd);
}

/**
 * @return bool Vrai si le joueur donné est capitaine d'au moins une équipe.
 * 
 * @author Johann Rosain
 */
function ph_is_captain(PH\User $user) : bool {
    global $phdb;

    try {
        return $phdb->isCaptain($user->getPlayerId());
    }
    catch (Exception $_) {
        return false;
    } 
}