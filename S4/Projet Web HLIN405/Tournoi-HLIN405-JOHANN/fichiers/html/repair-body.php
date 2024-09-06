<h1>Page de réparation de la base de données</h1>
<?php

if (array_key_exists('force', $_GET)) :
    try {
        $filename = ph_db_force_repair();
        $render = "La base de données a bien été réparée! Toutes les données ont été enregistrées ici : $filename, et sont prête à être réinsérées.";
    }
    catch (Exception $e) {
        if (true === DEVELOPMENT) {
            $render = $e->getMessage();
        }
        else {
            $render = 'Oops! Une erreur a été détectée lors de la réparation de la base de données. Veuillez contacter un développeur sur le champ!';
        }
    }
?>
<p><?php echo $render; ?></p>
<?php
else :
?>
<p>Il y a eu un problème lors de la mise à jour de la base de données. Le schéma a été changé et il n'a pas pu être mis à jour sur le serveur actuel, <?php echo $_SERVER['SERVER_ADDR']; ?><br />
Voulez-vous tout de même forcer la mise à jour ? <br />
<i>Si vous la forcez, les données actuelles seront sauvegardées dans un fichier.</i></p>
<form method="GET">
    <button name="force" class="btn btn-primary">Forcer</button>
</form>
<?php 
endif;