<?php

namespace Core\Tags;

require_once __DIR__ . '/class-itag.php';
require_once __DIR__ . '/tags-utils.php';

/**
 * La classe LinkTag permet de construire et de générer une balise HTML <link>. 
 * Elle gère tous les attributs de cette balise, et vérifie si les arguments 
 * entrés sont valides (d'après le standard html). 
 * Les valeurs standards de chaque attribut peuvent être trouvées en suivant 
 * les liens fournis des accesseurs en écriture.
 */
class LinkTag implements ITag {
    private string $href = '';
    private string $cross_origin = '';
    private string $href_lang = '';
    private string $media = '';
    private string $referrer_policy = '';
    private string $rel = '';
    private string $sizes = '';
    private string $title = '';
    private string $type = '';
    private string $integrity = '';

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * @param  string    $href Lien du fichier à inclure.
     * @throws Exception       Si le lien est vide ou nul.
     * 
     * @author Johann Rosain
     */
    public function __construct(string $href) {
        if (empty($href)) {
            throw new \Exception("Un lien ne peut pas être nul ou vide.");
        }
        $this->href = $href;
    }

    // ------------------------------------------------------------------------
    // Méthodes publiques.

    /** 
     * {@inheritdoc} 
     */
    public function make() : string {
        $string = '<link ';
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
    public function isEquivalent(ITag $oth) : bool {
        return $oth instanceof LinkTag && $oth->href === $this->href;
    }

    // ------------------------------------------------------------------------
    // Accesseurs en écriture.

    /**
     * @param  string    $cross_origin Valeur : anonymous|use-credentials
     * @throws Exception               Si la valeur donnée est invalide.
     * 
     * @link   https://www.w3schools.com/tags/tag_link.asp
     * @author Johann Rosain
     */
    public function setCrossOrigin(string $cross_origin) : void {
        if(!is_cross_origin($cross_origin)) {
            throw new \Exception("Valeur de crossorigin invalide : $cross_origin");
        }
        $this->cross_origin = $cross_origin;
    }

    /**
     * Cette méthode permet de définir la langue du fichier à inclure. 
     * Il faut utiliser un format standard de 2 lettres. Par exemple : en ou fr.
     * 
     * @param  string $href_lang Langage du fichier à inclure.
     * 
     * @author Johann Rosain
     */
    public function setHrefLang(string $href_lang) : void {
        $this->href_lang = $href_lang;
    }

    /**
     * @param  string $media Le type de média qui va être inclus.
     * 
     * @link   https://www.w3schools.com/tags/att_link_media.asp
     * @author Johann Rosain
     */
    public function setMedia(string $media) : void {
        $this->media = $media;
    }

    /**
     * @param  string    $referrer_policy Voir le lien pour plus d'informations. 
     * @throws Exception                  Si la valeur donnée est invalide.
     * 
     * @link   https://www.w3schools.com/tags/att_iframe_referrerpolicy.asp
     * @author Johann Rosain
     */
    public function setReferrerPolicy(string $referrer_policy) : void {
        if(!is_referrer_policy($referrer_policy)) {
            throw new \Exception("Valeur de referrerpolicy invalide : $referrer_policy");
        }
        $this->referrer_policy = $referrer_policy;
    }

    /**
     * @param  string    $rel Voir le lien pour plus d'informations. 
     * @throws Exception      Si la valeur donnée est invalide.
     * 
     * @link   https://www.w3schools.com/tags/att_link_rel.asp
     * @author Johann Rosain
     */
    public function setRel(string $rel) : void {
        if(!$this->isRel($rel)) {
            throw new \Exception("Valeur de rel invalide : $rel");
        }
        $this->rel = $rel;
    }

    /**
     * Spécifie une taille pour l'objet à inclure. Ne marche que sur certains
     * types de médias, comme `icon`. 
     * Les valeurs peuvent être :
     *  - `any` pour laisser la taille par défaut
     *  - `Width`x`Height`
     * 
     * @param  string    $sizes Voir le lien pour plus d'informations. 
     * @throws Exception        Si la valeur donnée est invalide.
     * 
     * @link   https://www.w3schools.com/tags/att_link_sizes.asp
     * @author Johann Rosain
     */
    public function setSizes(string $sizes) : void {
        if(!$this->isValidSizes($sizes)) {
            throw new \Exception("Valeur de sizes invalid : $sizes");
        }
        $this->sizes = $sizes;
    }

    /**
     * @param  string $title Le titre alternatif de la balise.
     * 
     * @author Johann Rosain
     */
    public function setTitle(string $title) : void {
        $this->title = $title;
    }

    /**
     * @param  string $type Voir le lien pour plus d'informations. 
     * 
     * @link   https://www.iana.org/assignments/media-types/media-types.xhtml
     * @author Johann Rosain
     */
    public function setType(string $type) : void {
        $this->type = $type;
    }

    /**
     * Inclus principalement pour bootstrap.
     * 
     * @param  string $integrity Valeur hashée permettant de vérifier l'intégrité 
     *                           de l'appelant.
     * 
     * @author Johann Rosain
     */
    public function setIntegrity(string $integrity) : void {
        $this->integrity = $integrity;
    }

    // ------------------------------------------------------------------------
    // Méthodes privées.

    private function makeAttributes() : string {
        $string = "href='$this->href' ";
        $string .= $this->getAttributeHtml('crossorigin', $this->cross_origin);
        $string .= $this->getAttributeHtml('hreflang', $this->href_lang);
        $string .= $this->getAttributeHtml('media', $this->media);
        $string .= $this->getAttributeHtml('referrerpolicy', $this->referrer_policy);
        $string .= $this->getAttributeHtml('rel', $this->rel);
        $string .= $this->getAttributeHtml('sizes', $this->sizes);
        $string .= $this->getAttributeHtml('title', $this->title);
        $string .= $this->getAttributeHtml('type', $this->type);
        $string .= $this->getAttributeHtml('integrity', $this->integrity);
        return $string;
    }

    private function getAttributeHtml(string $attribute, string $value) : string {
        if (!empty($value)) {
            return "$attribute='" . sanitize($value) . "' ";
        }
        return '';
    }

    private function isValidSizes(string $sizes) : bool {
        $sizes = explode(' ', $sizes);
        foreach($sizes as $size) {
            if (!($size === 'any' || $this->isSize($size))) {
                return false;
            }
        }
        return true;
    }

    private function isSize(string $size) : bool {
        $result = str_contains($size, 'x');
        if ($result) {
            $dimensions = explode('x', $size);
            $result = count($dimensions) === 2 && intval($dimensions[0]) !== 0 && intval($dimensions[1]) !== 0;
        }
        return $result;
    }

    private function isRel(string $rel) : bool {
        $relsArray = array(
            'alternate',
            'author',
            'dns-prefetch',
            'help',
            'icon',
            'license',
            'next',
            'pingback',
            'preconnect',
            'prefetch',
            'preload',
            'prerender',
            'prev',
            'search',
            'stylesheet',
        );
        return in_array($rel, $relsArray, $strict = true);
    }
}