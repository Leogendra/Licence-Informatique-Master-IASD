<?php
    
/**
 * Renvoie tous les utilisateurs existants de la base de données sous forme
 * d'objets User.
 * 
 * @param  array $conds Conditions de recherche d'un joueur
 * @return array        Un tableau d'objet User
 * 
 * @author Benoît Huftier
 */
function ph_get_all_users(array $conds = array()) : array {
    global $phdb;
    $users_datas = $phdb->getUsers($conds);
    $users_datas = ph_add_roles_to_users_datas($users_datas);
    return ph_create_multiple_users_with_datas($users_datas);
}

/**
 * Récupère tous les joueurs de la base de données.
 * 
 * @return array Un tableau d'objet User, uniquement des joueurs.
 * 
 * @author Benoît Huftier
 */
function ph_get_all_players() : array {
    $players = array();

    foreach (ph_get_all_users() as $user_id => $user) {
        if ($user->isPlayer()) {
            $players[$user_id] = $user;
        }
    }

    return $players;
}

/**
 * Méthode utilitaire permettant d'ajouter les rôles à un tableau de données
 * de plusieurs utilisateurs.
 * 
 * Le paramètre doit être un tableau de tableau dont les clés sont les id
 * des utilisateurs.
 * 
 * @param array $users_datas Les données utilisateurs
 * @return array             $users_datas avec la clé roles ajoutée pour
 *                           chaque utilisateur.
 * 
 * @throws \Exception Si les rôles d'un utilisateur ne sont pas trouvés
 * 
 * @author Benoît Huftier.
 */
function ph_add_roles_to_users_datas(array $users_datas) : array {
    global $phdb;
    $roles = $phdb->getRolesForUsers(array_keys($users_datas));
    if (count($users_datas) !== count($roles)) {
        throw new \Exception("Impossible de récupérer les rôles d'un utilisateur.");
    }
    foreach ($roles as $user_id => $role) {
        $users_datas[$user_id]['roles'] = $role;
    }
    return $users_datas;
}

/**
 * Méthode utilitaire permettant de créer plusieurs utilisateurs en ayant un
 * tableau de données correct.
 * 
 * @param array $users_datas Toutes les données pour créer des utilisateurs
 * @return array             Les données formatées en objet User. Les clés sont
 *                           les identifiants des utilisateurs.
 * 
 * @author Benoît Huftier
 */
function ph_create_multiple_users_with_datas(array $users_datas) : array {
    $users = array();
    
    foreach ($users_datas as $user_datas) {
        $users[$user_datas['id']] = new PH\User($user_datas);
    }
    return $users;
}