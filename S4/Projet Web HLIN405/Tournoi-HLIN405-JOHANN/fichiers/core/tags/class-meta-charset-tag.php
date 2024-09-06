<?php

namespace Core\Tags;

require_once __DIR__ . '/class-meta-tag.php';

/**
 * La classe MetaCharsetTag permet de générer une balise HTML <meta> de type charset :
 * <code>
 * <?php
 * require_once __DIR__ . '/core/autoloader.php;
 * 
 * $meta_charset_tag = new \Core\Tags\MetaCharsetTag('utf-8');
 * // Affiche : <meta charset='utf-8' />
 * $meta_charset_tag->render();
 * </code>
 */
final class MetaCharsetTag extends MetaTag {
    private string $charset;

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * Construit une balise HTML `<meta>` de type `charset`. 
     * 
     * @param  string $charset L'ensemble de caractères à utiliser sur la page.
     * 
     * @author Johann Rosain
     */
    public function __construct(string $charset) {
        if (empty($charset)) {
            throw new \Exception('Le charset d\'une balise MetaCharset ne doit pas être vide.');
        }
        $this->charset = $charset;
    }

    // ------------------------------------------------------------------------
    // Méthodes publiques.

    /**
     * {@inheritdoc}
     */
    final public function isEquivalent(ITag $oth) : bool {
        return $oth instanceof MetaCharsetTag && strtolower($oth->charset) === strtolower($this->charset);
    }

    // ------------------------------------------------------------------------
    // Méthode protégée.

    final protected function makeAttributes() : string {
        $string = "charset='" . sanitize($this->charset) . "' ";
        return $string;
    }
}