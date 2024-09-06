<?php

/**
 * Vérifie s'il existe ou non un utilisateur avec l'email donné. 
 * 
 * @param  string $email L'email à vérifier
 * @return bool          True si l'utilisateur n'existe pas, False s'il existe.
 * 
 * @author Johann Rosain
 */
function ph_user_does_not_exists(string $email) : bool {
    global $phdb;

    $user = $phdb->getUserFromEmail($email);

    return 0 === count($user);
}

/**
 * Enregistre l'utilisateur dans la base de données.
 * 
 * @param  string $email           L'email du nouvel utilisateur.
 * @param  string $name            Le nom affiché du nouvel utilisateur.
 * @param  string $password        Le mot de passe chiffré avec password_hash() en BCRYPT du nouvel utilisateur.
 * @param  string $profile_picture Chemin de la photo de profil. Nul s'il n'y en a pas.
 * 
 * @author Johann Rosain
 */
function ph_register_user(string $email, string $name, string $password, string|null $profile_picture) : void {
    global $phdb;

    $phdb->registerUser($email, $name, $password, $profile_picture);
}