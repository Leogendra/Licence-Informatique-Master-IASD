<?php

namespace Core\Validation\Constraint;

/**
 * Contrainte : la valeur doit être un des choix donné.
 * Les choix peuvent être d'autres contraintes.
 * 
 * Par exemple :
 * 
 * <code>
 * $choice = new Constraint\Choice(array(
 *     'no email',
 *     new Constraint\Email()
 * ));
 * 
 * // Est valide
 * $choice->assert('field', 'no email');
 * $choice->assert('field', 'toto@toto.com');
 * 
 * // Est invalide
 * $choice->assert('field', 'pas d\'email');
 * </code>
 */
class Choice extends Constraint {
    private array $choices;
    
    /**
     * Les choix peuvent être des contraintes, dans le cas, on validera
     * le choix en vérifiant que la contrainte est validé.
     * 
     * @param array Tous les choix possibles
     * 
     * @author Benoît Huftier
     */
    public function __construct(array $choices) {
        $this->choices = $choices;
    }

    /**
     * {@inheritdoc}
     */
    public function assert(string $field_name, mixed $value) : Result {
        $result = new Result($field_name);
        $valid = false;
        
        foreach ($this->choices as $choice) {
            if ($choice instanceof Constraint) {
                $r = $choice->assert($field_name, $value);
                if ($r->isValid()) {
                    $valid = true;
                    break;
                }
            }
            else {
                if ($value === $choice) {
                    $valid = true;
                    break;
                }
            }
        }

        if (!$valid) {
            $result->addMessage('Cette valeur ne fait pas partie des choix possibles');
            $result->setInvalid();
        }

        return $result;
    }
}