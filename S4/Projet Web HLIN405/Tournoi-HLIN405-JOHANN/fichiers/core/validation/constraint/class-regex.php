<?php

namespace Core\Validation\Constraint;

/**
 * Contrainte : la valeur doit valider un regex spécifique
 */
class Regex extends IsString {
    private string $regex;
    private string $message;

    /**
     * @param string $regex   La regex qui sera validée
     * @param string $message Le message d'erreur à envoyer si la regex n'est pas respectée
     * 
     * @throws \InvalidArgumentException Si la regex n'est pas valide
     * 
     * @author Benoît Huftier
     */
    public function __construct(string $regex, string $message = '') {
        if (false === @preg_match($regex, null)) {
            throw new \InvalidArgumentException("$regex n'est pas une regex valide !");
        }

        $this->message = $message;
        $this->regex = $regex;
    }

    /**
     * {@inheritdoc}
     */
    protected function assertString(string $field_name, string $value) : Result {
        $result = new Result($field_name);

        // Ici, on fait une simple comparaison car si la fonction renvoie false
        // (c'est à dire une erreur), le résultat sera quand même mauvais
        if (!preg_match($this->regex, $value)) {
            $result->setInvalid();
            $result->addMessage($this->message);
        }

        return $result;
    }
}