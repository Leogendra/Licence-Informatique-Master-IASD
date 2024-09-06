<?php

namespace Core\Validation\Constraint;

/**
 * Cette classe existe simplement pour faire la distinction entre
 * Contrainte de type Collection et les autres.
 * 
 * Aucune contrainte ne doit implémnter de IConstraint à l'exception
 * de Constraint et Collection, elles doivent toutes hériter de Field.
 */
abstract class Constraint implements IConstraint {
    /**
     * {@inheritdoc}
     */
    abstract public function assert(string $field_name, mixed $value) : Result;

    /**
     * Une contrainte simple est nulle à partir du moment où sa valeur
     * est envoyée en post avec rien dedans. Une chaîne vide.
     * 
     * {@inheritdoc}
     */
    public function isNull(mixed $value) : bool {
        return '' === $value;
    }
}