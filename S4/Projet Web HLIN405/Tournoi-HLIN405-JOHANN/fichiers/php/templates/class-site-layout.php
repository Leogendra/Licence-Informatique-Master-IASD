<?php

namespace PH\Templates;

/**
 * Cette classe permet de configurer le schéma de construction d'une page du site,
 * en particulier le header et le footer de toutes les pages à générer.
 * De plus, elle permet de configurer sur quelle page l'utilisateur va être 
 * redirigé en cas de soucis de droits.
 */
abstract class SiteLayout extends \Core\Renderer\PageRenderer {
    private string $header;
    private string $footer;

    // ------------------------------------------------------------------------
    // Méthodes de configuration du header & footer. 

    protected function configurateHeader() : void {
        $this->setHeader(ABSPATH . '/includes/default-header.php');
        // Configuration des sections restreintes ICI
    }

    protected function configurateFooter() : void {
        $this->setFooter(ABSPATH . '/includes/default-footer.php');
        // Configuration des sections restreintes ICI
    }

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * @author Johann Rosain
     */
    public function __construct() {
        parent::__construct();

        $this->configurateHeader();
        $this->configurateFooter();
        $this->setActionOnForbidden(function() {
            ph_error_redirect(403);
        });
    }

    // ------------------------------------------------------------------------
    // Méthode publique.

    /**
     * {@inheritdoc}
     */
    public function forbidAccessIfNotPermitted() : void {
        $this->testPermissionsForAndForbidIfNeeded($this->getCurrentUserPermissions());
    }

    // ------------------------------------------------------------------------
    // Méthodes protégées.

    /**
     * {@inheritdoc}
     */
    final protected function make() : \DOMDocument {
        $doc = $this->generatePage();
        $this->removeNotAllowedNodes($doc, $this->getCurrentUserPermissions());
        return $doc;
    }

    /**
     * @return  Core\Permissions Retourne les permissions de l'utilisateur actuel. 
     * 
     * @author Johann Rosain
     */
    final protected function getCurrentUserPermissions() : \Core\Permissions {
        return ph_get_user()->getPermissions();
    }

    /**
     * On surcharge la méthode de génération afin d'afficher le header et le footer du site.
     * 
     * @author Benoît Huftier
     * @see    Core\PageRenderer::generateBody(), getHeader(), getFooter()
     */
    protected function generateBody() : void {
        $this->generateHtml('<body>', 1);
        include $this->getHeader();
        include $this->getBody();
        include $this->getFooter();
        $this->generateScripts();
        $this->generateHtml('</body>', 1);
    }

    /**
     * @return string Chemin vers le header de la page à générer.
     * 
     * @author Benoît Huftier
     */
    final protected function getHeader() : string {
        return $this->header;
    }

    /**
     * @return string Chemin vers le footer de la page à générer.
     * 
     * @author Benoît Huftier
     */
    final protected function getFooter() : string {
        return $this->footer;
    }

    /**
     * @param  string $header Chemin vers le header de la page à générer.
     * 
     * @author Benoît Huftier
     */
    final protected function setHeader(string $header) : void {
        $this->header = $header;
    }

    /**
     * @param  string $footer Chemin vers le footer de la page à générer.
     * 
     * @author Benoît Huftier
     */
    final protected function setFooter(string $footer) : void { 
        $this->footer = $footer;
    }
}