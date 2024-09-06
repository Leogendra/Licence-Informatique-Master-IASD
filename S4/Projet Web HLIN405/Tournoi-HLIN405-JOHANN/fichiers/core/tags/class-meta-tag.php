<?php

namespace Core\Tags;

require_once __DIR__ . '/class-itag.php';
require_once __DIR__ . '/tags-utils.php';

/**
 * La classe MetaTag gère les méthodes communes à toutes les balises `<meta>`. 
 * En effet, pour chaque balise meta différente, c'est seulement l'implémentation 
 * des attributs qui change, le reste est similaire. 
 */
abstract class MetaTag implements ITag {

    // ------------------------------------------------------------------------
    // Méthodes publiques.

    /**
     * {@inheritdoc}
     */
    public function make() : string {
        $string = '<meta ';
        $string .= $this->makeAttributes();
        $string .= '/>';
        return $string;
    }

    /**
     * {@inheritdoc}
     */
    public function render() : void {
        echo $this->make();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function isEquivalent(ITag $oth) : bool;

    // ------------------------------------------------------------------------
    // Méthode protégée.

    /**
     * @return string Retourne les attributs bien formés de l'objet. 
     * 
     * @author Johann Rosain
     */
    abstract protected function makeAttributes() : string;
}