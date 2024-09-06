<?php

namespace Core;

/**
 * La classe ConfigurationFileParser permet d'ouvrir, lire, et récupérer le contenu d'un fichier 
 * .cfg.
 * Actuellement, cette classe détecte automatiquement les clés et les valeurs d'un fichier.
 * De plus, il est possible de spécifier des clés qui peuvent apparaître plusieurs fois.
 * Exemple : récupérer les valeurs d'un fichier `conf.cfg` avec la clé `development` :
 * <code>
 * <?php
 * require_once(__DIR__ . '/core/autoloader.php');
 * 
 * $cfgFileParser = new Core\ConfigurationFileParser(__DIR__ . '/conf.cfg');
 * $cfgFileParser->readAndParseFile();
 * var_dump($cfgFileParser->getConfigValue('development'));
 * </code>
 */
class ConfigurationFileParser {
    private array $keys_with_multiple_values;
    private array $configurations;
    private string $filename;

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * @param  string $filename Chemin du fichier à lire.
     * 
     * @author Johann Rosain
     */
    public function __construct(string $filename) {
        $this->keys_with_multiple_values = array();
        $this->configurations = array();
        $this->filename = $filename;
    }

    // ------------------------------------------------------------------------
    // Méthodes publiques.

    /**
     * Ajoute une clé qui peut apparaître une ou plusieurs fois dans le fichier de configuration.
     * 
     * @param  string $key La clé qui peut être dupliquée.
     * 
     * @author Johann Rosain
     */
    public function addMultipleAppearancesKey(string $key) : void {
        if (!in_array($key, $this->keys_with_multiple_values, $strict = true)) {
            array_push($this->keys_with_multiple_values, $key);
        }
    }

    /**
     * Traite les informations du fichier. Ne renvoie rien, lance juste le  traitement du fichier 
     * en interne.
     * 
     * @param  ?callable $on_fail La fonction à appeler si le fichier ne peut pas être lu. 
     * 
     * @author Johann Rosain
     */
    public function readAndParseFile(?callable $on_fail = null) : void {
        try {
            $this->openAndProcessFile();
        }
        catch (CanNotOpenConfigurationFileException $e) {
            if(!is_null($on_fail)) {
                $on_fail();
            }
        }
    }

    /**
     * @param  string       $key   La clé de la valeur de configuration à récupérer.
     * @return string|array        Si la clé peut avoir plusieurs apparences, retourne un tableau. 
     *                             Sinon, retourne une chaîne.
     * @throws NoSuchConfigurationKeyException Si la clé demandé n'a pas été trouvée dans le fichier 
     *                                         de configuration.
     * 
     * @author Johann Rosain
     */
    public function getConfigurationValue(string $key) : string|array {
        if (array_key_exists($key, $this->configurations)) {
            return $this->configurations[$key];
        }
        throw new NoSuchConfigurationKeyException($key);
    }

    // ------------------------------------------------------------------------
    // Méthodes privées.

    private function openAndProcessFile() : void {
        try {
            $file_handle = fopen($this->filename, 'r'); 
            if ($file_handle) {
                $this->processFileContent($file_handle);
                fclose($file_handle);
            }
        }
        catch(\Exception $e) {
            throw new CanNotOpenConfigurationFileException($this->filename);
        }
    }

    private function processFileContent(/* resource */$file_handle) : void {
        while (($line = fgets($file_handle)) !== false) {
            $line = $this->cleanLine($line);
            if ($this->isLineValid($line)) {
                $key_value_array = $this->getKeyValue($line);
                if (!empty($key_value_array)) {
                    [$key, $value] = $key_value_array;
                    $this->addConfigurationKeyValue($key, $value);
                }
            }
        }
    }

    private function cleanLine(string $line) : string {
        $line = $this->removeComments($line);
        $line = $this->stripUnnecessaryChars($line);
        return $line;
    }

    private function isLineValid(string $line) : bool {
        $result = preg_match('/^.+:.*$/', $line);
        return $result != false;
    }

    private function getKeyValue(string $line) : array {
        $offset = strpos($line, ':');
        $key_value_array = array();
        if ($offset !== false) {
            $key_value_array = array(
                substr($line, 0, $offset),
                substr($line, $offset+1),
            );
        }
        return $key_value_array;
    }

    private function addConfigurationKeyValue(string $key, string $value) : void {
        if (in_array($key, $this->keys_with_multiple_values, $strict = true)) {
            $this->configurations[$key][] = $value;
        }
        else {
            $this->configurations[$key] = $value;
        }
    }

    private function removeComments(string $line) : string {
        $pos = strpos($line, '#');
        if ($pos !== false) {
            $line = substr($line, 0, $pos);
        }
        return $line;
    }

    private function stripUnnecessaryChars(string $line) : string {
        $line = trim(preg_replace(array('/ /', '/\t/', '/\0/', '/\v/'), '', $line));
        return $line;
    }
}