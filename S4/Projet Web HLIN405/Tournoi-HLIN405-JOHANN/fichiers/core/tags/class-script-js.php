<?php

namespace Core\Tags;

require_once __DIR__ . '/class-script-tag.php';

/**
 * Paramètre une balise Script avec les options de base pour inclure un script js.
 */
class ScriptJS extends ScriptTag {

    /**
     * @param  string $src Lien vers le script js à inclure.
     * 
     * @author Johann Rosain
     */
    public function __construct(string $src) {
        parent::__construct($src);

        $this->setType('application/javascript');
    }
}