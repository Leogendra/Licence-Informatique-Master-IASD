<?php 

if (false === defined('PH_REDIRECT_KEY')) {
    define('PH_REDIRECT_KEY', 'redirect');
    define('PH_SUCCESS_MESSAGES', 'ph_success_messages_key');
}

/**
 * Crée un fichier temporaire dans le dossier temporaire à la racine du projet.
 * Le fichier décrit par $file_array y ait transféré avec un nom unique.
 * 
 * @param  array $file_array Le tableau qui décrit le fichier.
 * @return string            Le chemin vers le fichier temporaire.
 * 
 * @author Johann Rosain
 */
function ph_create_tmp_file(array $file_array) : string {
    $dir_path = ABSPATH . '/tmp';
    if (false === is_dir($dir_path)) {
        mkdir($dir_path, 0755);
    }

    $filename = $dir_path . '/' . $file_array['name'];
    $path_info = pathinfo($filename);
    $i = 0;

    while (file_exists($filename)) {
        $filename = $path_info['dirname'] . '/' . $path_info['filename'] . "_$i" . $path_info['extension'];
    }

    move_uploaded_file($file_array['tmp_name'], $filename);

    return $filename;
}

/**
 * Sauvegarde un fichier dans le dossier uploads. Le fichier doit être un tableau
 * extrait de $_FILES.
 * 
 * Attention si le fichier existe déjà, il sera remplacé !
 * 
 * @param  array       $file        Le fichier à sauvegarder. 
 * @param  string      $upload_dir  Le sous dossier du dossier uploads, ou sauvegarder le fichier
 * @param  string      $upload_name Le nom du fichier. S'il n'y a pas de nom, ce sera un nom inexistant qui sera utilisé.
 * @return string|null              Le chemin depuis le dossier uploads. Null si le fichier contenanit une erreur.
 * 
 * @author Benoît Huftier
 */
function ph_save_upload_file(array $file, string $upload_dir, string $upload_name = '') : string|null {
    if (UPLOAD_ERR_OK === $file['error']) {
        $dir = ph_get_upload_path($upload_dir);

        // Création du dossier s'il n'existe pas
        if (!is_dir($dir)) {
            mkdir($dir, 0755, $recursive = true);
        }

        $ext = pathinfo($file['name'])['extension'];
        $filename = $upload_name . '.' . $ext;

        if (empty($upload_name)) {
            // Création d'un nom de fichier qui n'existe pas
            do {
                $filename = uniqid() . '.' . $ext;
            }
            while (file_exists($dir . '/' . $filename));
        }

        // On sauvegarde le fichier à l'emplacement voulu
        rename($file['tmp_name'], $dir . '/' . $filename);
        
        // On renvoie le chemin depuis le dossier des uploads.
        return $upload_dir . '/' . $filename;
    }
    
    return null;
}

/**
 * Essaies de mettre la page de redirection à $_GET['page']. Si ce n'est pas possible car la clé
 * n'existe pas, la met à la racine du site.
 * 
 * @author Johann Rosain
 */
function ph_set_redirect() : void {
	$_SESSION[PH_REDIRECT_KEY] = isset($_GET['page']) ? ($_GET['page'] === ROOT ? ROOT . '/' : $_GET['page']) : ROOT . '/';
}

/**
 * Enlève la page de redirection de la session.
 * 
 * @author Johann Rosain
 */
function ph_remove_redirect() : void {
    unset($_SESSION[PH_REDIRECT_KEY]);
}

/**
 * Retourne la page dans laquelle l'utilisateur doit être redirigé.
 * 
 * @return string La page vers laquelle l'utilisateur doit être redirigé. 
 * 
 * @author Johann Rosain
 */
function ph_get_redirect() : string {
    return $_SESSION[PH_REDIRECT_KEY];
}

/**
 * @param  array $messages Les messages de succès à afficher sur la prochaine page.
 * 
 * @author Johann Rosain 
 */
function ph_set_success_messages(array $messages) : void {
    $_SESSION[PH_SUCCESS_MESSAGES] = $messages;
}

/**
 * @return array Les messages de succès qui ont été set sur la page précédente.
 * 
 * @author Johann Rosain 
 */
function ph_get_success_messages() : array|null {
    $success_messages = null;
    if (isset($_SESSION[PH_SUCCESS_MESSAGES])) {
        $success_messages = $_SESSION[PH_SUCCESS_MESSAGES];
        unset($_SESSION[PH_SUCCESS_MESSAGES]); 
    }
    return $success_messages;
}

/**
 * Traite les requêtes de recherche dans le tableau $conds.
 * Renvoie un tableau qui permettra de faire une requête correcte a la base de données.
 * 
 * @param  array $conds                     Les conditions de recherche.
 * @param  array $accepted_keys             Les clés acceptées par la base de données.
 * @param  array $need_comparator_treatment Les valeurs avec comparateur.
 * @return array Tableau formatté comme attendu dans la bdd.
 * 
 * @author Johann Rosain
 */
function ph_process_search_data(array $conds, array $accepted_keys, array $need_comparator_treatment) : array {
    $result = array();

    foreach ($conds as $key => $value) {
        if (0 !== strlen($value)) {
            if (in_array($key, $accepted_keys, $strict = true)) {
                if (is_numeric($conds[$key])) {
                    $conds[$key] = intval($conds[$key]);
                }
                if (in_array($key, $need_comparator_treatment, $strict = true)) {
                    $result[$key] = ph_process_comparators($key);
                }
                else {
                    $result[$key] = $value;
                }
            }
        }
    }

    return $result;
}

/**
 * Renvoie un tableau avec 2 éléments : le comparateur, et la valeur à comparer.
 * 
 * @param  string $key Clé du tableau $_GET.
 * @return array       Le tableau a 2 éléments.
 * 
 * @author Johann Rosain
 */
function ph_process_comparators($key) : array {
    $comparators = array('<', '<=', '=', '>', '>=');

    if (false === in_array($_GET[$key . '-comparator'], $comparators, $strict = true)) {
        throw new Exception('Mauvais opérateur de comparaison.');
    }

    return array($_GET[$key . '-comparator'], $_GET[$key]);
}

/**
 * Flag pour que le json encode soit fait correctement.
 * 
 * @param  array  Le tableau a convertir en javascript
 * @return string Le tableau en json.
 * 
 * @author Johann Rosain
 */
function ph_get_json_encode(array $arr) : string {
    return addslashes(json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE));
}