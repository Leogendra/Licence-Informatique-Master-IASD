<?php

namespace PH;

define('PH_USER_DEFAULT_PROFILE_PICTURE', 'user-default-pp.png');

/**
 * Cette classe est une structure de données permettant de stocker les
 * paramètres d'un utilisateur.
 */
class User {
    private int $id;
    private \Core\Permissions $permissions;
    private string $name;
    private string $role;
    private string $email;
    private string $profile_picture;

    // Sépcifique pour les joueurs
    private int $player_id;
    private string $description;

    // ------------------------------------------------------------------------
    // Méthodes de construction statiques.

    /**
     * Construction d'un utilisateur selon un identifiant
     * 
     * @param  int $user_id L'id de l'utilisateur à créer
     * @return User         L'utilisateur ayant l'id $user_id
     * 
     * @throws \Exception Si une erreur survient lors de la création.
     * 
     * @author Benoît Huftier
     */
    public static function createFromId(int $user_id) : User {
        global $phdb;

        $datas = $phdb->getUserFromId($user_id);
        
        if (empty($datas)) {
            throw new \Exception("L'utilisateur avec l'id $user_id est introuvable.");
        }

        $datas = self::addRolesToUserDatas($datas);

        return new User($datas);
    }

    /**
     * Construction d'un utilisateur selon son email
     * 
     * @param  string $user_email L'email de l'utilisateur à créer
     * @return User               L'utilisateur ayant le mail $user_email
     * 
     * @throws \Exception Si une erreur survient lors de la création.
     * 
     * @author Benoît Huftier
     */
    public static function createFromEmail(string $user_email) : User {
        global $phdb;

        $datas = $phdb->getUserFromEmail($user_email);
        
        if (empty($datas)) {
            throw new \Exception("L'utilisateur avec l'email $user_email est introuvable.");
        }

        $datas = self::addRolesToUserDatas($datas);

        return new User($datas);
    }

    /**
     * Construction d'un utilisateur public, qui n'a pas de compte
     * 
     * @return User Un utilisateur sans aucune donnée et avec les permissions Public
     * 
     * @author Benoît Huftier
     */
    public static function createPublicUser() : User {
        $datas = array(
            'id' => 0,
            'name' => 'Invité',
            'email' => '',
            'profile_picture' => null,
            'roles' => array(array('label' => 'Invité'))
        );
        return new User($datas);
    }

    // ------------------------------------------------------------------------
    // Méthodes utilitaires statiques.

    /**
     * Méthode utilitaire permettant d'ajouter les rôles à un tableau de données
     * d'un utilisateur.
     * 
     * Le paramètre doit contenir la clé id avec l'identifiant de l'utilisateur.
     * 
     * @param array $user_datas  Les données de l'utilisateur
     * @return array             $user_datas avec la clé roles ajoutée
     * 
     * @throws \Exception Si les rôles de l'utilisateur ne sont pas trouvés
     * 
     * @author Benoît Huftier.
     */
    private static function addRolesToUserDatas(array $user_datas) : array {
        global $phdb;

        $roles = $phdb->getRolesForUser($user_datas['id']);
        
        if (empty($roles)) {
            throw new \Exception("Impossible de récupérer les rôles de l'utilisateur avec l'id {$user_datas['id']}.");
        }

        $user_datas['roles'] = $roles;

        return $user_datas;
    }

    // ------------------------------------------------------------------------
    // Constructeur.

