<?php

namespace Core\Renderer;

/**
 * La classe FormRenderer est une classe de rendu automatique de formulaire qui
 * a été validé par les classes de validation de ce module core.
 * Elle permet d'ajouter automatiquement les champs erronés et les champs valides.
 * Pour ce faire, par défaut, cette classe doit être surchargée. Il suffit simplement 
 * d'override les méthodes suivantes :
 *   - makeValid
 *   - makeInvalid
 * Cette classe est simple d'utilisation. Il suffit de faire 3 choses :
 *   - Mettre votre formulaire dans un fichier séparé. Il sera include.
 *   - Instancier une variable de type FormRenderer, et lui passer le résultat
 *     de la validation.
 *   - Render cette variable.
 * Exemple (cet exemple ne marche pas, car FormRenderer est une classe abstraite, il faut d'abord
 * faire une classe fille. Cependant, le code devrait être le même pour render) :
 * <code>
 * <?php
 * $form_renderer = new Core\Renderer\FormRenderer('includes/my-form.inc', $_SESSION['form_result']);
 * ?>
 * <!-- Ma page html -->
 * <!-- ... --> 
 * <div>
 *     <h2>Mon formulaire !</h2>
 *     <?php $form_renderer->render(); ?>
 * </div>
 * <!-- ... -->
 * </code> 
 */
abstract class FormRenderer {
    private string $path;
    private array $results;
    private array $values;
    private array $global_errors;
    private \DOMDocument $form;
    private null|string $additions = null;
    private int $counter;

    // --------------------------------------------------------------------------------------------
    // Constructeur.

    /**
     * Construit un FormRenderer et met à jour le formulaire selon les résultats de la validation.
     * Si un champ contient une ou plusieurs erreurs, elles seront affichées.
     * Si un champ est valide, la valeur précédente et un petit sigle valide sera affiché. 
     * 
     * @param  string $path                    Chemin pour inclure le formulaire.
     * @param  null|array  $results_and_values Résultats de la validation du formulaire, valeur 
     *                                         des champs du formulaire et erreurs globales du
     *                                         formulaire.
     * 
     * @author Johann Rosain
     * @throws Exception     Si le chemin vers le formulaire est vide.
     * @throws Exception     Si le tableau donné ne correspond pas à celui attendu.
     */
    public function __construct(string $path, null|array $results_and_values) {
        if (true === empty($path)) {
            throw new \Exception('Erreur : le chemin pour inclure un formulaire ne doit pas être vide.');
        }

        $this->path = $path;
        $this->counter = 0;

        $this->form = $this->getInitialForm();
        if (!is_null($results_and_values)) {
            if (!isset($results_and_values['results']) || !isset($results_and_values['values'])) {
                throw new \Exception('Erreur : le tableau donné ne correspond pas à celui attendu pour rendre le formulaire.');
            }
            $this->results = $results_and_values['results'];
            $this->values = $results_and_values['values'];
            $this->global_errors = $results_and_values['global_errors'];

            $this->form = $this->process();
        }
    }

    // --------------------------------------------------------------------------------------------
    // Méthode publique.

    /**
     * Rend le formulaire.
     * 
     * @author Johann Rosain.
     */
    public function render() : void {
        echo $this->form->saveHTML();
        if (false === is_null($this->additions)) {
            echo '<script>' . $this->additions . '</script>';
        }
    }

    // --------------------------------------------------------------------------------------------
    // Méthodes protégées.

    /**
     * Rend le noeud $node valide, car la valeur $value entrée a été approuvée par les règles de 
     * validation. Utilisez setNodeValue pour mettre à jour la valeur du noeud.
     * 
     * @param  DOMNode    &$node     La node a rendre valide. C'est une référence.
     * @param  DOMDocument $doc      Le document parent, qui gère le formulaire.
     * @param  array       $messages Les messages pour dire à l'utilisateur qu'il a vraiment bien 
     *                               fait son travail.
     * @param  string      $value    La valeur que doit prendre la node.
     * 
     * @author Johann Rosain
     * @see    setNodeValue
     */
    abstract protected function makeValid(\DOMNode &$node, \DOMDocument $doc, array $messages, string $value) : void;

