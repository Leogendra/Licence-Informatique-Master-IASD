<?php

namespace Core\Validation\Constraint;

/**
 * Contrainte : la valeur doit être une string
 */
class IsString extends Constraint {
    /**
     * {@inheritdoc}
     */
    final public function assert(string $field_name, mixed $value) : Result {
        if (!is_string($value)) {
            $result = new Result($field_name);
            $result->addMessage('String attendue, ' . gettype($value) . ' fourni');
            $result->setInvalid();
            return $result;
        }

        return $this->assertString($field_name, $value);
    }

    /**
     * Cette fonction est strictement identique à la fonction assert à la différence
     * qu'elle oblige la valeur à être de type string. Cela permet d'éviter de
     * constamment revérifier si la valeur est une chaîne de caractères ou non.
     * 
     * Cette méthode doit être surchargée à la place de la méthode assert dans toute
     * contrainte qui hérite de IsString.
     * 
     * @param string $field_name Le nom du champ qui est vérifié.
     * @param string $value      La valeur à vérifier.
     * 
     * @return Result Le résultat de l'assertion
     * 
     * @author Benoît Huftier
     */
    protected function assertString(string $field_name, string $value) : Result {
        return new Result($field_name);
    }
}