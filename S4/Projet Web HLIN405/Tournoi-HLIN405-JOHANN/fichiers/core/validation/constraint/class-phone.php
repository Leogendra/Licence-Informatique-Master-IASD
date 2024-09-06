<?php

namespace Core\Validation\Constraint;

/**
 * Contrainte : la valeur doit être un numéro de téléphone valide
 */
class Phone extends Regex {

    public function __construct() {
        parent::__construct('/^(\+\d{2} \d|0\d)([ -.]{0,1}\d{2}){4}$/', 'Numéro de téléphone invalide');
    }
}