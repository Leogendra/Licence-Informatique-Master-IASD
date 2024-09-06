<?php

namespace Core\Validation\Constraint;

/**
 * Contrainte : la valeur doit être une date valide selon un format
 * 
 * format (string)      => le format de la date voulu.
 * strict_format (bool) => oblige le format à être respécté ou non.
 * min (string)         => la valeur passée doit être postérieure à cette date.
 * max (string)         => la valeur passée doit être antérieure à cette date.
 *  
 * Le format de base est "Y-m-d".
 * Le format n'est pas strict si rien n'est spécifié, sauf si un format est donné.
 * 
 * Exemple :
 * 
 * <code>
 * // Format non strict
 * new DateTime();
 * 
 * // Format strict sur 'Y-m-d'
 * new DateTime(array('strict_format' => true));
 * 
 * // Format strict sur 'd-m-Y'
 * // Les deux sont équivalents
 * new DateTime(array('format' => 'd-m-Y));
 * new DateTime(array('format' => 'd-m-Y, 'strict_format' => true));
 * 
 * // Format non strict sur 'd-m-Y', c'est généralement inutile de faire ça.
 * new DateTime(array('format' => 'd-m-Y, 'strict_format' => false));
 * </code>
 * 
 * Il est généralement inutile de combiner "strict_format" et "format", car le premier
 * signifie généralement que l'on ne veut qu'une date, quelle qu'elle soit.
 * 
 * "min" et "max" peuvent prendre n'importe quelle date valide par un constructeur
 * de \DateTime ou du format donné en paramètre.
 * 
 * Si les caractères " | " sont rencontrés dans les paramètres "min" et "max", la partie
 * avant sera considéré comme la date et la partie après comme une modification pour la
 * date donnée.
 * 
 * Par exemple "now | +1 day" permet de dire "la date actuelle +1 jour".
 * 
 * Exemples de contraintes :
 * 
 * <code>
 * $date_min = '2022/01/01';
 * new DateTime(array(
 *     'format' => 'Y/m/d',            // La date devra forcément être écrite de la sorte.
 *     'min' => $date,                 // La date minimale.
 *     'max' => $date . ' | + 1 week'  // La date maximum est dans une semaine après la date minimale.
 * ));
 * </code>
 * 
 * - https://www.php.net/manual/en/datetime.createfromformat.php (tous les formats)
 * - https://www.php.net/manual/en/datetime.formats.php (les formats de min et max)
 */
class DateTime extends IsString {
    private string $format = 'Y-m-d';
    private bool $strict_format = false;
    private array $constraints;

    /**
     * Toutes les contraintes pour la date.
     * "format" est à "Y-m-d" par défaut.
     * "strict_format" est à "false" par défaut. Si un format est set, il est mis sur
     * "true".
     * 
     * @param array $constraints Le tableaux contenant les contraintes
     * 
     * @author Benoît Huftier
     */
    public function __construct(array $constraints = array()) {
        $this->constraints = $constraints;

        if (array_key_exists('format', $this->constraints)) {
            $this->format = $this->constraints['format'];
            $this->strict_format = true;
        }

        if (array_key_exists('strict_format', $this->constraints)) {
            $this->strict_format = $this->constraints['strict_format'];
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function assertString(string $field_name, string $value) : Result {
        $result = new Result($field_name);

        $date = $this->checkFormatAndCreateDate($value, $result);
        
        // Si la date n'est pas une DateTime, c'est qu'il y a eu une erreur dans
        // la création de la date.
        if (false === $date) {
            return $result;
        }

        if (array_key_exists('min', $this->constraints)) {
            $this->checkMin($this->constraints['min'], $date, $result);
        }

        if (array_key_exists('max', $this->constraints)) {
            $this->checkMax($this->constraints['max'], $date, $result);
        }

        return $result;
    }

    // ------------------------------------------------------------------------
    // Méthodes de validation privées.

    private function checkFormatAndCreateDate(string $value, Result &$result) : \DateTime|false {
        $date = \DateTime::createFromFormat($this->format, $value);
    
        // La création a échouée donc la date n'a pas le bon format
        if (false === $date) {
            if ($this->strict_format) {
                $result->setInvalid();
                $result->addMessage('Le format de la date est invalide');
            }
            else {
                try {
                    $date = $this->createFormattedDateTime($value);
                }
                catch (\Exception $e) {
                    $result->setInvalid();
                    $result->addMessage('Le date n\'est pas valide');
                }
            }
        }

        return $date;
    }

    private function checkMin(string $value, \DateTime $date, Result &$result) : void {
        try {
            $date_min = $this->createDate($value);

            if ($date_min > $date) {
                $d = $date_min->format($this->format);
                $result->setInvalid();
                $result->addMessage("La date doit être postérieure à la date suivante : $d");
            }
        }
        catch (\Exception $e) {
            $result->setInvalid();
            $result->addMessage("La date minimale n'est pas une date valide, il est donc impossible de vérifier la date donnée");
        }
    }

    private function checkMax(string $value, \DateTime $date, Result &$result) : void {
        try {
            $date_max = $this->createDate($value);
    
            if ($date_max < $date) {
                $d = $date_max->format($this->format);
                $result->setInvalid();
                $result->addMessage("La date doit être antérieure à la date suivante : $d");
            }
        }
        catch (\Exception $e) {
            $result->setInvalid();
            $result->addMessage("La date maximale n'est pas une date valide, il est donc impossible de vérifier la date donnée");
        }
    }

    // ------------------------------------------------------------------------
    // Méthodes privées.

    private function createDate(string $value) : \DateTime {
        $pos = strpos($value, ' | ');
        $modification = '';

        if (false !== $pos) {
            $modification = substr($value, $pos + 3);
            $value = substr($value, 0, $pos);
        }

        $date = $this->createFormattedDateTime($value);
        if (!empty($modification)) {
            $date->modify($modification);
        }

        return $date;
    }

    /**
     * Il faut utiliser cette méthode chaque fois qu'une date est créée afin d'avoir
     * des dates toujours formattées de la même façon.
     * 
     * Le format de la date ne sera pas forcément celui donné car DateTime force le format,
     * mais toutes les microsecondes seront au moins mises à 0, ce qui empêche les dates
     * de diverger en fonction du temps passé entre deux instructions.
     * 
     * @throws \Exception Si une erreur survient, c'est à dire que la date n'est pas valide
     */
    private function createFormattedDateTime(string $value) : \DateTime {
        // Cette exception est ajoutée car la date vide est considérée comme la date actuelle,
        // ce qui n'est pas le résultat attendu 
        if (true === empty($value)) {
            throw new \Exception('Impossible de créer une date avec une valeur vide');
        }

        // On essaie de créer la date depuis le format en premier car le constructeur de
        // DateTime n'accepte que les dates avec un format spécifique
        // https://www.php.net/manual/fr/datetime.formats.php
        $datetime = \DateTime::createFromFormat($this->format, $value);

        // Si la valeur ne correspond pas au format
        if (false === $datetime) {
            $datetime = new \DateTime($value);
            // Cette ligne sert à mettre la date au bon format.
            // Ainsi, toutes les dates seront formatées de façon identique dans la classe.
            $datetime = \DateTime::createFromFormat($this->format, $datetime->format($this->format));
        }

        return $datetime;
    }
}