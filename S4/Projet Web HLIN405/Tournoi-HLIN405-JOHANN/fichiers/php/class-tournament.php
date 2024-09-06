<?php 

namespace PH;

class Tournament {
    private int $id;
    private string $name;
    private \DateTime $starting_date;
    private \DateTime $end_inscription;
    private int $duration;
    private int $type;
    private Location $location;
    private int $status;
    private User $manager;
    private array $postulates;
    private array $matches;

    // --------------------------------------------------------------------------------------------
    // Méthodes de construction statiques.

    /**
     * Crée un Tournoi à partir de l'id demandé. 
     * 
     * @param  int        $id L'id depuis lequel récupérer les informations dans la base de données 
     * @return Tournament     Le tournoi construit grâce à l'id donné
     * @throws \Exception     Si l'id n'existe pas dans la base de données.
     * 
     * @author Johann Rosain
     */
    public static function fromId(int $id) : Tournament {
        global $phdb;

        $data = $phdb->getTournament($id);

        if (empty($data)) {
            throw new \Exception('L\'id fourni n\'existe pas dans la base de données.');
        }
        
        return self::fromRawData(array(
            'id' => $id,
            'name' => $data['tournament_name'],
            'starting-date' => $data['start_date'],
            'end-inscription' => $data['end_inscription'],
            'duration' => intval($data['duration']), 
            'type' => $data['type'],
            'location' => array(
                'id' => intval($data['location_id']),
                'city' => $data['city_name'],
                'code' => $data['code'],
                'address1' => $data['address1'],
                'address2' => $data['address2']
            ),
            'manager' => intval($data['manager'])
        ));
    }

    /**
     * Crée un tournoi à partir de données brutes.
     * Le tableau de données brutes doit être paramétré comme ceci :
     * array(
     *     'id' => int,
     *     'name' => string,
     *     'starting-date' => string,
     *     'duration' => int,
     *     'type' => string,
     *     'location' => array,
     *     'manager' => int
     * );
     * Le tableau du lieu doit correspondre au tableau des données brutes pour construire 
     * une Location.
     * 
     * @param  array      $raw_data Les données brutes nécessaires à construire un tournoi.
     * @return Tournament           Le tournoi construit grâce à ces données.
     * @throws \Exception           Si une des clés nécessaires est manquante.
     * @throws \Exception           Si une des valeurs n'est pas du type attendu.
     * 
     * @author Johann Rosain
     * @see    PH\Location
     */
    public static function fromRawData(array $raw_data) : Tournament {
        if (!array_key_exists('id', $raw_data) ||
            !array_key_exists('name', $raw_data) ||
            !array_key_exists('starting-date', $raw_data) ||
            !array_key_exists('end-inscription', $raw_data) ||
            !array_key_exists('duration', $raw_data) ||
            !array_key_exists('type', $raw_data) ||
            !array_key_exists('location', $raw_data) ||
            !array_key_exists('manager', $raw_data)) {
            throw new \Exception('Le tableau de données brutes n\'est pas adapté pour construire un objet de type Tournoi.');
        }

        if (!is_int($raw_data['id']) ||
            !is_string($raw_data['name']) ||
            !is_string($raw_data['starting-date']) ||
            !is_string($raw_data['end-inscription']) ||
            !is_int($raw_data['duration']) ||
            !is_string($raw_data['type']) ||
            !is_array($raw_data['location']) ||
            !is_int($raw_data['manager'])) {
            throw new \Exception('Le tableau de données brutes contient des valeurs de mauvais type.');
        }

        return new Tournament(
            $raw_data['id'],
            $raw_data['name'],
            new \DateTime($raw_data['starting-date']),
            new \DateTime($raw_data['end-inscription']),
            $raw_data['duration'],
            \Type::fromString($raw_data['type']),
            Location::fromRawData($raw_data['location']),
            User::createFromId($raw_data['manager'])
        );
    }

