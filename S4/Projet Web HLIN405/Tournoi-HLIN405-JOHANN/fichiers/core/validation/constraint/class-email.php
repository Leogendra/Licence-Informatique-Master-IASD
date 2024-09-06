<?php

namespace Core\Validation\Constraint;

/**
 * Contrainte : la valeur doit être un email valide
 */
class Email extends Regex {

    /**
     * Un email doit valider une regex spécifique
     * 
     * @author Benoît Huftier
     */
    public function __construct() {
        parent::__construct('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', 'Le mail fourni n\'est pas un mail valide.');
    }

    /**
     * {@inheritdoc}
     */
    protected function assertString(string $field_name, string $value) : Result {
        $result = parent::assertString($field_name, $value);
        
        if (false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $result->setInvalid();
        }

        return $result;
    }
}