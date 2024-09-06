<?php

namespace PH\Templates;

/**
 * La classe BootstrapPage permet de créer une page qui est configurée pour 
 * une utilisation de bootstrap. Cela inclut les scripts js ainsi que la feuille
 * css nécessaires.
 * Cette classe hérite de SiteLayout, elle fait donc un rendu paramétré comme
 * sur sa classe mère :
 *     - header du site
 *     - corps de texte
 *     - footer du site
 */
final class BootstrapPage extends SiteLayout {

    // ------------------------------------------------------------------------
    // Méthodes de configuration du header & footer.

    protected function configurateHeader() : void {
        $this->setHeader(ABSPATH . '/includes/site-header.php');
        // Configuration des sections restreintes ICI
        $this->addRestrictedSections(array(
            \Role::Administrator => array('admin-only'),
            \Role::Manager => array('manager-only'),
            \Role::Player => array('player-only'),
            \Role::Administrator | \Role::Player => array('player-admin-only'),
            \Role::Public => array('public'),
            \Role::Connected => array('disconnection')
        ));
    }

    protected function configurateFooter() : void {
        $this->setFooter(ABSPATH . '/includes/site-footer.php');
        // Configuration des sections restreintes ICI
    }

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * @author Johann Rosain
     */
    public function __construct() {
        parent::__construct();

        $this->addBootstrapStylesheet();
        $this->addStylesheets(array(ph_create_css_object('main.css')));

        $this->addBootstrapScripts();
        $this->addScripts(array(ph_create_js_object('main.js')));
    }

    // ------------------------------------------------------------------------
    // Méthodes protégées.

    /**
     * {@inheritdoc}
     */
    protected function generateBody() : void {
        $this->generateHtml('<body>', 1);
        $this->generateHtml('<div id="main">', 2);
        include $this->getHeader();
        $this->generateHtml('<div class="container">', 3);
        $this->createSuccessAlerts();
        include $this->getBody();
        $this->generateHtml('</div>', 3);
        include $this->getFooter();
        $this->generateHtml('</div>', 2);
        $this->generateScripts();
        $this->generateHtml('</body>', 1);
    }

    /**
     * {@inheritdoc}
     */
    protected function generateScripts() : void {
        $this->generateHtml('<script>');
        $this->generateHtml('window.localStorage.siteRoot = window.location.origin + "' . ROOT . '";', 1);
        $this->generateHtml('</script>');
        parent::generateScripts();
    }

    // ------------------------------------------------------------------------
    // Méthodes privées.

    private function addBootstrapStylesheet() : void {
        $bootstrap_ss = new \Core\Tags\LinkCSS('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css');
        $bootstrap_ss->setIntegrity('sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6');
        $bootstrap_ss->setCrossOrigin('anonymous');
        $this->addStylesheets(array(
            $bootstrap_ss,
        ));
    }

    private function addBootstrapScripts() : void {
        $bootstrap_js = new \Core\Tags\ScriptJS('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js');
        $bootstrap_js->setIntegrity('sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf');
        $bootstrap_js->setCrossOrigin('anonymous');
        $this->addScripts(array(
            $bootstrap_js,
        ));
    }

    private function createSuccessAlerts() : void {
        $alerts_array = ph_get_success_messages();
        if (false === empty($alerts_array)) {
            $this->generateHtml('<div class="alert alert-success alert-dismissible fade show" role="alert">', 3);
            foreach (array_values($alerts_array) as $index => $message) {
                if ($index !== count($alerts_array) - 1) {
                    $this->generateHtml('<p>' . htmlentities($message) . '</p>', 4);
                    $this->generateHtml('<hr />');
                }
                else {
                    $this->generateHtml(htmlentities($message), 4);
                }
            }
            $this->generateHtml('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>', 4);
            $this->generateHtml('</div>', 3);
        }
    }
}