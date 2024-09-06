<?php

namespace Core\Tags;

require_once __DIR__ . '/class-itag.php';
require_once __DIR__ . '/tags-utils.php';

/**
 * La classe ScriptTag permet de construire et de générer une balise HTML <script>. 
 * Elle gère tous les attributs de cette balise, et vérifie si les arguments 
 * entrés sont valides (grâce au standard). 
 * Les valeurs standards de chaque attribut peuvent être trouvées en suivant 
 * les liens fournis des accesseurs en écriture.
 */
class ScriptTag implements ITag {
    private string $src = '';
    private bool $async = false;
    private string $cross_origin = '';
    private bool $defer = false;
    private string $integrity = '';
    private bool $no_module = false;
    private string $referrer_policy = '';
    private string $type = '';

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * @param  string    $src  Lien du script à inclure.
     * @throws \Exception      Si le lien est vide ou nul.
     * 
     * @author Johann Rosain
     */
    public function __construct(string $src) {
        if (empty($src)) {
            throw new \Exception("La source d'un script ne peut pas être nul ou vide.");
        }
        $this->src = $src;
    }

    // ------------------------------------------------------------------------
    // Méthodes publiques.

    /** 
     * {@inheritdoc} 
     */
    public function make() : string {
        $string = '<script ';
        $string .= $this->makeAttributes();
        $string .= '></script>';
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
        return $oth instanceof ScriptTag && $oth->src === $this->src;
    }

    // ------------------------------------------------------------------------
    // Accesseurs en écriture.

    /**
     * Ne marche que sur les scripts externes à la page.
     * Charge le script en mode asynchrone dès qu'il est disponible, charge en
     * même temps que la page.
     * 
     * @param  bool $on Activer le mode asynchrone.
     * 
     * @link   https://www.w3schools.com/tags/att_script_async.asp
     * @author Johann Rosain
     */
    public function setAsyncMode(bool $on) : void {
        $this->async = $on;
    }

    /**
     * @param  string    $cross_origin anonymous|use-credentials
     * @throws Exception               Si la valeur donnée est invalide.
     * 
     * @author Johann Rosain
     */
    public function setCrossOrigin(string $cross_origin) : void {
        if(!is_cross_origin($cross_origin)) {
            throw new \Exception("Valeur de crossorigin invalide : $cross_origin");
        }
        $this->cross_origin = $cross_origin;
    }

    /**
     * Ne marche que sur les scripts externes à la page.
     * Reporte le chargement du script à la fin du chargement de la page.
     * 
     * @param  bool $on Activer le mode `report`. 
     * 
     * @link   https://www.w3schools.com/tags/att_script_defer.asp
     * @author Johann Rosain
     */
    public function setDeferMode(bool $on) : void {
        $this->defer = $on;
    }

    /**
     * Désactive le script sur les navigateurs utilisant ES5 et inférieur.
     * 
     * @param  bool $on Active le mode nomodule.
     * 
     * @link   https://www.w3schools.com/js/js_es6.asp
     * @author Johann Rosain
     */
    public function setNoModuleMode(bool $on) : void {
        $this->no_module = $on;
    }

    /**
     * @param  string    $referrer_policy Voir le lien pour plus d'informations. 
     * @throws \Exception                 Si la valeur donnée est invalide.
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
     * @param  string $type Type de média à lier.
     * 
     * @link   https://www.iana.org/assignments/media-types/media-types.xhtml
     * @author Johann Rosain
     */
    public function setType(string $type) {
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
    public function setIntegrity(string $integrity) {
        $this->integrity = $integrity;
    }

    // ------------------------------------------------------------------------
    // Méthodes privées.

    private function makeAttributes() : string {
        $string = "src='$this->src'";
        $string .= $this->getAttributeHtml('async', $this->async);
        $string .= $this->getAttributeHtml('crossorigin', $this->cross_origin);
        $string .= $this->getAttributeHtml('defer', $this->defer);
        $string .= $this->getAttributeHtml('integrity', $this->integrity);
        $string .= $this->getAttributeHtml('nomodule', $this->no_module);
        $string .= $this->getAttributeHtml('referrerpolicy', $this->referrer_policy);
        $string .= $this->getAttributeHtml('type', $this->type);
        return $string;
    }

    private function getAttributeHtml(string $attribute, bool|string $value) : string {
        $string = '';
        if (is_bool($value) && $value) {
            $string = " $attribute";
        }
        else if (!empty($value)) {
            $string = " $attribute='" . sanitize($value) . "'";
        }
        return $string;
    }
}