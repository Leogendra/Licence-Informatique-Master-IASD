<?php

namespace Core\Validation\Constraint;

/**
 * La classe Result est utilisée afin de partager le travail entre les assertions
 * de contraintes et le renvoi de message à l'utilisateur.
 * 
 * Ainsi, toute assertion renvoie une variable de type Result sans se compliquer
 * la tâche. Elle a juste besoin d'ajouter des messages et de dire si l'assertion
 * a été un succès ou non.
 * 
 * De base, un objet de type Result considère que l'assertion est réussie. pour
 * dire que l'assertion est un échec, il suffit d'appeler setInvalid().
 * 
 * Pour plus de clarté et de facilité d'utilisation, plusieurs messages peuvent
 * être ajouté à un seul et même résultat. La fonction addMessage() permet
 * d'ajouter un nouveau message.
 * 
 * Enfin, pour les assertions spéciales de type Collection, il est possible
 * d'ajouter des "sous résultats" c'est à dire, les résultats des éléments de la
 * collection. Je vous invite à lire la documentation des contraintes Collection
 * pour plus de détail.
 * 
 * @see IConstraint
 * @see Constraint
 * @see Collection
 */
class Result {
    private string $field_name;
    private bool $valid;
    private array $sub_results;
    private array $messages;

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * Un objet Result est construit en fonction d'un champ du formulaire. Ce sera
     * une réponse à la valeur envoyer par l'utilisateur dans ce champ précisément.
     * 
     * Le résultat est consiédré comme vrai si rien ne le contredit.
     * 
     * @param string $field_name Le résultat représentera la valeur de ce champ
     *                           envoyée par l'utilisateur.
     * 
     * @author Benoît Huftier
     */
    public function __construct(string $field_name) {
        $this->field_name = $field_name;
        $this->valid = true;
        $this->sub_results = array();
        $this->messages = array();
    }

    // ------------------------------------------------------------------------
    // Accesseurs en écriture.

    /**
     * Ajoute un sous-résultat, surtout utile pour les contraintes de type Collection.
     * Attention à ne pas faire des inclusions en boucles !!!
     * 
     * @param Result $result Le sous-résultat ajouté.
     * 
     * @author Benoît Huftier 
     */
    public function addResult(Result $result) : void {
        $this->sub_results[] = $result;
    }

    /**
     * Ajoute un message au résultat. Rien ne dit qu'il est obligatoire d'ajouter un
     * message en cas d'erreur et rien ne dit qu'il est interdit d'ajouter un message
     * en cas de succès, tout est possible !
     * 
     * Notez que vous pouvez ajouter plusieurs messages, le premier devrait être le
     * plus important.
     * 
     * @param string $message Le message à ajouter.
     * 
     * @author Benoît Huftier 
     */
    public function addMessage(string $message) : void {
        $this->messages[] = $message;
    }

    /**
     * Ajoute un message à un champ dont on donne le nom. Si le message a pu être
     * ajouté (c'est à dire que le nom donné existe dans les résultats), renvoie vrai.
     * Sinon, renvoie faux.
     * 
     * @param string $field_name Le nom du champ à qui il faut ajouter un message.
     * @param string $message    Le message à ajouter.
     * @param bool   $invalid    Est-ce que le champ doit devenir invalide ? Défaut à faux.
     * @return bool              Si le message a pu être ajouté.
     * 
     * @author Benoît Huftier
     */
    public function addMessageToField(string $field_name, string $message, bool $invalid = false) : bool {
        if ($this->field_name === $field_name) {
            $this->addMessage($message);
            if (true === $invalid) {
                $this->setInvalid();
            }
            return true;
        }

        foreach ($this->sub_results as $result) {
            if ($result->addMessageToField($field_name, $message, $invalid)) {
                return true;
            }
        }

        return false;
    }

    /**
     * De base, un résultat est considéré comme vrai, appeler cette méthode et il sera
     * alors considéré faux.
     * 
     * Il est inutile d'appeler cette méthode dans le cas de Collection car la validité
     * d'une Collection est en fonction de la validité de ses sous-résultats.
     * 
     * @author Benoît Huftier 
     */
    public function setInvalid() : void {
        $this->valid = false;
    }

    // ------------------------------------------------------------------------
    // Accesseurs en lecture.

    /**
     * @return array Les messages ajoutés au résultat.
     * 
     * @author Benoît Huftier
     */
    public function getMessages() : array {
        return $this->messages;
    }

    /**
     * Renvoie si oui ou non le résultat est valide.
     * Pour les contraintes de type Collection, il faut que tous les sous résultats
     * soient valides pour que le résultat soit valide.
     * 
     * @return bool La validité du résultat
     * 
     * @author Benoît Huftier
     */
    public function isValid() : bool {
        $valid = $this->valid;
        foreach ($this->sub_results as $result) { 
            $valid = $valid && $result->isValid();
        }
        return $valid;
    }

    /**
     * Transforme le résultat en tableau afin d'être renvoyé à l'utilisateur. Surtout
     * utilisé en cas d'erreur dans le formulaire.
     * 
     * Le tableau renvoyé contient 4 clés :
     * name     => Le nom du champ du formulaire.
     * success  => Si la validation est bonne ou pas.
     * messages => Un tableau contenant tous les messages ajoutés, cette clé n'existe
     *             que si au moins un message a été ajouté.
     * fields   => Dans le cas d'une contrainte de type Collection, tous les sous-
     *             résultats sont stockés dans ce tableau.
     * 
     * @return array Le tableau décrit juste au dessus
     * 
     * @author Benoît Huftier
     */
    public function toArray() : array {
        $array = array();

        $array['name'] = $this->field_name;
        $array['success'] = $this->isValid();

        if (!empty($this->messages)) {
            $array['messages'] = $this->messages;
        }

        foreach ($this->sub_results as $result) { 
            $array['fields'][$result->field_name] = $result->toArray();
        }

        return $array;
    }
}