<?php 

/**
 * Enumération des différents status d'un tournoi.
 */
abstract class Status {
    const OnGoing = 1 << 0;
    const Forthcoming = 1 << 1;
    const Finished = 1 << 2;
    const PreRegistrations = 1 << 3;

    private static array $string_values = array(
        self::OnGoing => 'En cours',
        self::Forthcoming => 'À venir',
        self::Finished => 'Terminé',
        self::PreRegistrations => 'Phase de pré-inscriptions',
    );

    /**
     * Permet de fabriquer un status en fonction de la date de début & de fin
     * 
     * @param  string $end_inscription  La date de fin des inscriptions d'un tournoi, formattée correctement.
     * @param  string $start_date       La date de début d'un tournoi, formattée correctement.
     * @param  int    $duration         La durée du tournoi.
     * @return int                      Le Status correspondant.
     * @throws \Exception         Si la date ne peut pas être construite.
     * 
     * @author Johann Rosain
     */
    static public function fromDate(string $end_inscription, string $start_date, int $duration) : int {
        if (empty($start_date)) {
            throw new Exception('La date de début ne doit pas être vide.');
        }

        $end_inscription_date = new DateTime($end_inscription);
        $real_starting_date = new DateTime($start_date);
        $real_ending_date = new DateTime($start_date);
        $real_ending_date->add(new DateInterval('P' . $duration . 'D'));

        $now = new DateTime();

        $status = -1;

        if ($now < $end_inscription_date) {
            $status = self::PreRegistrations;
        }
        else if ($now < $real_starting_date) {
            $status = self::Forthcoming;
        }
        else if ($now > $real_ending_date) {
            $status = self::Finished;
        }
        else {
            $status = self::OnGoing;
        }

        return $status;
    }

    /**
     * Permet de fabriquer un nom en français à partir du Status donné. 
     * 
     * @param  int    $status Le status à convertir.
     * @return string         Le nom du status en français.
     * @throws \Exception     Si le status n'est pas trouvé dans le tableau de valeur
     * 
     * @author Johann Rosain
     */
    static public function toString(int $status) : string {
        if (false === array_key_exists($status, self::$string_values)) {
            throw new \Exception("L'énumération ne possède pas d'élément de valeur $status.");
        }
        return self::$string_values[$status];
    }

    /**
     * Permet de fabriquer un status en fonction du nom donné en français
     * 
     * @param  string $name Le nom
     * @return int          Le Status correspondant au nom
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
}