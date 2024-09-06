<?php 

/**
 * Redirige vers une page d'erreur.
 * Attention, le code après l'appel de cette fonction ne sera pas exécuté !
 * 
 * @param int $error Le code d'erreur de la page à afficher. Si le code d'erreur
 *                   n'est pas géré par le site, ce sera 500.
 * 
 * @author Benoît Huftier
 */
function ph_error_redirect(int $error = 500) : void {
    $error_page_array = array(
        403 => 'errors/forbidden.php',
        404 => 'errors/not-found.php',
        500 => 'errors/internal-server-error.php',
        // ...
    );
    
    if (!array_key_exists($error, $error_page_array)) {
        $error = 500;
    }

    $link = ph_get_route_link($error_page_array[$error]);
    if ($_SERVER['REQUEST_URI'] !== $link) {
        http_response_code($error);
        header('Location: ' . $link);
        exit;
    }
}

/**
 * Affichage d'une erreur critique.
 * Cette fonction permet d'arrêter toute exécution du code et d'afficher en dernière
 * ligne un message d'erreur.
 * 
 * Si le mode développement est set à false (comme sur le site live), seule une erreur
 * 500 sera levée, sans afficher l'erreur.
 * 
 * @param string $error        L'erreur à afficher en mode développement
 * @param bool   $enable_debug Affichage de tous les appels de fonctions qui ont amené à l'erreur
 * 
 * @author Benoît Huftier
 */
function ph_error_display(string $error, bool $enable_debug = false) : void {
    if (!defined('DEVELOPMENT') || false === DEVELOPMENT) {
        ph_error_redirect(500);
    }
    
    echo $error;
    
    if (true === $enable_debug) {
        $debug = debug_backtrace(0);
        array_shift($debug);
        var_dump($debug);
    }
    
    exit;
}