<?php

if (false === defined('PH_FORM_SESSION_KEY')) {
    define('PH_FORM_SESSION_KEY', 'form_results');
    define('PH_FORM_FILES_KEY', 'form_files');
}

/**
 * Met les données reçues de la validation d'un formulaire ainsi que les valeurs des champs du  
 * formulaire dans un tableau que s'attend à recevoir FormRenderer, et les enregistrent dans
 * le champ de session défini.
 * 
 * @param  array $results Résultat de la validation du formulaire.
 * 
 * @author Johann Rosain
 */
function ph_save_form_data(array $results, array $errors) : void {
    $conform_array = array(
        'results'       => $results,
        'values'        => ph_get_posted_values(),
        'global_errors' => $errors,
    );
    $_SESSION[PH_FORM_SESSION_KEY] = $conform_array;
}

/**
 * Récupère les valeurs dans le tableau $_POST et $_FILES. Récupère seulement le nom de chaque
 * fichier envoyé dans $_FILES.
 * 
 * @return array Les valeurs de $_POST et $_FILES 
 * 
 * @author Johann Rosain
 */
function ph_get_posted_values() : array {
    $result_array = $_POST;

    foreach ($_FILES as $name => $file_array) {
        $result_array[$name] = $file_array['name'];
    }

    return $result_array;
}

/**
 * Récupère le résultat de la validation du dernier formulaire si il existe.
 * De plus, enlève cette variable du tableau de session.
 * 
 * @return array|null Un tableau des résultats de la submission du formulaire si ils existent.
 *                    Sinon, renvoie null.
 * 
 * @author Johann Rosain.
 */
function ph_get_validation_result() : array|null {
    global $validation_result;

    if (isset($validation_result)) {
        return $validation_result;
    }

    $validation_result = null;
    if (isset($_SESSION[PH_FORM_SESSION_KEY])) {
        $validation_result = $_SESSION[PH_FORM_SESSION_KEY];
        unset($_SESSION[PH_FORM_SESSION_KEY]);
    }
    return $validation_result;
}

/**
 * Redirige l'utilisateur avec l'erreur 403 s'il essaie d'accéder à la page de validation
 * sans passer par un formulaire, i.e. : s'il entre l'url dans son navigateur.
 * 
 * @param  array $expected_fields Les champs qui doivent être présents dans $_POST.
 * 
 * @author Johann Rosain
 */
function ph_redirect_if_not_form_submission(array $expected_fields) : void {
    foreach ($expected_fields as $field) {
        if (false === (array_key_exists($field, $_POST))) {
            ph_error_redirect(403);
        }
    }
}

/**
 * Crée un tableau pour les fichiers de session sauvegardés. 
 * 
 * @author Johann Rosain
 */
function ph_create_session_files_array() : void {
    if (!isset($_SESSION[PH_FORM_FILES_KEY])) {
        $_SESSION[PH_FORM_FILES_KEY] = array();
    }
}

/**
 * Tous les fichiers envoyés sont sauvegardés dans le répertoire `tmp_files` afin de les garder si 
 * l'utilisateur a des erreurs dans son formulaire (pour qu'il ne perde pas de temps à re-upload)
 * le fichier.
 * Lorsque les fichiers ont bien été récupérés, et que les fichiers temporaires ne sont plus requis,
 * il suffit d'appeler ph_clear_tmp_files()
 * 
 * @author Johann Rosain
 */
function ph_save_current_files() : void {
    ph_create_session_files_array();

    foreach ($_FILES as $name => $file_array) {
        if ($file_array['error'] === UPLOAD_ERR_OK && $file_array['size'] > 0) {
            if (isset($_SESSION[PH_FORM_FILES_KEY][$name])) {
                $previous_name = $_SESSION[PH_FORM_FILES_KEY][$name]['tmp_name'];
                if (file_exists($previous_name)) {
                    unlink($previous_name);
                }
            }

            $filename = ph_create_tmp_file($file_array);

            $_SESSION[PH_FORM_FILES_KEY][$name] = $file_array;
            $_SESSION[PH_FORM_FILES_KEY][$name]['tmp_name'] = $filename;
            $_FILES[$name] = $_SESSION[PH_FORM_FILES_KEY][$name];
        }
    }
}

