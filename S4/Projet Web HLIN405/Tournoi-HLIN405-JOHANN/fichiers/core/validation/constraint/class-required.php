<?php

namespace Core\Validation\Constraint;

/**
 * Contrainte : la valeur ne doit pas être vide.
 */
class Required extends IsString {
    
    /**
     * {@inheritdoc}.
     */
    protected function assertString(string $field_name, string $value) : Result {
        $result = new Result($field_name);

        if (strlen($value) < 1) {
            $result->setInvalid();
            $result->addMessage('Ce champ est requis.');
        }

        return $result;
    }
}