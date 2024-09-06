<?php

namespace Core\Validation\Constraint;

/**
 * Une contrainte multiple permet d'ajouter plusieurs contraintes à un seul champ de
 * formulaire.
 * 
 * Par exemple, si une adresse email doit faire au moins 10 caractères de long, on peut
 * imaginer le code suivant :
 * 
 * <code>
 * $constraints = new Constraint\Collection(array(
 *     'mail' => new Constraint\Multiple(array(
 *         new Constraint\Length(array('min' => 10)),
 *         new Constraint\Email()
 *     )),
 *     // ...
 * ));
 * </code>
 * 
 * Il est bon de noter que les contraintes de type collection ne sont pas autorisées car
 * elles ont un comportement différent des contraintes classique.
 */
class Multiple extends Constraint {
    private array $constraints;

    /**
     * @param array $constraint Un tableau contenant toutes les contraintes pour le champ.
     * 
     * @throws InvalidArgumentException Si l'une des contraintes n'est pas un objet
     *                                  héritant de Constraint.
     *  
     * @author Benoît Huftier
     */
    public function __construct(array $constraints) {
        foreach ($constraints as $constraint) {
            if (!$constraint instanceof Constraint) {
                throw new \InvalidArgumentException('Les contraintes d\'une contrainte multiple
                doivent être de type Constraint ' . gettype($constraint) . ' fourni.');
            }
        }

        $this->constraints = $constraints;
    }

    /**
     * Tous les messages des résultats des différentes contraintes sont stockés dans les
     * messages du résultat renvoyé.
     * Cette méthode, contrairement à une collection, renvoie un unique résultat sans
     * sous-résultat.
     *  
     * {@inheritdoc}
     */
    public function assert(string $field_name, mixed $value) : Result {
        $result = new Result($field_name);

        foreach ($this->constraints as $constraint) {
            $constraint_result = $constraint->assert($field_name, $value);
            if (!$constraint_result->isValid()) {
                $result->setInvalid();
            }
            foreach ($constraint_result->getMessages() as $message) {
                $result->addMessage($message);
            }
        }

        return $result;
    }
}