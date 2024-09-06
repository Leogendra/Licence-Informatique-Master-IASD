<?php 

/**
 * Enumération des différents types de tournois disponibles sur le site.
 */
abstract class Type {
    const Coupe = 1 << 0;
    const Championnat = 1 << 1;
    const Poules = 1 << 2;

    private static array $string_values = array(
        self::Coupe => 'Coupe',
        self::Championnat => 'Championnat',
        self::Poules => 'Poules',
    );

    /**
     * Permet de fabriquer un type en fonction du nom donné en français dans la BDD
     * 
     * @param  string $name Le nom dans la BDD
     * @return int          Le Type correspondant au nom
     * @throws \Exception   Si le nom n'est pas trouvé dans le tableau de valeurs.
     * 
     * @author Johann Rosain
     */
    static public function fromString(string $name) : int {
        $result = array_search($name, self::$string_values, $strict = true);
        if (false === $result) {
            throw new \Exception("Aucun type de nom $name n'existe.");
        }
        return $result;
    }

    /**
     * Permet de fabriquer un nom en français à partir du Type donné. 
     * 
     * @param  int    $type Le type à convertir.
     * @return string       Le nom du type en français.
     * @throws \Exception   Si le tyhpe n'est pas trouvé dans le tableau de valeur
     * 
     * @author Johann Rosain
     */
    static public function toString(int $type) : string {
        if (false === array_key_exists($type, self::$string_values)) {
            throw new \Exception("L'énumération ne possède pas d'élément de valeur $type.");
        }
        return self::$string_values[$type];
    }
}