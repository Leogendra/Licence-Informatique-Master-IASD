<?php 

namespace PH;

/**
 * La classe Location permet de stocker toutes les informations intéressantes d'un lieu.
 * Notamment, elle réuni tous les attributs qui sont dispersées dans plusieurs tables 
 * de la base de données, comme le nom de la ville, le code postal, ou bien l'adresse.
 * De plus, elle permet de calculer un attribut dérivé : le numéro de département.
 */
class Location {
    private int $id;
    private string $city;
    private string $zip_code;
    private string $address1;
    private string|null $address2;

    // --------------------------------------------------------------------------------------------
    // Méthodes de construction statiques.

    /**
     * Crée une Location à partir de l'id demandé. 
     * 
     * @param  int      $id L'id depuis lequel récupérer les informations dans la base de données 
     * @return Location     Le lieu construit grâce à l'id donné
     * @throws \Exception   Si l'id n'existe pas dans la base de données.
     * 
     * @author Johann Rosain
     */
    public static function fromId(int $id) : Location {
        global $phdb;

        $data = $phdb->getLocation($id);

        if (empty($data)) {
            throw new \Exception('L\'id fourni n\'existe pas dans la base de données.');
        }
        
        return self::fromRawData(
            array(
                'id' => $id,
                'city' => $data['name'],
                'code' => $data['code'],
                'address1' => $data['address1'],
                'address2' => $data['address2'],
            )
        );
    }

    /**
     * Crée une Location à partir de données brutes.
     * Le tableau de données brutes doit être paramétré comme ceci :
     * array(
     *     'id' => int,
     *     'city' => string,
     *     'code' => string,
     *     'address1' => string,
     *     'address2' => string|null,
     * );
     * 
     * @param  array    $raw_data Les données brutes nécessaires à construire un Lieu.
     * @return Location           Le lieu construit grâce à ces données.
     * @throws \Exception         Si une des clés nécessaires est manquante.
     * @throws \Exception         Si une des 5 valeurs n'est pas du type attendu.
     * 
     * @author Johann Rosain
     */
    public static function fromRawData(array $raw_data) : Location {
        if (!array_key_exists('id', $raw_data) ||
            !array_key_exists('city', $raw_data) ||
            !array_key_exists('code', $raw_data) ||
            !array_key_exists('address1', $raw_data) ||
            !array_key_exists('address2', $raw_data)) {
            throw new \Exception('Le tableau de données brute n\'est pas adapté pour construire un objet de type lieu.');
        }

        if (5 !== strlen($raw_data['code']) || 
            is_null($raw_data['city']) || 
            is_null($raw_data['address1']) || 
            !is_int($raw_data['id'])) {
            throw new \Exception('Le tableau de données brute pour construire un lieu contient des éléments incorrects.');
        }

        return new Location($raw_data);
    }

    // --------------------------------------------------------------------------------------------
    // Constructeur privé. 

    private function __construct(array $raw_datas) {
        $this->id = $raw_datas['id'];
        $this->city = $raw_datas['city'];
        $this->zip_code = $raw_datas['code'];
        $this->address1 = $raw_datas['address1'];
        $this->address2 = $raw_datas['address2'];
    }

    // --------------------------------------------------------------------------------------------
    // Accesseurs en lecture.

    public function getId() : int {
        return $this->id;
    }

    public function getZipCode() : string {
        return $this->zip_code;
    }

    public function getAddress() : string {
        return $this->address1;
    }

    public function getAddressComplement() : string {
        if (is_null($this->address2)) {
            return '';
        }
        return $this->address2;
    }

    public function getCity() : string {
        return $this->city;
    }

    public function getDepartment() : string {
        return substr((string) $this->zip_code, 0, 2);
    }
}