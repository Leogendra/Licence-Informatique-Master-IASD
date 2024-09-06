<?php

namespace PH;

define('PH_TEAM_DEFAULT_PROFILE_PICTURE', 'team-default-pp.png');

/**
 * Cette classe est une structure de données permettant de stocker les
 * paramètres d'une équipe.
 */
class Team {
    private int $id;
    private int $level;
    private string $name;
    private string $profile_picture;
    private bool $active;
    private User $captain;
    private array $players;
    private array $contact_informations;
    private array $postulates;

    // ------------------------------------------------------------------------
    // Méthodes de construction statiques.

    /**
     * Construction d'une équipe selon un identifiant
     * 
     * @param  int $team_id L'id de l'équipe à créer
     * @return Team         L'équipe ayant l'id $team_id
     * 
     * @throws \Exception Si une erreur survient lors de la création.
     * 
     * @author Benoît Huftier
     */
    public static function createFromId(int $team_id) : Team {
        global $phdb;

        $datas = $phdb->getTeamFromId($team_id);
    
        if (empty($datas)) {
            throw new \Exception("L'équipe ayant l'identifiant $team_id est introuvable.");
        }

        return self::create($datas);
    }

    /**
     * Construction d'une équipe selon un nom
     * 
     * @param  string $team_name Le nom de l'équipe à créer
     * @return Team              L'équipe ayant le nom $team_name
     * 
     * @throws \Exception Si une erreur survient lors de la création.
     * 
     * @author Benoît Huftier
     */
    public static function createFromName(string $team_name) : Team {
        global $phdb;

        $datas = $phdb->getTeamFromName($team_name);
    
        if (empty($datas)) {
            throw new \Exception("L'équipe ayant le nom $team_name est introuvable.");
        }

        return self::create($datas);
    }

    private static function create(array $datas) : Team {
        global $phdb;

        $players_datas = $phdb->getPlayersForTeam($datas['id']);
        $players_datas = ph_add_roles_to_users_datas($players_datas);

        $datas['players'] = ph_create_multiple_users_with_datas($players_datas);
        $datas['captain'] = $datas['players'][$datas['captain']];
        $datas['location'] = Location::fromRawData(array(
            'id'        => intval($datas['location_id']),
            'city'      => $datas['city'],
            'code'      => $datas['code'],
            'address1'  => $datas['address1'],
            'address2'  => $datas['address2']
        ));
        
        return new Team($datas);
    }

    // ------------------------------------------------------------------------
    // Constructeur.
    
    /**
     * Construction d'une équipe en donnant un tableau de données.
     * Le mieux est d'appeler une méthode statique afin de construire une équipe
     * selon des paramètres spécifiques.
     * 
     * @param array $team_datas Toutes les données de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function __construct(array $team_datas) {
        $this->id              = $team_datas['id'];
        $this->name            = $team_datas['name'];
        $this->level           = $team_datas['level'];
        $this->profile_picture = is_null($team_datas['profile_picture']) ? PH_TEAM_DEFAULT_PROFILE_PICTURE : $team_datas['profile_picture'];
        $this->active          = $team_datas['active'];
        $this->captain         = $team_datas['captain'];
        $this->players         = $team_datas['players'];
        $this->contact_informations = array(
            'email'     => $team_datas['email'],
            'phone'     => $team_datas['phone'],
            'location'  => $team_datas['location']
        );
        $this->postulates = array();
    }

    /**
     * @return int L'id de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * @return string Le nom de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @return int Le niveau de l'équipe (sa force)
     * 
     * @author Benoît Huftier
     */
    public function getLevel() : int {
        return $this->level;
    }

    /**
     * @return string La photo de profil de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function getProfilePicture() : string {
        return ph_get_upload_link($this->profile_picture);
    }

    /**
     * @return bool Si l'équipe a été dissoute ou pas 
     * 
     * @author Benoît Huftier
     */
    public function isActive() : bool {
        return $this->active;
    }

    /**
     * @return User Le capitaine de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function getCaptain() : User {
        return $this->captain;
    }

    /**
     * @return array Tous les joueurs de l'équipe
     * 
     * @author Benoît Huftier
     */
    public function getPlayers() : array {
        return $this->players;
    }

    /**
     * @return array Les informations de contact comme suit :
     *               'email' => string
     *               'phone' => string
     *               'location' => objet Location
     * 
     * @author Benoît Huftier
     */
    public function getContactInformations() : array {
        return $this->contact_informations;
    }

    /**
     * @param  User $player Le joueur qu'il faut vérifier
     * @return bool         Si le joueur appartient à l'équipe
     * 
     * @author Benoît Huftier
     */
    public function hasPlayer(User $player) : bool {
        foreach ($this->players as $p) {
            if ($player->sameUserThan($p)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array Tous les joueurs en attente
     * 
     * @author Benoît Huftier
     */
    public function getPendingPlayers() : array {
        return $this->getPostulatesFromStatus(\Postulate::Pending);
    }

    /**
     * @return array Tous les joueurs refusé
     * 
     * @author Benoît Huftier
     */
    public function getRefusedPlayers() : array {
        return $this->getPostulatesFromStatus(\Postulate::Refused);
    }

    /**
     * @return array Tous les joueurs accepté
     * 
     * @author Benoît Huftier
     */
    public function getAcceptedPlayers() : array {
        return $this->getPostulatesFromStatus(\Postulate::Accepted);
    }

    /**
     * Renvoie le type de postulat du joueur.
     * 
     * @param  User   $player Le joueur dont on veut le type de postulat
     * @return string         Un des types de postulat possible.
     * 
     * @author Benoît Huftier
     */
    public function getPostulateTypeForPlayer(User $player) : string {
        foreach ($this->getPendingPlayers() as $pending) {
            if ($player->sameUserThan($pending['player'])) {
                return \Postulate::Pending;
            }
        }

        foreach ($this->getRefusedPlayers() as $refused) {
            if ($player->sameUserThan($refused['player'])) {
                return \Postulate::Refused;
            }
        }

        foreach ($this->getAcceptedPlayers() as $accepted) {
            if ($player->sameUserThan($accepted['player'])) {
                return \Postulate::Accepted;
            }
        }
        
        return \Postulate::None;
    }

    /**
     * Récupère tous les joueurs qui ont postulés à l'équipe ayant le type demandé.
     * Ce sont tous les derniers postulats de chaque joueur, qu'il soit rejeté,
     * accepté ou en attente.
     * 
     * Chaque sous-tableaux possède en clé les id des joueurs postulant et en valeur
     * un autre sous-tableaux avec deux clés :
     * - player : l'objet User comportant le joueur qui a postulé
     * - postulate_date : Un objet DateTime contenant la date de postulat
     * 
     * @param  string $status Un type de postulat
     * @return array          Tous les joueurs ayant postulés avec le statut $status.
     * 
     * @author Benoît Huftier
     */
    private function getPostulatesFromStatus(string $status) : array {
        global $phdb;

        if (array_key_exists($status, $this->postulates)) {
            return $this->postulates[$status];
        }

        $postulates_datas = $phdb->getPostulatesForTeam($this->id);
        $postulates_datas = ph_add_roles_to_users_datas($postulates_datas);
        $users = ph_create_multiple_users_with_datas($postulates_datas);

        $postulates = array();

        foreach ($postulates_datas as $user_id => $data) {
            if ($status === $data['statut']) {
                $postulates[$user_id] = array(
                    'player' => $users[$user_id],
                    'postulate_date' => new \DateTime($data['postulate_date'])
                );
            }
        }

        $this->postulates[$status] = $postulates;
        return $postulates;
    }
}