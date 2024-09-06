<?php

namespace Core\Validation\Constraint;

/**
 * Contrainte : la valeur doit être une chaîne de caractères et sa longueur
 * peut être spécifiée.
 * 
 * min => La chaîne de caractères doit faire une certaine taille au minimum.
 * max => La chaîne de caractères doit faire une certaine taille au maximum.
 * equals => La chaîne de caractères doit fair exactement la taille fourni.
 */
class Length extends IsString {
    private array $constraints;

    /**
     * Les contraintes sont des noms de méthodes privées accompagnées de la
     * valeur des arguments à fournir.
     * 
     * Les arguments peuvent changer en fonction de la méthode.
     * 
     * @param array $constraints Le tableaux contenant les contraintes
     * 
     * @author Benoît Huftier
     */
    public function __construct(array $constraints) {
        $this->constraints = $constraints;
    }

    /**
     * {@inheritdoc}.
     */
    protected function assertString(string $field_name, string $value) : Result {
        $result = new Result($field_name);

        foreach ($this->constraints as $method => $arg) {
            if (method_exists($this, $method)) {
                $this->$method($value, $arg, $result);
            }
        }

        return $result;
    }

    // ------------------------------------------------------------------------
    // Méthodes de validation privées.

    private function min(string $value, int $min, Result &$result) : void {
        if (strlen($value) < $min) {
            $result->setInvalid();
            $result->addMessage("Pas assez de caractères (minimum $min)");
        }
    }

    private function max(string $value, int $max, Result &$result) : void {
        if (strlen($value) > $max) {
            $result->setInvalid();
            $result->addMessage("Trop de caractères (maximum $max)");
        }
    }

    private function equals(string $value, int $equals, Result &$result) : void {
        if (strlen($value) !== $equals) {
            $result->setInvalid();
            $result->addMessage("Il doit y avoir exactement $equals caractères");
        }
    }
}