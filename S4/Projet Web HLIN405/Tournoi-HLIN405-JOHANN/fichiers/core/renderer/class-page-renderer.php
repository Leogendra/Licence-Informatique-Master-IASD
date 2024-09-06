<?php

namespace Core\Renderer;

require_once __DIR__ . '/../autoloader.php';

/**
 * La classe PageRenderer permet :
 *     - une gestion facile, rapide, et sécurisée des permissions d'accès a une page web.
 *     - un ajout automatique des feuilles de style et des scripts utilisés dans la page pour une 
 *       programmation modulaire.
 *     - la définition de sections a accès protégées de manière simple et efficace.
 *     - Le rendu automatique d'une page web.
 * C'est une classe abstraite, c'est à dire qu'il faut que les classes du site, des classes de
 * `Template` en héritent pour :
 *     - Faire un rendu personnalisé.
 *     - Gérer les permissions d'accès.
 * La plupart des méthodes pour gérer ces 2 points sont déjà implémentées (en tant que méthodes 
 * protégées). La suite de la documentation détaille ces méthodes.
 */
abstract class PageRenderer {
    private \Core\Permissions $permissions_needed;
    private string $body = '';
    private string $title = '';
    private array $stylesheets = array();
    private array $scripts = array();
    private array $meta_tags = array();
    private array $restricted_sections = array();
    private array $special_sections = array();
    private \Closure $action_on_forbidden;

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * Construit une page de rendu par défaut : besoin d'aucune permissions pour
     * voir le contenu de la page, et balises meta pour utf-8 et un développement
     * responsive.
     * 
     * @author Johann Rosain
     */
    public function __construct() {
        $this->setPermissionsNeeded(0);
        $this->addOrSetMetaTags(array(
            new \Core\Tags\MetaCharsetTag('UTF-8'),
            new \Core\Tags\MetaContentTag('viewport', 'width=device-width, initial-scale=1'),
            new \Core\Tags\MetaHTTPEquivTag('Content-type', 'text/html; charset=UTF-8'),
        ));
    }
 
    // ------------------------------------------------------------------------
    // Méthodes publiques.

    /**
     * Si l'utilisateur a les droits pour accéder à la page, alors il ne se passe rien. Sinon,
     * envoie une erreur http 403 et exécute la fonction utilisateur définie dans 
     * setActionOnForbidden().
     * 
     * @author Johann Rosain
     * @see    setActionOnForbidden(callable)
     */
    abstract public function forbidAccessIfNotPermitted() : void;

    /**
     * Génère la page HTML.
     * 
     * @author Johann Rosain
     */
    final public function render() : void {
        echo $this->make()->saveHTML();
    }

    // ------------------------------------------------------------------------
    // Accesseurs en écriture.

    /**
     * @param  int $permission_needed Permissions dont l'utilisateur a besoin pour accéder au 
     *                                contenu de la page.
     * 
     * @author Johann Rosain
     */
    public function setPermissionsNeeded(int $permission_needed) : void {
        $this->permissions_needed = new \Core\Permissions($permission_needed);
    }

    /**
     * @param  string    $path_to_body Chemin absolu vers le corps de la page à générer.
     * @throws Exception               Si le fichier n'a pas été trouvé.
     * 
     * @author Johann Rosain
     */
    public function setBody(string $path_to_body) : void {
        if (file_exists($path_to_body)) {
            $this->body = $path_to_body;
        }
        else {
            throw new \Exception("Fichier du corps de la page non trouvé : $path_to_body ce fichier n'existe pas !");
        }
    }

    /**
     * Ajoute tous les `scripts` donnés s'ils ne sont pas déjà dans le tableau.
     * 
     * @param  array $scripts Tableau de \Core\Tags\ScriptTag
     * 
     * @author Johann Rosain
     */
    public function addScripts(array $scripts) : void {
        $this->addScriptsToLocalsIfNotExists($scripts);
    }

    /**
     * Ajoute tous les `liens` donnés s'ils ne sont pas déjà dans le tableau.
     * 
     * @param  array $stylesheets Tableau de \Core\Tags\LinkTag
     * 
     * @author Johann Rosain
     */
    public function addStylesheets(array $stylesheets) : void {
        $this->addCssToLocalsIfNotExists($stylesheets);
    }

