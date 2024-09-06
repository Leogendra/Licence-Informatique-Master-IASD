<?php

namespace Core\Validation\Constraint;

/**
 * Contrainte : la valeur doit être un nombre, int ou float
 */
class IsNumber extends Constraint {
    /**
     * {@inheritdoc}
     */
    final public function assert(string $field_name, mixed $value) : Result {
        if (!is_numeric($value)) {
            $result = new Result($field_name);
            $result->addMessage('Valeur numérique attendue, ' . gettype($value) . ' non numérique fourni.');
            $result->setInvalid();
            return $result;
        }

        return $this->assertNumeric($field_name, $value);
    }

    /**
     * Cette fonction est strictement identique à la fonction assert à la différence
     * qu'elle oblige la valeur à être d'un type numérique. Cela permet d'éviter de
     * constamment revérifier si la valeur est numérique ou non.
     * 
     * Cette méthode doit être surchargée à la place de la méthode assert dans toute
     * contrainte qui hérite de IsNumeric.
     * 
     * @param string    $field_name Le nom du champ qui est vérifié.
     * @param int|float $value      La valeur à vérifier.
     * 
     * @return Result Le résultat de l'assertion
     * 
     * @author Benoît Huftier
     */
    protected function assertNumeric(string $field_name, int|float $value) : Result {
        return new Result($field_name);
    }
}