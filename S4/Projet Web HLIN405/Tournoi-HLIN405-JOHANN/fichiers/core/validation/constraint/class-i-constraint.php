<?php

namespace Core\Validation\Constraint;

/**
 * Interface commune à toutes les contraintes
 * 
 * Le but d'une contrainte est le suivant : une valeur a été envoyée par un
 * utilisateur dans un fomulaire, il faut vérifier cette valeur. C'est ce que
 * fait la fonction assert().
 */
interface IConstraint {
    /**
     * Renvoie le résultat du test de la contrainte
     * 
     * @param string $field_name Le nom du champ qui est vérifié.
     * @param mixed  $value      La valeur à vérifier.
     * 
     * @return Result Le résultat de l'assertion
     * 
     * @author Benoît Huftier
     */
    public function assert(string $field_name, mixed $value) : Result;

    /**
     * Renvoie si la valeur est considérée comme nulle si le champ est "non requis"
     * Les champs à valider peuvent être non requis, dans ce cas, cette méthode
     * sera appelée pour vérifier que la valeur donnée ne doit pas être vérifiée.
     * 
     * Si cette méthode rnenvoie vraie, c'est que la valeur n'a pas été set par
     * l'utilisateur et qu'il n'y a donc aucune vérification à faire. Le résultat
     * de la validation sera vrai.
     * 
     * @param mixed $value La valeur à vérifier.
     * 
     * @return bool Est-ce que la valeur est considérée comme nulle
     * 
     * @author Benoît Huftier
     */
    public function isNull(mixed $value) : bool;
}