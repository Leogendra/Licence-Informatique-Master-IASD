<?php

/**
 * Enumération des différents rôles possibles d'un utilisateur sur le site.
 */
abstract class Role {
    const None = 0;
    const Administrator = 1 << 0;
    const Manager = 1 << 1;
    const Player = 1 << 2;
    const Public = 1 << 3;
    const Connected = Role::Administrator | Role::Manager | Role::Player;
    const All = Role::Connected | Role::Public;

    private static string $admin = 'Administrateur';
    private static string $manager = 'Gestionnaire de tournois';
    private static string $player = 'Joueur';

    /**
     * Permet de fabriquer un rôle en fonction du nom donné en français dans la BDD
     * 
     * @param string $name Le nom dans la BDD
     * @return int         Le Role correspondant au nom
     * 
     * @author Benoît Huftier
     */
    static public function fromString(string $name) : int {
        return match($name) {
            self::$admin   => Role::Administrator,
            self::$manager => Role::Manager,
            self::$player  => Role::Player,
            default        => Role::Public
        };
    }

    /**
     * Permet de fabriquer un nom en français à partir du Role donné. 
     * 
     * @param  int $role Le rôle à convertir.
     * @return string    Le nom du rôle en français.
     * 
     * @author Johann Rosain
     */
    static public function toString(int $role) : string {
        return match($role) {
            Role::Administrator => self::$admin,
            Role::Manager       => self::$manager,
            Role::Player        => self::$player,
            default             => '',
        };
    }

    /**
     * Permet de récupérer le label de tous les rôles d'un utilisateur depuis ses permissions.
     * 
     * @param  \Core\Permissions $perms Toutes les permissions à convertir.
     * @return array                    Un tableau avec le label de tous les rôles de l'utilisateur.
     * 
     * @author Johann Rosain
     */
    static public function toArray(\Core\Permissions $perms) : array {
        $result = array();

        if ($perms->hasFlag(Role::Administrator)) {
            $result[] = self::$admin;
        }
        if ($perms->hasFlag(Role::Manager)) {
            $result[] = self::$manager;
        }
        if ($perms->hasFlag(Role::Player)) {
            $result[] = self::$player;
        }

        return $result;
    }
}