/**
 * Restore les fichiers sauvegardés durant les précédentes instances de la submission de ce formulaire.
 * Modifie directement le tableau $_FILES.
 * 
 * @author Johann Rosain
 */
function ph_restore_saved_files() : void {
    if (!isset($_SESSION[PH_FORM_FILES_KEY]) || empty($_SESSION[PH_FORM_FILES_KEY])) {
        return;
    }

    $_FILES = $_SESSION[PH_FORM_FILES_KEY];
}

/**
 * Enlève toutes les valeurs du tableau de sauvegarde des fichiers, et supprime les images temporaires 
 * associées.
 * 
 * @author Johann Rosain
 */
function ph_clear_tmp_files() : void {
    foreach ($_SESSION[PH_FORM_FILES_KEY] as $name => $file_array) {
        unlink($file_array['tmp_name']);
    }

    unset($_SESSION[PH_FORM_FILES_KEY]);
}

/**
 * Récupère l'id et le nom de tous les gestionnaires de tournois.
 * 
 * @return array Les gestionnaires de tournoi avec un tableau id => nickname.
 * 
 * @author Johann Rosain
 */
function ph_get_all_managers() : array {
    global $phdb;

    return $phdb->getManagers();
}

/**
 * Récupère l'id et le nom de toutes les villes enregistrées dans la base de données.
 * 
 * @return array Les villes avec un tableau id => name.
 * 
 * @author Johann Rosain
 */
function ph_get_all_cities() : array {
    global $phdb;

    return $phdb->getCities();
}

/**
 * Récupère l'id et le numéro de tous les codes postaux enregistrées dans la base de données.
 * 
 * @return array Les codes postaux avec un tableau id => code.
 * 
 * @author Johann Rosain
 */
function ph_get_all_zips() : array {
    global $phdb;

    return $phdb->getZips();
}

/**
 * Récupère l'id et le numéro de tous les types de tournois de la base de données.
 * 
 * @return array Les types de tournois avec un tableau id => code.
 * 
 * @author Johann Rosain
 */
function ph_get_tournament_types() : array {
    global $phdb;

    $records = $phdb->getAllTournamentTypes();
    $tournaments = array();

    foreach ($records as $record) {
        $tournaments[$record[0]] = $record[1];
    }

    return $tournaments;
}

/**
 * Vérifie si l'id envoyée correspond bien à un tournoi « classique ».
 * N'est utile que pour la version 1 du site ; en effet, nous ne gérerons que les tournois
 * dans cette version et non les autres types.
 * 
 * @param  int $id L'id du type.
 * @return bool    Vrai si l'id correspond à celui des tournois dans data.sql
 * 
 * @author Johann Rosain 
 */
function ph_is_tournament(int $id) : bool {
    return 1 === $id;
}

/**
 * Filtre les messages des champs à valider avec de multiples contraintes comme si c'était un 
 * connecteur logique « et » (c'est à dire, évaluation paresseuse, donc seul le premier message
 * est sauvegardé).
 * 
 * @param  array $messages Les messages d'un résultat à déchiffrer.
 * @param  array $fields   Les champs qui doivent être traités comme des « et »
 * @return array           Un tableau avec les messages traités. 
 * 
 * @author Johann Rosain
 */
function ph_filter_and(array $messages, array $fields) : array {
    $res = array();

    foreach ($messages as $message) {
        if (false === $message['success'] && in_array($message['name'], $fields, $strict = true)) {
            $message['messages'] = array($message['messages'][0]);
        }
        $res[] = $message;
    }

    return $res;
}

/**
 * Redirige l'utilisateur en dehors de la page s'il n'a pas les permissions requises.
 * 
 * @param  $permissions Les rôles qui peuvent accéder à la page
 * 
 * @author Johann Rosain
 */
function ph_redirect_if_not(int $permissions) : void {
    if (false === ph_get_user()->getPermissions()->hasAny($permissions)) {
        ph_error_redirect(403);
    }
}