    /**
     * Cette méthode fait soit :
     *      - Ajout du metatag dans le tableau s'il n'y est pas encore
     *      - Réécriture du metatag dans le tableau s'il existe déjà
     * 
     * @param  array $meta_tags Tableau de \Core\Tags\MetaTag
     * 
     * @author Johann Rosain
     */
    public function addOrSetMetaTags(array $meta_tags) : void {
        $this->addOrSetMetaTagsToLocals($meta_tags);
    }

    /**
     * Cette méthode ajoute des sections restreintes sur la page.
     * Une section restreinte est une section qui peut être accédée seulement avec certaines 
     * permissions.
     * Cette méthode prend en paramètre un tableau tel que celui-ci :
     * <code>
     * array(
     *     Role::Admin => array('nom-classe-1', 'nom-classe-2'),
     *     Role::Modo => array('nom-classe-3', 'nom-classe-4'),
     *     Role::Admin | Role::Modo => array('nom-classe-5'),
     * )
     * </code>
     * Il associe des flags à des noms de classes HTML.
     * 
     * @param  array $restricted_sections Tableau de \Core\Permissions => array(string)
     * 
     * @author Johann Rosain
     */
    public function addRestrictedSections(array $restricted_sections) : void {
        $this->addRestrictedSectionsToLocals($restricted_sections);
    }

    /**
     * Cette méthode permet de définir les section spéciales, c'est à dire les sections 
     * qu'il faut afficher selon un prédicat. Cette méthode prend en paramètre un tableau
     * de type string => prédicat, tel que celui-ci : 
     * <code>
     * array(
     *     'classe-1' => eval(predicat), // Le prédicat doit déjà être évalué, donc true ou false
     *     'classe-2' => eval(predicat2),
     * );
     * </code>
     * 
     * @param  array $special_sections Tableau de string => bool
     * 
     * @author Johann Rosain
     */
    public function setSpecialSections(array $special_sections) : void {
        $this->special_sections = $special_sections;
    }

    /**
     * @param  string $title Titre de l'onglet.
     * 
     * @author Johann Rosain
     */
    public function setTitle(string $title) : void {
        $this->title = $title;
    }

    /**
     * Exécute la fonction donnée si l'utilisateur n'a pas les permissions nécessaires pour consulter
     * la page.
     * 
     * @param  callable $fun Fonction à appeler sur erreur 403.
     * 
     * @author Johann Rosain
     */
    public function setActionOnForbidden(callable $fun) : void {
        $this->action_on_forbidden = \Closure::fromCallable($fun);
    }

    // ------------------------------------------------------------------------
    // Méthodes protégées.

    /**
     * @param  Core\Permissions $user_permissions Permissions de l'utilisateur actuel
     * 
     * @author Johann Rosain
     */
    final protected function testPermissionsForAndForbidIfNeeded(\Core\Permissions $user_permissions) : void {
        if (!$this->isAccessAllowedAs($user_permissions)) {
            $this->forbidAccess();
        }
    }

    /**
     * Créer la page, cette méthode doit être surchargée et doit :
     * - Générer la page
     * - Gérer les restrictions
     * 
     * @return \DOMDocument Le document DOM généré par la page.
     * 
     * @author Benoît Huftier
     * @see    generatePage(), removeNotAllowedNodes()
     */
    abstract protected function make() : \DOMDocument;

    /**
     * La génération d'une page se fait comme suit :
     *      - Ouverture de page
     *      - Head
     *          - Titre
     *          - Méta données
     *          - Lien CSS
     *      - Body
     *          - Scripts
     *      - Fermeture de page
     * 
     * Pour afficher le rendu : echo $this->make()->saveHTML();
     * Toutes les méthodes sont disponibles pour surcharger la façon de rendre la page.
     * En suivant l'ordre donné plus haut, voici les méthodes à surcharger pour modifier le rendu :
     * 
     *      - beginPage()
     *      - generateHead()
     *          - generateTitleTag()
     *          - generateMetaTags()
     *          - generateStylesheetTags()
     *      - generateBody()
     *          - generateScripts()
     *      - endPage()
     * 
     * @return DOMDocument Le document DOM généré par la page.
     * 
     * @author Johann Rosain
     * @see    beginPage(), generateHead(), generateBody(), endPage()
     */
    final protected function generatePage() : \DOMDocument {
        ob_start();
        $this->beginPage();
        $this->generateHead();
        $this->generateBody();
        $this->endPage();
        $dom_page = new \DOMDocument();
        @$dom_page->loadHTML(ob_get_clean());
        return $dom_page;
    }

