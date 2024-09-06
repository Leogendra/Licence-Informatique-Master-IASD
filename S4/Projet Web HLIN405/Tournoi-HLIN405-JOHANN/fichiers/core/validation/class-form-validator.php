<?php

namespace Core\Validation;

use Core\Validation\Constraint\Collection;
use Core\Validation\Constraint\Result;

/**
 * La classe FormValidator permet de valider la réponse d'un formulaire soumise par
 * un utilisateur.
 * 
 * Il est très simple de lancer la validation d'un formulaire, dans le fichier de
 * vérification, il faut simplement écrire quelque chose comme ceci :
 * <code>
 * $constraints = new Core\Validation\Constraint\Collection(array(
 *     // Vos contraintes ici !
 * ));
 * 
 * // On suppose ici que les valeurs des champs du formulaire sont envoyées en POST
 * $validator = new Core\Validation\FormValidator($_POST, $constraints);
 *
 * if ($validator->isValid()) {
 *     // Succès !
 * }
 * else {
 *     // Échec de la validation, cette méthode permet d'envoyer des debugs à
 *     // l'utilisateur
 *     $array = $validator->formMessages();
 * }
 * </code>
 */
class FormValidator {
    private Result $result;

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * Valide directement le formulaire.
     * Le résultat est stocké dans l'attribut $result
     * 
     * @param array                 $fields      Les valeurs du formulaires soumises
     *                                           par l'utilisateur
     * @param Constraint\Collection $constraints Toutes les vérifications
     *                                           à faire en fonction des champs.
     * 
     * @author Benoît Huftier
     */
    public function __construct(array $fields, Collection $constraints) {
        $this->result = $constraints->assert('', $fields);
    }

    // ------------------------------------------------------------------------
    // Accesseur en lecture.

    /**
     * @return bool vrai si tous les champs du formulaire sont vérifiés et valides.
     * 
     * @author Benoît Huftier
     */
    public function isValid() : bool {
        return $this->result->isValid();
    }

    /**
     * Renvoie un tableau contenant tous les messages pour chaque champ du formulaire.
     * Ce tableau est utile en cas de soumission non valide du formulaire afin de
     * renvoyer des messages permettant à l'utilisateur de voir pourquoi il a des
     * erreurs.
     * 
     * @return array Tous les messages à afficher
     * 
     * @author Benoît Huftier
     */
    public function formMessages() : array {
        $array = $this->result->toArray();
        unset($array['name']);
        return $array;
    }

    /**
     * Ajoute un message à un champ sans corrompre sa validité.
     * 
     * @param string $field_name Le nom du champ où il faut ajouter un message.
     * @param string $message    Le message à ajouter.
     * 
     * @author Benoît Huftier
     */
    public function addMessageToField(string $field_name, string $message) : void {
        $this->result->addMessageToField($field_name, $message);
    }

    /**
     * Ajoute un message d'erreur à un champ en le mettant invalide.
     * 
     * @param string $field_name Le nom du champ où il faut ajouter un message.
     * @param string $message    Le message à ajouter.
     * 
     * @throws \Exception        Si le champ n'a pas été trouvé, car il est censé y
     *                           avoir une erreur dans un champ inexistant. 
     * 
     * @author Benoît Huftier
     */
    public function addErrorToField(string $field_name, string $message) : void {
        if (false === $this->result->addMessageToField($field_name, $message, $invalid = true)) {
            throw new \Exception("Le champ $field_name n'existe pas dans le formulaire");
        }
    }
}