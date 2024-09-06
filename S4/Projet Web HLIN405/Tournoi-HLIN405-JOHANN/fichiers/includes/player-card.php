<div class="col-12 col-md-6">
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-3 col-xl-3 col-xxl-2">
                <img class="rounded" width="100px" src="<?php echo $player->getProfilePicture(); ?>" alt="Photo de profil du joueur <?php echo $player->getName(); ?>">
            </div>
            <div class="col-9 col-xl-9 col-xxl-10">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $player->getName(); ?></h5>
                    <p class="card-text"><?php echo $player->getDescription(); ?></p>
                </div>
            </div>
        </div>
        <div class="position-absolute" style="top: 5px; right: 5px">
            <?php if ($team->getCaptain()->sameUserThan($player)) : ?>
                <span class="badge bg-success" >Capitaine</span>
            <?php endif; ?>
            <?php if (ph_get_user()->sameUserThan($player)) : ?>
                <span class="badge bg-danger" >Vous</span>
            <?php endif; ?>
        </div>
    </div>
</div>