    /**
     * Affiche les deux premières balises d'une page HTML.
     * 
     * @author Johann Rosain
     * @see    generateHTML()
     */
    protected function beginPage() : void {
        $this->generateHtml('<!DOCTYPE html>');
        $this->generateHtml('<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">');
    }

    /**
     * Affiche l'entiéreté de la balise <head> de la page
     * 
     * @author Johann Rosain
     * @see    generateHTML(), generateTitleTag(), generateMetaTags(), generateStylesheetTags()
     */
    protected function generateHead() : void {
        $this->generateHtml('<head>', 1);
        $this->generateTitleTag();
        $this->generateMetaTags();
        $this->generateStylesheetTags();
        $this->generateHtml('</head>', 1);
    }

    /**
     * Affiche la balise de titre
     * 
     * @author Johann Rosain
     * @see    generateHTML(), getTitle()
     */
    protected function generateTitleTag() : void {
        $this->generateHtml("<title>{$this->getTitle()}</title>", 2);
    }

    /**
     * Affiche toutes les balises de méta-données
     * 
     * @author Johann Rosain
     * @see    generateHTML(), getMetaTags()
     */
    protected function generateMetaTags() : void {
        foreach ($this->getMetaTags() as $meta_tag) {
            $this->generateHtml($meta_tag->make(), 2);
        }
    } 

    /**
     * Affiche toutes les balises link pour les fichiers CSS
     * 
     * @author Johann Rosain
     * @see    generateHTML(), getStylesheets()
     */
    protected function generateStylesheetTags() : void {
        foreach ($this->getStylesheets() as $stylesheet) {
            $this->generateHtml($stylesheet->make(), 2);
        }
    }

    /**
     * Affiche l'entièreté du corps de la page (entre les balises <body>)
     * 
     * @author Benoît Huftier
     * @see    generateHTML(), getBody(), generateScripts()
     */
    protected function generateBody() : void {
        $this->generateHtml('<body>', 1);
        include $this->getBody();
        $this->generateScripts();
        $this->generateHtml('</body>', 1);
    }

    /**
     * Affiche toutes les balises scripts pour les fichiers JS
     * 
     * @author Benoît Huftier
     * @see    generateHTML(), getScripts()
     */
    protected function generateScripts() : void {
        foreach ($this->getScripts() as $script) {
            $this->generateHtml($script->make());
        }
    }

    /**
     * Affiche la balise de fin de document html. 
     * 
     * @author Johann Rosain
     * @see    generateHTML()
     */
    protected function endPage() : void {
        $this->generateHtml('</html>');
    }

    /**
     * Affiche une ligne de html proprement : bien indentée et finissant par un retour à la ligne.
     * Cette méthode doit être appelée lorsque la classe fille décide de surcharger les méthodes
     * de générations de la page.
     * 
     * @param  string  $tag                     La ligne html a écrire
     * @param  int     $number_of_indentations  La ligne sera indenté d'autant de tabulations.
     *                                          Une tabulation est représentée par 4 espaces.
     *                                          Valeur par défaut : 0.
     * 
     * @author Benoît Huftier
     */
    final protected function generateHtml(string $tag, int $number_of_indentations = 0) : void {
        while ($number_of_indentations-- > 0) {
            echo '    ';
        }
        echo $tag;
        echo PHP_EOL;
    }

    /**
     * @return string Titre de la page
     * 
     * @author Benoît Huftier
     */
    final protected function getTitle() : string {
        return $this->title;
    }

