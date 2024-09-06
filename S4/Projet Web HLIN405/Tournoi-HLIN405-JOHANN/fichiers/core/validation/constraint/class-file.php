<?php

namespace Core\Validation\Constraint;

/**
 * Contrainte : la valeur doit être un fichier existant
 * 
 * Plusieurs paramètre peuvent être fourni à cette contrainte :
 * - "types" => un tableau contenant tous les mime type que peut prendre le fichier envoyé
 * - "max_size" => La taille maximum que peux avoir le fichier envoyé.
 * 
 * "max_size" peut prendre une valeur en Ko, Mo ou Go, il faut juste bien mettre un espace
 * entre la valeur et l'unité. Si l'unité est fausse ou non spécifiée, ce sera en octets.
 * Attention, la taille maximale pouvant être upload est gérée par apache et php.
 * 
 * Toutes les différentes valeurs du tableaux "types" seront vérifier en tant que regex,
 * vous pouvez donc écrire 'image/*' qui va par exemple vérifier que le fichier est de
 * n'importe quel type d'image, image/png, image/gif, etc...
 * Les '/' n'ont pas besoin d'être échappés mais tous les autres caractères de regex qui ne
 * font pas partie d'une regex voulue doivent être échappés.
 * 
 * <code>
 * new File(array(
 *     'types' => array(
 *         'image/*',   // N'importe quel type d'image.
 *         'text/plain'
 *     ),
 *     'max_size' => '2 Mio' // Seule la première lettre compte, vous pouvez mettre
 *                           // "Mo", "Mio", "M", "m"... Mais il faut un espace.
 * ));
 * </code>
 */
class File extends Constraint {
    private array $constraints;

    public function __construct(array $constraints = array()) {
        $this->constraints = $constraints;
    }

    /**
     * Il peut y avoir deux types de valeurs pour un fichier :
     * 
     * - Une simple chaine de caractères qui est le chemin absolu vers le fichier.
     * - Un tableau type envoyé par $_FILES en post.
     * 
     * La vérification est légèrement différente selon le type de valeur envoyée.
     * 
     * {@inheritdoc}
     */
    public function assert(string $field_name, mixed $value) : Result {
        if (is_string($value)) {
            return $this->assertFileString($field_name, $value);
        }

        if (is_array($value)
            && array_key_exists('name', $value) && is_string($value['name'])
            && array_key_exists('type', $value) && is_string($value['name'])
            && array_key_exists('tmp_name', $value) && is_string($value['tmp_name'])
            && array_key_exists('error', $value) && is_int($value['error'])
            && array_key_exists('size', $value) && is_int($value['size'])) {
            return $this->assertFileArray($field_name, $value);
        }

        $result = new Result($field_name);
        $result->setInvalid();
        $result->addMessage('Valeur invalide');
        return $result;
    }

    /**
     * Un fichier est vide si c'est une string vide ou si c'est un tableau $_FILES
     * contenant l'erreur UPLOAD_ERR_NO_FILE
     * 
     * {@inheritdoc}
     */
    public function isNull(mixed $value) : bool {
        return '' === $value || (isset($value['error']) && UPLOAD_ERR_NO_FILE === $value['error']);
    }

    // ------------------------------------------------------------------------
    // Méthodes d'assertion privées.

    private function assertFileString(string $field_name, string $file) : Result {
        $result = new Result($field_name);
        
        if (false === is_file($file)) {
            $result->setInvalid();
            $result->addMessage('Impossible de récupérer le fichier fourni');
            return $result;
        }

        if (array_key_exists('max_size', $this->constraints)) {
            $this->verifySize(filesize($file), $this->constraints['max_size'], $result);
        }

        if (array_key_exists('types', $this->constraints)) {
            $this->verifyType(mime_content_type($file), $this->constraints['types'], $result);
        }

        return $result;
    }

    private function assertFileArray(string $field_name, array $file_params) : Result {
        $result = new Result($field_name);
        
        if (false === is_file($file_params['tmp_name'])) {
            $result->setInvalid();
            $result->addMessage('Impossible de récupérer le fichier fourni');
            return $result;
        }

        if (array_key_exists('max_size', $this->constraints)) {
            $this->verifySize($file_params['size'], $this->constraints['max_size'], $result);
        }

        if (array_key_exists('types', $this->constraints)) {
            $this->verifyType($file_params['type'], $this->constraints['types'], $result);
        }

        return $result;
    }

    // ------------------------------------------------------------------------
    // Méthodes de validation privées.

    private function verifySize(int $size, string $max_str, Result &$result) : void {
        $pos = strpos($max_str, ' ');
        $type = 'Octets';
        
        if (false !== $pos) {
            $type = strtolower(substr($max_str, $pos + 1, 1));
            $max = intval(substr($max_str, 0, $pos));
            
            switch ($type) {
                case 'k':
                    $max *= 1024;
                    $type = 'Ko';
                    break;
                case 'm':
                    $max *= 1024 * 1024;
                    $type = 'Mo';
                    break;
                case 'g':
                    $max *= 1024 * 1024 * 1024;
                    $type = 'Go';
                    break;
                default:
                    $type = 'Octets';
                    break;
            }
        }
        else {
            $max = intval($max_str);
        }

        if ($size > $max) {
            $result->setInvalid();
            $result->addMessage("Le fichier est trop gros. Taille max : $max $type");
        }
    }

    private function verifyType(string $type, array $allowed_types, Result &$result) : void {
        foreach ($allowed_types as $allowed_type) {
            $allowed_type = str_replace('/', '\\/', $allowed_type);
            if (1 === preg_match("/$allowed_type/", $type)) {
                return;
            }
        }

        $result->setInvalid();
        $result->addMessage("Le type $type n'est pas autorisé");
    }
}