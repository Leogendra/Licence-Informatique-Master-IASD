<?php

namespace Core\Tags;

require_once __DIR__ . '/class-meta-tag.php';

/**
 * La classe MetaHttpEquivTag permet de générer une balise HTML <meta> de type http-equiv:content :
 * <code>
 * <?php
 * require_once __DIR__ . '/core/autoloader.php;
 * 
 * $meta_http_equiv_tag = new \Core\Tags\MetaHTTPEquivTag('refresh', '30');
 * // Affiche : <meta http-equiv='refresh' content='30' />
 * $meta_http_equiv_tag->render();
 * </code>
 */
final class MetaHTTPEquivTag extends MetaTag {
    private string $http_equiv;
    private string $content;

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * Construit une balise HTML `<meta>` de type `http-equiv:content`. 
     * 
     * @param  string $http_equiv Un des noms donné sur le lien ci-dessous.
     * @param  string $content    Valeur liée au nom.
     * 
     * @author Johann Rosain
     * @link   https://www.w3schools.com/tags/att_meta_http_equiv.asp
     */
    public function __construct(string $http_equiv, string $content = '') {
        if (empty($http_equiv)) {
            throw new \Exception('Le http_equiv d\'une balise MetaHTTPEquiv ne doit pas être vide.');
        }
        $this->http_equiv = $http_equiv;
        $this->content = $content;
    }

    // ------------------------------------------------------------------------
    // Méthodes publiques.

    /**
     * {@inheritdoc}
     */
    final public function isEquivalent(ITag $oth) : bool {
        return $oth instanceof MetaHttpEquivTag && $this->hasSameHttpEquiv($oth);
    }

    // ------------------------------------------------------------------------
    // Méthode protégée.

    final protected function makeAttributes() : string {
        $string = "http-equiv='" . sanitize($this->http_equiv) . "' ";
        if (!empty($this->content)) {
            $string .= "content='" . sanitize($this->content) . "' ";
        }
        return $string;
    }

    // ------------------------------------------------------------------------
    // Méthodes privées.

    private function hasSameHttpEquiv(MetaHttpEquivTag $meta_tag) : bool {
        return strtolower($meta_tag->http_equiv) === strtolower($this->http_equiv);
    }
}