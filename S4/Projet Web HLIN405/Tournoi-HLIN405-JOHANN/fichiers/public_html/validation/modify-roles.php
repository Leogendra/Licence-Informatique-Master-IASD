<?php 

require_once __DIR__ . '/../site-header.php';

$result = function(bool $success, string $message) {
    echo json_encode(array(
        'success' => $success,
        'message' => $message,
    ));
    exit;
};

if (!isset($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
    $result(false, 'L\'utilisateur ne peut pas être récupéré.');
}

try {
    $user = PH\User::createFromId($_POST['user_id']);
}
catch (Exception $e) {
    $result(false, 'L\'utilisateur n\'a pas pu être récupéré de la base de données.');
}

if (!ph_get_user()->getPermissions()->hasFlag(Role::Administrator)) {
    $result(false, 'Vous n\'avez pas les droits pour faire ça');
}

$to_add = array();
$to_del = array();

$test_role = function(string $k, int $role) use(&$to_add, &$to_del, $user) : void {
    if ('true' === $_POST[$k]) {
        if (!$user->getPermissions()->hasFlag($role)) {
            $to_add[] = Role::toString($role);
        }
    }
    else {
        if ($user->getPermissions()->hasFlag($role)) {
            $to_del[] = Role::toString($role);
        }
    }
};

$test_role('role_admin', Role::Administrator);
$test_role('role_manager', Role::Manager);
$test_role('role_player', Role::Player);

// Si l'utilisateur a un seul rôle, et que l'administrateur veut le supprimer, fait une erreur.
// Cependant, s'il lui ajoute un autre rôle, c'est bon, car l'utilisateur aura au moins un rôle.
if (count($to_del) === count(Role::toArray($user->getPermissions())) && empty($to_add)) {
    $result(false, 'L\'utilisateur ' . $user->getName() . ' doit avoir au moins un rôle.');
}

if ($user->getId() === ph_get_user()->getId() && in_array(Role::toString(Role::Administrator), $to_del, $strict = true)) {
    $result(false, 'Vous ne pouvez pas enlever votre propre rang administrateur.');
}

global $phdb;

try {
    $phdb->modifyRoles($user->getId(), $to_add, $to_del);
}
catch (Exception $e) {
    $result(false, $e->getMessage());
}

$result(true, 'Les rôles de ' . $user->getName() . ' ont été mis à jour avec succès.');