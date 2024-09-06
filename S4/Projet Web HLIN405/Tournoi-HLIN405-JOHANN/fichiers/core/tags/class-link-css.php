<?php

namespace Core\Tags;

require_once __DIR__ . '/class-link-tag.php';

/**
 * Paramètre une balise Link avec les options de base pour inclure une page CSS.
 */
class LinkCSS extends LinkTag {

    /**
     * @param  string $href Lien vers la page CSS à inclure.
     * 
     * @author Johann Rosain
     */
    public function __construct(string $href) {
        parent::__construct($href);

        $this->setRel('stylesheet');
        $this->setType('text/css');
    }
}