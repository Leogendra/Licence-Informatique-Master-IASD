<div id="postulate-row-player-<?php echo $player->getId(); ?>" class="d-flex align-items-center my-2">
    <div class="px-1">
        <img class="rounded" width="30px" src="<?php echo $player->getProfilePicture(); ?>" alt="Photo de profil du joueur <?php echo $player->getName(); ?>">
    </div>
    <div class="px-1">
        <?php echo $player->getName(); ?>
    </div>
    <div class="px-1 ms-auto" title="">
        <?php if (!$player->sameUserThan($team->getCaptain())): ?>
            <button type="button" class="btn btn-danger" onclick="ph_eject_player(<?php echo $player->getId(); ?>)">Éjecter de l'équipe</button>
        <?php else: ?>
            <div title="Vous ne pouvez pas vous éjecter vous-même de l'équipe">
                <button type="button" class="btn btn-danger" disabled>Éjecter de l'équipe</button>
            </div>
        <?php endif; ?>
    </div>
</div>