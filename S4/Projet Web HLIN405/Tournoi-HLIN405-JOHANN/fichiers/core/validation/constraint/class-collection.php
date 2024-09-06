<?php

namespace Core\Validation\Constraint;

/**
 * Une collection est une contrainte un peu spéciale.
 * Elle vérifie que la variable envoyée est un tableau de donnée et non une valeur
 * directement.
 * 
 * Il faut voir les Collection comme des tableaux de contraintes.
 * 
 * Elle prend un tableau de contraintes en paramètre. Toutes ces contraintes seront
 * vérifiées lors de la validation.
 * Chaque clé du tableaux de contraintes correspond aux noms des champs du formulaire
 * à valider.
 * 
 * Pour construire un FormValidator, il faut lui donner en paramètre une Collection.
 * 
 * Exemple :
 * <code>
 * $constraints = new Constraint\Collection(array(
 *     'name' => new Constraint\Collection(
 *         'first_name' => new Constraint\Length(array('min' => 3)),
 *         'last_name' => new Constraint\Length(array('min' => 3))
 *     ),
 *     'birth_date' => new Constraint\Date(array(
 *         'format' => 'Y-m-d',
 *         'max' => 'now'
 *     )),
 *     'description' => new Constraint\String()
 * ));
 * 
 * // Cette contrainte est prête à valider un tableau de données du type suivant :
 * 
 * $data = array(
 *     'name' => array(
 *         'first_name' => 'Jean', // String devant avoir au moins 3 caractères
 *         'last_name' => 'Dupont' // String devant avoir au moins 3 caractères
 *     ),
 *     'birth_date' => '1990-05-23', // Date de naissance au format YYYY-mm-dd devant
 *                                   // être inférieure à la date actuelle
 *     'desciption' => '' // Une string qui doit exister, mais sans contraintes
 *                        // particulières
 * );
 * </code>
 * 
 * @see FormValidator
 */
class Collection implements IConstraint {
    private array $constraints;
    private array $not_mandatory;

    /**
     * Les clés du tableaux sont les noms des champs
     * Les valeurs sont les contraintes pour les champs.
     * 
     * @param array $constraint     Un tableau contenant tous les champs et leurs
     *                              contraintes.
     * @param array $not_mandatory  Un tableau contenant les champs qui ne sont pas
     *                              obligatoires.
     * 
     * @throws InvalidArgumentException Si l'une des contraintes n'est pas un objet
     *                                  héritant de IConstraint.
     *  
     * @author Benoît Huftier
     */
    public function __construct(array $constraints, array $not_mandatory = array()) {
        foreach ($constraints as $constraint) {
            if (!($constraint instanceof IConstraint)) {
                throw new \InvalidArgumentException('Les contraintes d\'une collection
                doivent être de type IConstraint, ' . gettype($constraint) . ' fourni.');
            }
        }

        $this->constraints = $constraints;
        $this->not_mandatory = $not_mandatory;
    }

    /**
     * Le résultat renvoyé contient un sous résultat pour chaque champ de la collection.
     * Aucun message n'est directement ajouté au résultat, il faut regarder les messages
     * des sous résultats pour avoir les détails.
     * 
     * {@inheritdoc}
     */
    public function assert(string $field_name, mixed $value) : Result {
        $result = new Result($field_name);

        if (!is_array($value)) {
            $result->addMessage('Array attendu, ' . gettype($value) . ' fourni');
            $result->setInvalid();
            return $result;
        }

        foreach ($this->constraints as $field_name => $constraint) {
            $mandatory = !in_array($field_name, $this->not_mandatory, true);
            
            if (!array_key_exists($field_name, $value)) {
                if (true === $mandatory) {
                    $bad_result = new Result($field_name);
                    $bad_result->setInvalid();
                    $bad_result->addMessage('Ce champ n\'a pas été soumis');
                    $result->addResult($bad_result);
                }
            }
            else if ($mandatory || !$constraint->isNull($value[$field_name])) {
                $result->addResult($constraint->assert($field_name, $value[$field_name]));
            }
        }

        return $result;
    }

    /**
     * Une collection est nulle si rien n'a été envoyé, ça peut être un tableau ou
     * une chaîne vide selon comment est implémenté le formulaire.
     * 
     * {@inheritdoc}
     */
    public function isNull(mixed $value) : bool {
        return array() === $value || '' === $value;
    }
}