    /**
     * Rend le noeud $node invalide, car la valeur entrée n'a pas été approuvée par les règles de
     * validation. Ainsi, toutes les erreurs sur la valeur entrée se trouve dans le tableau de 
     * $messages. De plus, le document parent est fourni si besoin est pour créer de nouveaux
     * éléments.
     * 
     * @param  DOMNode     &$node    Le noeud à rendre invalide.
     * @param  DOMDocument $doc      Le document parent, qui gère le formulaire.
     * @param  array       $messages Les différents problèmes sur la valeur précédemment entrée.
     * @param  string      $value    La valeur précédente du noeud.
     * 
     * @author Johann Rosain
     */
    abstract protected function makeInvalid(\DOMNode &$node, \DOMDocument $doc, array $messages, string $value) : void;

    /**
     * Ajoute une section au dessus du formulaire qui sont les « erreurs globales », c'est à dire 
     * que ce ne sont pas les erreurs sur les champs, mais plutôt certaines combinaisons qui ne 
     * sont pas passées. Par exemple, pour connecter un utilisateur, on vérifie si sa combinaison
     * email / mot de passe correspond à celle stockée dans la base de données. Si elle ne correspond
     * pas, il faut bien pouvoir le dire. C'est ici qu'est générée cette section. 
     * 
     * @param  DOMDocument $doc    Le document qui gère le formulaire.
     * @param  array       $errors Les erreurs faite. Peut être vide.
     * 
     * @author Johann Rosain
     */
    abstract protected function makeErrors(\DOMDocument $doc, array $errors) : void;

    /**
     * Set la valeur d'un noeud. L'implémentation de ce bout de code change selon le type d'input.
     * En effet, la plupart des input ont juste besoin de set l'attribut value a la valeur donnée,
     * mais 4 balises n'utilisent pas cette méthode pour set la valeur. Ainsi, cette méthode le 
     * fait pour tous les types de balise input. 
     * 
     * @param  DOMNode &$node  Le noeud à mettre à jour la valeur.
     * @param  string   $value La valeur à mettre au noeud.
     * 
     * @author Johann Rosain
     */
    final protected function setNodeValue(\DOMNode &$node, string $value) : void {
        if (true === empty($value)) {
            return;
        }

        if ('input' === $node->tagName) {
            $set_instantly = array(
                'color',
                'date',
                'datetime-local',
                'email',
                'month',
                'number',
                'password',
                'range',
                'tel',
                'text',
                'time',
                'url',
                'week',
                '',
            );
            $need_further_treatments = array(
                'checkbox' => array($this, 'setCheckboxChecked'),
                'radio' => array($this, 'setRadioChecked'),
                'file' => array($this, 'setFileName'),
            );
    
            $type = $this->getTypeFromNode($node);
    
            if (in_array($type, $set_instantly, $strict = true)) {
                $node->setAttribute('value', $value);
            }
            if (array_key_exists($type, $need_further_treatments)) {
                $need_further_treatments[$type]($node, $value);
            }
        }
        // Cela peut être seulement un SELECT. On test quand même au cas où. 
        else {
            if ('select' !== $node->tagName) {
                return;
            }

            foreach ($node->childNodes as $child_node) {
                if ($child_node instanceof \DOMElement && 'option' === $child_node->tagName) {
                    if ($child_node->attributes->getNamedItem('value')?->value === $value) {
                        $child_node->setAttribute('selected', true);
                    }
                }
            }
        }

    }

    // --------------------------------------------------------------------------------------------
    // Méthodes privées.

    /**
     * Ajoute les messages d'erreur au DOM de l'objet à rendre.
     */
    private function process() : \DOMDocument {
        $form_dom = $this->form;

        $valid_fields = $this->getValidFields();
        $invalid_fields = $this->getInvalidFields();
        $unvalidated_fields = $this->getUnvalidatedFields(array_merge($valid_fields, $invalid_fields));

        $this->setValidFields($form_dom, $valid_fields);
        $this->setInvalidFields($form_dom, $invalid_fields);
        $this->setUnvalidatedFieldsValue($form_dom, $unvalidated_fields);
        $this->makeErrors($form_dom, $this->global_errors);

        return $form_dom;
    }

    /**
     * Parcours le tableau de résultat pour récupérer les champs valides. 
     * De plus, si un champ est valide, on récupère sa valeur.
     */
    private function getValidFields() : array {
        return $this->getFields($success = true);
    }

    /**
     * Parcours le tableau de résultat pour récupérer les champs invalides.
     * De plus, si un champ est invalide, récupère tous les messages.
     */
    private function getInvalidFields() : array {
        return $this->getFields($success = false);
    }

    private function getFields(bool $success) : array {
        $fields = array();
        foreach ($this->results['fields'] as $field => $result) {
            if ($success === $result['success']) {
                $name = $result['name'];
                $fields[$name]['messages'] = array_key_exists('messages', $result) ? $result['messages'] : array();
                $fields[$name]['value'] = $this->values[$name];
            }
        }
        return $fields;
    }

