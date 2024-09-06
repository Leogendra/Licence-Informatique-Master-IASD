<?php

namespace Core\Tags;

/**
 * @param  string $cross_origin anonymous|use-credentials.
 * @return bool                 Vrai si c'est une cross-origin valide.
 * 
 * @author Johann Rosain
 */
function is_cross_origin(string $cross_origin) : bool {
    return in_array($cross_origin, array('anonymous', 'use-credentials'), $strict = true);
}

/**
 * @param  string $policy Voir le lien ci-dessous.
 * @return bool           Vrai si c'est une referrer-policy valide.
 * 
 * @author Johann Rosain
 * @link   https://www.w3schools.com/tags/att_iframe_referrerpolicy.asp
 */
function is_referrer_policy(string $policy) : bool {
    $policiesArray = array(
        'no-referrer',
        'no-referrer-when-downgrade',
        'origin',
        'origin-when-cross-origin',
        'same-origin',
        'strict-origin',
        'strict-origin-when-cross-origin',
        'unsafe-url',
    );
    return in_array($policy, $policiesArray, $strict = true);
}

/**
 * @param  string $arg La chaîne à assainir.
 * @return string      La chaîne sans ses caractères interdits.
 * 
 * @author Johann Rosain
 */
function sanitize(string $arg) : string {
    // Suppression des backslashes
    $arg = htmlspecialchars($arg, ENT_QUOTES | ENT_HTML5);
    return str_replace('\\', '&#92;', $arg);
}