    /**
     * Construction d'un utilisateur en donnant un tableau de données
     * Le mieux est d'appeler une méthode statique afin de construire un utilisateur
     * selon des paramètres spécifiques.
     * 
     * Le tableau de données doit posséder des clés spécifiques sans lesquelles la
     * méthode renverra un résultat innatendu.
     * 
     * Voici les clés obligatoires :
     * 'id' (int)
     * 'name' (string)
     * 'email' (string)
     * 'profile-picture' (string|null)
     * 'roles' (array)
     *     (array)
     *         'label' (string)
     * 'player_id' (int, seulement si l'utilisateur possède le rôle joueur)
     * 'description' (string, seulement si l'utilisateur possède le rôle joueur)
     * 
     * @param array $user_datas Toutes les données de l'utilisateurs.
     * 
     * @author Benoît Huftier
     */
    public function __construct(array $user_datas) {
        global $phdb;
        
        $this->id = $user_datas['id'];
        $this->name = $user_datas['name'];
        $this->email = $user_datas['email'];
        $this->profile_picture = is_null($user_datas['profile_picture']) ? PH_USER_DEFAULT_PROFILE_PICTURE : $user_datas['profile_picture'];
        $this->role = empty($user_datas['roles']) ? '' : $user_datas['roles'][0]['label'];
        
        $permissions = \Role::None;
        foreach ($user_datas['roles'] as $role) {
            $permissions |= \Role::fromString($role['label']);
        }
        $this->permissions = new \Core\Permissions($permissions);

        if ($this->isPlayer()) {
            $this->player_id = $user_datas['player_id'];
            $this->description = $user_datas['description'];
        }
    }

    // ------------------------------------------------------------------------
    // Accesseurs en lecture.

    /**
     * @return int L'id de l'utilisateur
     * 
     * @author Benoît Huftier
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * Cette méthode permet de savoir ce que l'utilisateur à le droit de faire/voir.
     * 
     * @return \Core\Permissions Les permissions de l'utilisateur
     * 
     * @author Benoît Huftier
     */
    public function getPermissions() : \Core\Permissions {
        return $this->permissions;
    }

    /**
     * @return string Le nom l'utilisateur
     * 
     * @author Benoît Huftier
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @return string L'email de l'utilisateur
     * 
     * @author Benoît Huftier
     */
    public function getEmail() : string {
        return $this->email;
    }

    /**
     * Cette méthode renvoie uniquement le rôle "majoritaire" de l'utilisateur.
     * S'il a plusieurs rôles alors seul le plus important sera renvvoyé.
     * 
     * @return string Le rôle principale de l'utilisateur.
     * 
     * @author Benoît Huftier
     */
    public function getRole() : string {
        return $this->role;
    }

    /**
     * @return string La photo de profil de l'utilisateur
     * 
     * @author Benoît Huftier
     */
    public function getProfilePicture() : string {
        return ph_get_upload_link($this->profile_picture);
    }

    /**
     * @return string La description du joueur
     * 
     * @throws \Exception Si l'utilisateur n'est pas un joueur.
     * 
     * @author Benoît Huftier
     */
    public function getDescription() : string {
        $this->throwIfNotPlayer();
        return $this->description;
    }

    /**
     * @return string L'identifiant du joueur
     * 
     * @throws \Exception Si l'utilisateur n'est pas un joueur.
     * 
     * @author Benoît Huftier
     */
    public function getPlayerId() : int {
        $this->throwIfNotPlayer();
        return $this->player_id;
    }

    /**
     * @return bool Vrai si l'utilisateur est un joueur
     * 
     * @author Benoît Huftier
     */
    public function isPlayer() : bool {
        return $this->permissions->hasFlag(\Role::Player);
    }

    /**
     * @return bool Vrai si l'utilisateur est un admin
     * 
     * @author Benoît Huftier
     */
    public function isAdmin() : bool {
        return $this->permissions->hasFlag(\Role::Administrator);
    }

    /**
     * @param User $other Un autre utilisateur
     * @return bool       Vrai si les utilisateurs sont les mêmes
     * 
     * @author Benoît Huftier
     */
    public function sameUserThan(User $other) : bool {
        return $this->id === $other->id;
    }

    private function throwIfNotPlayer() : void {
        if (!$this->isPlayer()) {
            throw new \Exception('Cet utilisateur n\'est pas un joueur');
        }
    }
}