    /**
     * Tous les champs qui sont dans les valeurs mais pas dans celles validées.
     */
    private function getUnvalidatedFields(array $validated_fields) : array {
        $unvalidated_fields = array_diff_key($this->values, $validated_fields);
        return $unvalidated_fields;
    }

    /**
     * Marque les champs valides sur le DOM. La méthode prend une référence, et ne retourne rien.
     */
    private function setValidFields(\DOMDocument &$form_dom, array $valid_fields) : void {
        $this->setFields($form_dom, $valid_fields, array($this, 'makeValid'));
    }

    /**
     * Marque les champs invalides sur le DOM. La méthode prend une référence, et ne retourne rien.
     */
    private function setInvalidFields(\DOMDocument &$form_dom, array $invalid_fields) : void {
        $this->setFields($form_dom, $invalid_fields, array($this, 'makeInvalid'));
    }

    private function setFields(\DOMDocument &$form_dom, array $fields, callable $fun) : void {
        foreach ($this->getFormFields($form_dom) as $node) {
            $node_name = $this->getNameFromNode($node);
            if (array_key_exists($node_name, $fields)) {
                $fun($node, $form_dom, $fields[$node_name]['messages'], $fields[$node_name]['value']);
                unset($fields[$node_name]);
            }
        }
    }

    /**
     * Met toutes les nodes avec le nom donné à leur valeur.
     */
    private function setUnvalidatedFieldsValue(\DOMDocument &$form_dom, array $unvalidated_fields) : void {
        foreach ($this->getFormFields($form_dom) as $node) {
            $name = $this->getNameFromNode($node);
            if (array_key_exists($name, $unvalidated_fields)) {
                $this->setNodeValue($node, $unvalidated_fields[$name]);
                unset($unvalidated_fields[$name]);
            }
        }
    }

    private function getFormFields(\DOMDocument $form_dom) : array {
        $dnlToArray = function(\DOMNodeList $list) : array {
            $array = array();
            foreach ($list as $node) {
                $array[] = $node;
            } 
            return $array;
        };
        return array_merge($dnlToArray($form_dom->getElementsByTagName('input')), $dnlToArray($form_dom->getElementsByTagName('select')));
    }

    private function setCheckboxChecked(\DOMNode &$node, string $value) : void {
        $node->setAttribute("checked", true);
    }

    private function setRadioChecked(\DOMNode $_, string $value) : void {
        // On ne se sert pas de la node donnée.
        foreach ($this->form->getElementsByTagName('input') as $node) {
            if ('radio' === $this->getTypeFromNode($node) && $value === $this->getValueFromNode($node)) {
                $node->setAttribute('checked', true);
            }
        }
    }

    private function setFileName(\DOMNode $file_node, string $value) : void {
        $id = $this->getAttributeFromNode($file_node, 'id');
        $script = $this->generateUpdateFileScript($id, $value);
        if (true === is_null($this->additions)) {
            $this->additions = $script;
        }
        else {
            $this->additions .= $script;
        }
    }

    /**
     * Récupère le formulaire initial sans changement.
     */
    private function getInitialForm() : \DOMDocument {
        ob_start();
        include $this->path;
        $form_dom = new \DOMDocument();
        @$form_dom->loadHTML('<?xml encoding="utf-8" ?>' . ob_get_clean());
        return $form_dom;
    }

    private function getNameFromNode(\DOMNode $node) : string {
        return $this->getAttributeFromNode($node, 'name');
    }

    private function getTypeFromNode(\DOMNode $node) : string {
        return $this->getAttributeFromNode($node, 'type');
    }

    private function getValueFromNode(\DOMNode $node) : string {
        return $this->getAttributeFromNode($node, 'value');
    }

    private function getAttributeFromNode(\DOMNode $node, string $attr) : string {
        $name = $node->attributes->getNamedItem($attr)?->value;
        return is_null($name) ? '' : $name;
    }

    private function generateUpdateFileScript(string $id, string $name) : string {
        $var = 'newFile' . strval($this->counter);

        $script = '';
        if (0 === $this->counter) {
            $script = "
            function FileListItems (file) {
                let b = new ClipboardEvent('').clipboardData || new DataTransfer();
                b.items.add(file);
                return b.files;
            }";
        }

        $script .= "
        let $var = new File([''], '$name', {type: ''});
        
        document.getElementById('$id').files = new FileListItems($var);
        ";

        $this->counter += 1;

        return $script;
    }
}