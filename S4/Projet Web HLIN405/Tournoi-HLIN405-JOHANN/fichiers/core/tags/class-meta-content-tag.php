<?php

namespace Core\Tags;

require_once __DIR__ . '/class-meta-tag.php';

/**
 * La classe MetaContentTag permet de générer une balise HTML <meta> de type name:content :
 * <code>
 * <?php
 * require_once __DIR__ . '/core/autoloader.php;
 * 
 * $meta_content_tag = new \Core\Tags\MetaContentTag('description', 'Ma belle page d\'accueil !');
 * // Affiche : <meta name='description' content='Ma belle page d\'acceuil !' />
 * $meta_content_tag->render();
 * </code>
 */
final class MetaContentTag extends MetaTag {
    private string $name;
    private string $content;

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * Construit une balise HTML `<meta>` de type `name:content`. 
     * 
     * @param  string $name    Un des noms donné sur le lien ci-dessous.
     * @param  string $content Valeur liée au nom.
     * 
     * @author Johann Rosain
     * @link   https://www.w3schools.com/tags/tag_meta.asp
     */
    public function __construct(string $name, string $content) {
        if (empty($name) || empty($content)) {
            throw new \Exception('Le nom et le contenu d\'une balise MetaContent ne doivent pas être vides.');
        }
        $this->name = $name;
        $this->content = $content;
    }

    // ------------------------------------------------------------------------
    // Méthodes publiques.

    /**
     * {@inheritdoc}
     */
    final public function isEquivalent(ITag $oth) : bool {
        return $oth instanceof MetaContentTag && $this->hasSameContent($oth);
    }

    // ------------------------------------------------------------------------
    // Méthode protégée.

    final protected function makeAttributes() : string {
        $string = "name='" . sanitize($this->name) . "' ";
        $string .= "content='" . sanitize($this->content) . "' ";
        return $string;
    }

    // ------------------------------------------------------------------------
    // Méthodes privées.

    private function hasSameContent(MetaContentTag $meta_tag) : bool {
        // L'attribut `name` est comme une clé de dictionnaire, s'il y a 
        // plusieurs fois la même dans une page, il y a un problème.
        return strtolower($meta_tag->name) === strtolower($this->name);
    }
}