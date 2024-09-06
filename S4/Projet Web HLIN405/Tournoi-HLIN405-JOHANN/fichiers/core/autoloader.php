<?php
namespace Core;

spl_autoload_register('Core\\Autoloader::classLoader');
spl_autoload_register('Core\\Autoloader::exceptionLoader');

define('CORE', __DIR__);

/**
 * La classe Autoloader permet d'automatiser la recherche des fichiers à inclure à la création d'une 
 * nouvelle classe. 
 * L'autoloader marche pour tous les fichiers qui correspondent aux conventions  d'écriture du module 
 * Core.
 * <code>
 * <?php
 * require_once(__DIR__ . '/core/autoloader.php');
 * 
 * // Trouve automatiquement et inclus le fichier qui contient la classe 
 * // Core\ConfigurationFileParser (core/class-configuration-file-parser.php)
 * $cfg_file = new Core\ConfigurationFileParser(__DIR__ . '/whatever.cfg');
 * 
 * // Trouve automatiquement et inclus le fichier qui contient la classe Core\MyAwesomeFeature 
 * // (core/class-my-awesome-feature.php)
 * $awesome_feature = new Core\MyAwesomeFeature;
 * </code>
 */
class Autoloader {
    private static string $root_directory = __DIR__ . '/..';

    // ------------------------------------------------------------------------
    // Méthodes publiques.

    /**
     * Charge automatiquement les fichiers de classe PHP.
     * 
     * @author Johann Rosain
     */
    public static function classLoader(string $class_name) : void { 
        $exploded_path = self::getExplodedPath($class_name);
        $path = self::getRealPath($exploded_path);
        self::includeFileIfExists($path);
    }

    /**
     * Charge automatiquement les fichiers d'exception de PHP.
     * 
     * @author Johann Rosain
     */
    public static function exceptionLoader(string $class_name) : void {
        $exceptionsDir = __DIR__ . '/exceptions';
        if (str_contains($class_name, 'Exception') && str_contains($class_name, 'Configuration')) {
            self::includeFileIfExists($exceptionsDir . '/class-configuration-file-exceptions.php');
        }
    }

    // ------------------------------------------------------------------------
    // Méthodes privées.

    private static function includeFileIfExists(string $file) : void {
        if (file_exists($file)) {
            include_once $file;
        }
    }

    private static function getRealPath(array $exploded_path) : string {
        $path = explode('/', self::$root_directory);
        foreach ($exploded_path as $directory) {
            $real_path = strtolower(implode('-', self::explodeOnCaps($directory)));
            array_push($path, $real_path);
        }
        $last = count($path) - 1;
        $path[$last] = 'class-' . $path[$last];
        return implode('/', $path) . '.php';
    }

    private static function getExplodedPath(string $class_name) : array {
        return explode('\\', $class_name);
    }

    private static function explodeOnCaps(string $path) : array {
        $arr = preg_split('/(?=[A-Z])/', $path, -1, PREG_SPLIT_NO_EMPTY);
        return self::concatAcronyms($arr);
    }

    private static function concatAcronyms(array $arr) : array {
        $final_array = array($arr[0]);
        array_shift($arr);
        foreach ($arr as $element) {
            $last_index = count($final_array) - 1;
            $last_elem = $final_array[$last_index];
            // Si le dernier élément du tableau final ne comporte qu'un seul caractère, c'est un 
            // acronyme. De plus, l'acronyme peut faire plus d'un caractère. Si tous les caractères
            // du dernier élément du tableau sont des majuscules et que l'élément actuel n'a
            // qu'un caractère, il fait partie de l'acronyme
            if ($last_elem === 1 || 
               (strtoupper($last_elem) === $last_elem && strlen($element) === 1)) {
                $final_array[$last_index] .= $element;
            }
            else {
                array_push($final_array, $element);
            }
        }
        return $final_array;
    }
}
