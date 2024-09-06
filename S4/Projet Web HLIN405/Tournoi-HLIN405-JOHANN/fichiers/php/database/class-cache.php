<?php 

namespace PH\Database;

/**
 * La classe Cache permet de mettre en cache les données requêtées à la base de
 * données.
 * C'est une structure de données qui implémente un tableau et les fonctions
 * utilitaires qui vont tout autour d'un cache.
 */
class Cache {
    private array $data;
    private string $cache_time;

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * $cache_time doit être exprimé d'une façon telle que la chaîne puisse être transformée
     * en timestamp avec la fonction php strtotime(). 
     * 
     * @param  string $cache_time La durée pendant laquelle les données restent en cache.
     * 
     * @author Johann Rosain
     * @link   strtotime() : https://www.php.net/manual/en/function.strtotime.php
     */
    public function __construct(string $cache_time = '+5 minutes') {
        $this->data = array();
        $this->cache_time = $cache_time;
    }

    // ------------------------------------------------------------------------
    // Méthodes publiques.

    /**
     * @param  string $key  Nom de la méthode à cache.
     * @param  mixed  $data Les données à cacher.
     * 
     * @author Johann Rosain
     */
    public function set(string $key, mixed $data) : void {
        $this->data[$key] = array(
            'data' => $data,
            'expires' => strtotime($this->cache_time),
        );
    }

    /**
     * @param  string $key Nom de la méthode dans le cache.
     * @return mixed       La donnée en cache, faux si les données ne sont pas 
     *                     valides.
     * 
     * @author Johann Rosain
     * @throws Exception Si la clé n'est pas valide.
     */
    public function get(string $key) : mixed {
        if (!$this->isValid($key)) {
            throw new \Exception("La clé $key n'est pas valide.");
        }
        return $this->data[$key]['data'];
    }

    /**
     * @param  string $key Nom de la méthode dans le cache.
     * @return bool        Vrai si la clé existe et que les données n'ont pas
     *                     expirées.
     * 
     * @author Johann Rosain
     */
    public function isValid(string $key) : bool {
        return $this->exists($key) && !$this->hasExpired($key);
    }

    // ------------------------------------------------------------------------
    // Méthodes privées.

    private function exists(string $key) : bool {
        return array_key_exists($key, $this->data);
    }

    private function hasExpired(string $key) : bool {
        return $this->data[$key]['expires'] < time();
    }
}