    /**
     * @return string Chemin vers le corps de la page à générer.
     * 
     * @author Johann Rosain
     */
    final protected function getBody() : string {
        return $this->body;
    }

    /**
     * @return array Tableau de \Core\Tags\MetaTag, toutes les balises de meta données de la page
     * 
     * @author Benoît Huftier
     */
    final protected function getMetaTags() : array {
        return $this->meta_tags;
    }

    /**
     * @return array Tableau de \Core\Tags\LinkTag, toutes les balises link de la page
     * 
     * @author Benoît Huftier
     */
    final protected function getStylesheets() : array {
        return $this->stylesheets;
    }

    /**
     * @return array Tableau de \Core\Tags\ScriptTag, toutes les balises de script de la page
     * 
     * @author Benoît Huftier
     */
    final protected function getScripts() : array {
        return $this->scripts;
    }

    /**
     * Supprime du DOM les noeuds que l'utilisateur n'a pas le droit de consulter
     * avec ses permissions.
     * 
     * @param  DOMDocument      &$doc             Document DOM de la page générée.
     * @param  Core\Permissions $user_permissions Permissions de l'utilisateur.
     * 
     * @author Johann Rosain
     */
    final protected function removeNotAllowedNodes(\DOMDocument &$doc, \Core\Permissions $user_permissions) : void {
        foreach ($this->restricted_sections as $permission_needed => $classes) {
            if (!$user_permissions->hasAny($permission_needed)) {
                foreach ($classes as $class) {
                    $this->removeChildsOfClass($doc, $class);
                }
            }
        }
        foreach ($this->special_sections as $class => $predicat) {
            if (false === $predicat) {
                $this->removeChildsOfClass($doc, $class);
            }
        }
    }

    // ------------------------------------------------------------------------
    // Méthodes privées.

    private function addScriptsToLocalsIfNotExists(array $scripts) : void {
        foreach ($scripts as $script) {
            $this->pushIfNotInArray($this->scripts, $script);
        }
    }

    private function addCssToLocalsIfNotExists(array $stylesheets) : void {
        foreach ($stylesheets as $stylesheet) {
            $this->pushIfNotInArray($this->stylesheets, $stylesheet);
        }
    }

    private function addOrSetMetaTagsToLocals(array $meta_tags) : void {
        foreach ($meta_tags as $meta_tag) {
            $foundMetaTag = $this->findInObjectArr($this->meta_tags, $meta_tag);
            if ($foundMetaTag !== false) {
                unset($this->meta_tags[$foundMetaTag]);
            }
            array_push($this->meta_tags, $meta_tag);
        }
    }

    private function addRestrictedSectionsToLocals(array $restricted_sections_array) : void {
        foreach ($restricted_sections_array as $role => $restricted_sections) {
            if (array_key_exists($role, $this->restricted_sections)) {
                $this->restricted_sections[$role] = array_unique(array_merge($this->restricted_sections[$role], $restricted_sections));
            }
            else {
                $this->restricted_sections[$role] = $restricted_sections;
            }
        }
    }

    private function pushIfNotInArray(array &$array, mixed $value) : void {
        if ($this->findInObjectArr($array, $value) === false) {
            array_push($array, $value);
        }
    }

    private function findInObjectArr(array $object_array, object $obj) : int|bool {
        foreach ($object_array as $index => $object) {
            if ($object->isEquivalent($obj)) {
                return $index;
            }
        }
        return false;
    }

    private function isAccessAllowedAs(\Core\Permissions $permissions) : bool {
        return $this->permissions_needed->getFlags() === 0 || $permissions->hasAny($this->permissions_needed->getFlags());
    }

    private function forbidAccess() : void {
        http_response_code(403);
        if (isset($this->action_on_forbidden)) {
            call_user_func($this->action_on_forbidden);
        }
        exit;
    }

    private function removeChildsOfClass(\DOMDocument &$doc, string $class) : void {
        $finder = new \DOMXPath($doc);
        $nodes = $finder->query("//*[contains(concat(' ', @class, ' '), ' $class ')]");
        foreach ($nodes as $node) {
            $node->parentNode->removeChild($node);
        }
    }
}