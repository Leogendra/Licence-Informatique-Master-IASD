<?php
$player = $postulate['player'];
$date = $postulate['postulate_date'];
?>
<div id="postulate-row-player-<?php echo $player->getId(); ?>" class="d-flex align-items-center my-2">
    <div class="px-1">
        <img class="rounded" width="30px" src="<?php echo $player->getProfilePicture(); ?>" alt="Photo de profil du joueur <?php echo $player->getName(); ?>">
    </div>
    <div class="px-1">
        <?php echo $player->getName(); ?> a postulé le <?php echo $date->format('d/m/Y'); ?> à <?php echo $date->format('H:i'); ?>
    </div>
    <div class="ms-auto d-flex align-items-center">
        <div class="px-1" title="">
            <button type="button" class="btn btn-warning" onclick="ph_accept_player(<?php echo $player->getId(); ?>)">Accepter</button>
        </div>
        <div class="px-1" title="">
            <button type="button" class="btn btn-danger" onclick="ph_refuse_player(<?php echo $player->getId(); ?>)">Refuser</button>
        </div>
    </div>
</div>