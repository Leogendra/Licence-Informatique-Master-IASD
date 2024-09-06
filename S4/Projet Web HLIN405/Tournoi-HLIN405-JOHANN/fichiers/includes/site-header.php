<?php 
$get_array = isset($_SERVER['REDIRECT_URL']) && false === strstr($_SERVER['REDIRECT_URL'], 'errors') ? array('page' => $_SERVER['REDIRECT_URL']) : array('page' => ROOT);
?>

<header>
    <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid mx-2 py-2">
            <div class="col-2-md-1 ">
                <a class="navbar-brand" href="<?php echo ph_get_route_link('index.php'); ?>">Bracket Creator</a>
            </div>
            <div class="col">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Equipes
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="<?php echo ph_get_route_link('teams.php'); ?>">Voir les équipes</a></li>
                                    <li><a class="dropdown-item player-admin-only" href="<?php echo ph_get_route_link('team-creation.php'); ?>">Créer une équipe</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Tournois
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="<?php echo ph_get_route_link('tournaments.php'); ?>">Voir les tournois</a></li>
                                    <li><a class="dropdown-item player-only" href="<?php echo ph_get_route_link('my-tournaments.php'); ?>">Voir mes tournois</a></li>
                                    <li><a class="dropdown-item admin-only" href="<?php echo ph_get_route_link('tournament-creation.php'); ?>">Créer un tournoi</a></li>
                                    <li><a class="dropdown-item manager-only" href="<?php echo ph_get_route_link('manage-tournaments.php'); ?>">Gérer mes tournois</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link admin-only" href="<?php echo ph_get_route_link('role-management.php'); ?>">Rôles</a>
                            </li>
                        </ul>
                    </div>
            </div>
            <div class="col-2-md-1 px-2 public">
                <a class="btn btn-primary" type="button" href="<?php echo ph_get_route_link('login.php', $get_array); ?>">Connexion</a>
            </div>
            <a class="lead col-2-md-1 px-2 disconnection link-dark" href="<?php echo ph_get_route_link('profile.php'); ?>"><?php echo ph_get_user()->getName(); ?></a>
            <div class="col-2-md-1 px-2 disconnection">
                <a class="btn btn-primary" type="button" href="<?php echo ph_get_route_link('validation/disconnect.php', $get_array) ?>">Déconnexion</a>
            </div>
            <div class="col-2-md-1 px-2 public">
                <a class="btn btn-outline-primary" type="button" href="<?php echo ph_get_route_link('register.php', $get_array); ?>">Inscription</a>
            </div>
        </div>
    </nav>
</header>
