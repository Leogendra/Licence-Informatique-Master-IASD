<?php

namespace Core\Tags;

/**
 * Interface commune à toutes classes qui gèrent les balises HTML. 
 */
interface ITag {
    /**
     * Génère la balise HTML associée à l'objet.
     * 
     * @return string La chaîne de caractères correspondant à la balise HTML.
     * 
     * @author Johann Rosain
     */
    public function make() : string;

    /**
     * Génère et affiche la balise HTML associée à l'objet.
     * 
     * @author Johann Rosain
     */
    public function render() : void;

    /**
     * Cette méthode est implémentée car l'opérateur == ne peut pas être 
     * surchargé.
     * 
     * @param  ITag $oth Autre instance d'un objet ITag.
     * @return bool      Vrai si $oth est équivalent à l'instance courante.
     * 
     * @author Johann Rosain
     */
    public function isEquivalent(ITag $oth) : bool;
}