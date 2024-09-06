<?php

namespace Core;

/**
 * L'exception NoSuchConfigurationKeyException se produit lors de la demande d'une clé qui n'existe
 * pas dans le fichier de configuration.
 * Vous pouvez récupérer cette clé avec `getKey()`.
 */
class NoSuchConfigurationKeyException extends \Exception {
    private string $key;

    /**
     * @param string $key La clé qui n'a pas été trouvée.
     * 
     * @author Johann Rosain
     */
    public function __construct(string $key) {
        parent::__construct("Erreur : la clé $key n'existe pas dans le fichier de configuration.");
        $this->key = $key;
    }

    /**
     * @return string La clé qui n'a pas été trouvée.
     * 
     * @author Johann Rosain
     */
    public function getKey() : string {
        return $this->key;
    }
}

/**
 * L'exception CanNotOpenConfigurationFileException se produit lors de la demande d'ouverture d'un 
 * fichier qui n'est pas accessible.
 */
class CanNotOpenConfigurationFileException extends \Exception {
    /**
     * @param string $filename Chemin du fichier qui pose problème.
     * 
     * @author Johann Rosain
     */
    public function __construct(string $filename) {
        parent::__construct("Erreur : le fichier $filename n'est pas accessible.");
    }
}