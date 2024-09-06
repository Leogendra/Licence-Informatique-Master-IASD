<div class="col-12 col-lg-6">
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-3 col-xl-3 col-xxl-2">
                <img class="rounded" width="100px" src="<?php echo $team->getProfilePicture(); ?>" alt="Photo de profil du joueur <?php echo $team->getName(); ?>">
            </div>
            <div class="col-9 col-xl-9 col-xxl-10">
                <div class="card-body">
                    <h5 class="card-title mb-4"><?php echo $team->getName(); ?></h5>
                    <dl class="row">
                        <dt class="col-4 col-lg-6 col-xxl-5">Capitaine</dt>
                        <dd class="col-8 col-lg-6 col-xxl-7"><?php echo $team->getCaptain()->getName(); ?></dd>

                        <dt class="col-4 col-lg-6 col-xxl-5">Nombre de joueurs</dt>
                        <dd class="col-8 col-lg-6 col-xxl-7"><?php echo count($team->getPlayers()); ?></dd>

                        <dt class="col-4 col-lg-6 col-xxl-5">Niveau</dt>
                        <dd class="col-8 col-lg-6 col-xxl-7"><?php echo $team->getLevel(); ?></dd>

                        <dt class="col-4 col-lg-6 col-xxl-5">Email</dt>
                        <dd class="col-8 col-lg-6 col-xxl-7"><?php echo $team->getContactInformations()['email']; ?></dd>

                        <dt class="col-4 col-lg-6 col-xxl-5">Téléphone</dt>
                        <dd class="col-8 col-lg-6 col-xxl-7"><?php echo $team->getContactInformations()['phone']; ?></dd>
                    </dl>
                    <a type="button" class="btn btn-primary" href="<?php echo ph_get_route_link('team.php', array('id' => $team->getId())); ?>">Voir l'équipe</a>
                </div>
            </div>
        </div>
    </div>
</div>