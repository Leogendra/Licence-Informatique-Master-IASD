<?php

namespace Core;

/**
 * La classe Permissions permet de gérer de multiples permissions d'accès, gérées avec des flags, 
 * des énumérations avec des puissances de 2, afin de pouvoir faire des opérations sur les bits, 
 * pour facilement affecter plusieurs permissions à un objet :
 * <code>
 * <?php
 * require_once __DIR__ . '/core/autoloader.php';
 * // Mon enum
 * abstract class DaysOfWeek {
 *     const Mon = 1;   // <=> 2^0
 *     const Tue = 2;   // <=> 1 << 1 <=> 2^1
 *     const Wed = 4;   // <=> 1 << 2 <=> 2^2
 *     const Thu = 8;   // <=> 1 << 3 <=> 2^3
 *     const Fri = 16;  // <=> 1 << 4 <=> 2^4
 *     const Sat = 32;  // <=> 1 << 5 <=> 2^5
 *     const Sun = 64;  // <=> 1 << 6 <=> 2^6
 * }
 * 
 * $today = /* un des jours de la semaine *\/;
 * 
 * $evenDays = new \Core\Permissions(DaysOfWeek::Tue | DaysOfWeek::Thu | DaysOfWeek::Sat);
 * if ($evenDays->hasFlag($today)) {
 *     // Si la variable $today est Mardi ou Jeudi ou Samedi
 * }
 * </code>
 */
class Permissions {
    private int $flags;

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * @param  int $flags Les drapeaux de permission.
     * 
     * @author Johann Rosain
     */
    public function __construct(int $flags) {
        $this->flags = $flags;
    }

    // ------------------------------------------------------------------------
    // Méthodes publiques.

    /**
     * Vérifie si la permission contient le flag donné.
     * 
     * @param  int  $flag Le flag à vérifier.
     * @return bool       Vrai si le flag est dans les permissions. Faux sinon.
     * 
     * @author Johann Rosain
     */
    public function hasFlag(int $flag) : bool {
        return (($this->flags & $flag) == $flag);
    }

    /**
     * Vérifie si les permissions ont un ou plusieurs flags d'autres permissions.
     * 
     * @param  int  $flags Les flags à vérifier.
     * @return bool        Vrai si un flag ou plus sont présents dans les permissions
     *                     de l'objet.
     * 
     * @author Johann Rosain 
     */
    public function hasAny(int $flags) : bool {
        return (($this->flags & $flags) !== 0);
    }

    /**
     * @author Johann Rosain
     */
    public function getFlags() : int {
        return $this->flags;
    }
}