    // --------------------------------------------------------------------------------------------
    // Constructeur privé.

    private function __construct(int $id, string $name, \DateTime $date, \DateTime $end_inscription, int $duration, int $type, Location $location, User $manager) {
        $this->id = $id;
        $this->name = $name;
        $this->starting_date = $date;
        $this->end_inscription = $end_inscription;
        $this->duration = $duration;
        $this->type = $type;
        $this->location = $location;
        $this->status = \Status::fromDate($end_inscription->format('Y-m-d'), $date->format('Y-m-d'), $duration);
        $this->manager = $manager;

        // Récupération de toutes les équipes qui veulent s'inscrire. On utilise ce tableau pour ensuite 
        // instancier tout ce qui est relatif aux équipes. En effet, il y a 3 structures de données à 
        // instancier avec toutes les équipes du tournoi :
        //   - Les équipes qui se préinscrivent P
        //   - Les équipes inscrites I (et I est inclus dans P)
        //   - Les matches M (M inclus dans I inclus dans P)
        // Ainsi, toutes les structures peuvent être construites grâce aux id récupérés par la préinscription 
        // des équipes.
        global $phdb;

        try {
            $teams_postulate = $phdb->getAllPreinscriptions($id);
            $this->postulates = array();
            foreach ($teams_postulate as $id => $data) {
                $team = Team::createFromId($id);
                $this->postulates[$id] = array(
                    'team' => $team,
                    'date' => new \DateTime($data['date']),
                    'status' => $data['status']
                );
            }

            // Arbre des matches. Ce qu'il reste à faire.
        }
        catch (\PDOException $e) {
            throw new \Exception('Un problème est survenu lors de la communication avec la base de données.');
        }
    }

    // --------------------------------------------------------------------------------------------
    // Accesseurs en lecture.

    public function getId() : int {
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getFormattedEndInscriptions(string $format) : string {
        return $this->end_inscription->format($format);
    }

    public function getFormattedStartingDate(string $format) : string {
        return $this->starting_date->format($format);
    }

    public function getFormattedEndingDate(string $format) : string {
        $ending_date = $this->starting_date;
        $ending_date->add(new \DateInterval('P' . ($this->duration - 1) . 'D'));
        return $ending_date->format($format);
    }

    public function getDuration() : int {
        return $this->duration;
    }

    public function getType() : string {
        return \Type::toString($this->type);
    }

    public function getLocation() : Location {
        return $this->location;
    }

    public function getStatus() : int {
        return $this->status;
    }

    public function getStatusString() : string {
        return \Status::toString($this->status);
    }

    public function getManager() : User {
        return $this->manager;
    }

    public function getPendingTeams() : array {
        return $this->getTeamFromStatus(\Postulate::Pending);
    }

    public function getRefusedTeams() : array {
        return $this->getTeamFromStatus(\Postulate::Refused);
    }

    public function getAcceptedTeams() : array {
        return $this->getTeamFromStatus(\Postulate::Accepted);
    }

    public function getRegisteredTeams() : array {
        $accepted_teams = $this->getAcceptedTeams();
        $registered_teams = array();

        foreach ($accepted_teams as $team) {
            $registered_teams[$team['team']->getId()] = $team['team'];
        }

        return $registered_teams;
    }

    public function getPostulateStatusOfTeam(Team $team) : string {
        foreach ($this->postulates as $postulate) {
            if ($postulate['team']->getId() === $team->getId()) {
                return $postulate['status'];
            }
        }
        return \Postulate::None;
    }

    // --------------------------------------------------------------------------------------------
    // Méthodes privées

    private function getTeamFromStatus(string $status) : array {
        $teams = array();
        foreach ($this->postulates as $postulate) {
            if ($status === $postulate['status']) {
                $teams[] = array(
                    'team' => $postulate['team'],
                    'date' => $postulate['date']
                );
            }
        }
        return $teams;
    }
}