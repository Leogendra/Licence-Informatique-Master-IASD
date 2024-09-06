<?php 
  session_start();
  if(isset($_SESSION['login']) && isset($_SESSION['mdp'])) {
    $login = $_SESSION['login'];

//$_SESSION['estValide'] = 0;
//$_SESSION['phase'] = 1;

?>

<!DOCTYPE html>
<html>

  <head>
    <title>MesTournois.com</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CDN CSS BOOTSTRAP-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl"
     crossorigin="anonymous">
    <!-- CDN ICONS BOOTSTRAP-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <!-- CDN JS BOOTSTRAP-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
     crossorigin="anonymous">

    </script>
  </head>

  <style>
    body {position: relative; background-color: rgb(0, 0, 0); padding-bottom: 50px;}
    h1 {color: white;}

    #rencontres {padding-top:30px; background-color: rgb(0, 0, 0);}
    
    input[type="text"]:disabled { background-color: rgb(255,255,255,0.1); color: #fff;}
    input[type="date"]:disabled { background-color: rgb(255,255,255,0.1); color: #fff;}
    input[type="textarea"]:disabled { background-color: rgb(255,255,255,0.1); color: #fff;}
    input[type="number"]:disabled { background-color: rgb(255,255,255,0.1); color: #fff;}

    .form-control{ background-color:rgba(255,255,255,0.1); color: white; }
  </style>

  <body data-spy="scroll" data-target=".navbar" data-offset="50">

  <!-- NAVIGATION -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <div class="d-flex justify-content-start">
          <a class="navbar-brand" href="indexlogin.php"><i class="bi bi-house-fill"></i> MesTournois.com </a>
            <div class="btn-group">
              <a href="indexlogin.php" class="btn btn-outline-success"><i class="bi bi-person-circle"></i> Admin</a>
              <a href="logout.php" class="btn btn-outline-success"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
            </div>
        </div>
      </div>
    </nav>  

  <!-- RENCONTRES -->

  <?php
  include('connexion.php');

  //Permettre affichage de la date en français
  setlocale(LC_TIME, 'fr_FR.utf8','fra');

  //Variables de temps pour affichage de la màj
  $dateAuj = date('Y-m-d H:i:s');
  $dateMaj = strftime("%d %b à %X", strtotime($dateAuj));

  //Récupérer l'entièreté de la table Matchs juste pour affecter valeur à $numTour
  //$nbTotalMatchs = $bdd->query("SELECT * FROM Matchs WHERE Id_Tournoi = (SELECT Id_Tournoi FROM Tournois WHERE Id_Evenement = (SELECT Id_Evenement FROM Evenements WHERE Gestionnaire = {$_SESSION['login']}))")->fetchAll();
  $nbTotalMatchs = $bdd->query("SELECT * FROM Matchs WHERE Id_Tournoi = {$_SESSION['monIdT']}")->fetchAll();
  $nTotalMa = count($nbTotalMatchs);//Je compte mon nombre de Matchs total

  $maxPhase = $bdd->query("SELECT Phase FROM Matchs WHERE Id_Tournoi = {$_SESSION['monIdT']} ORDER BY Phase DESC")->fetch();

  //Récupération de la table des équipes
  $Equ = $bdd->query("SELECT * FROM Equipes WHERE Id_Tournois = {$_SESSION['monIdT']}");
  $TabEqu = $Equ->fetchAll();
  $nEq = count($TabEqu);

  if(isset($_POST['tourSuivant'])){
    //$numTour+=1;
    $_SESSION['estValide'] = 1;
  }

  $cptMatchs = 0;
  foreach ($nbTotalMatchs as $unMatch){
    if(($unMatch['Score_A']==0)&&($unMatch['Score_B']==0)){
      $cptMatchs+=1;
    }
  }

  if(($nTotalMa == 0)||($nTotalMa < ($nEq/2))){$numTour = 1;}
  elseif($nTotalMa == ($nEq/2)){
    if(($cptMatchs == 0)&&($_SESSION['estValide']==1)){$numTour = $maxPhase[0]+1;}
    else{$numTour = 1;}
  }
  elseif($nTotalMa > ($nEq/2)){
    if($_SESSION['estValide'] == 1){
      $numTour = $maxPhase[0]+1;
    }
    else{
      $numTour = $maxPhase[0];
    }
  }
  
  //$TabEqu = $bdd->query("SELECT * FROM Equipes WHERE Id_Equipe IN (SELECT Id_Equipe_A FROM Matchs WHERE Phase = {$numTour}-1 AND Score_A > Score_B) OR Id_Equipe IN(SELECT Id_Equipe_B FROM Matchs WHERE Phase = {$numTour}-1 AND Score_B > Score_A)");

  //Récupération de la table des matchs
  $matchs = $bdd->query("SELECT * FROM Matchs WHERE Id_Tournoi = {$_SESSION['monIdT']} AND Phase = {$numTour}");
  $mesMatchs = $matchs->fetchAll();

  $nMa = count($mesMatchs);

  $passage = 0;
  $enManuel = 1;

  if(($nTotalMa == $nEq-1)&&($_SESSION['estValide'] == 1)){?>
  <div id="rencontres" class="container-fluid">
  <h1 id="rencontres" class="text-success"><i>RENCONTRES </i> <i class="bi bi-people"></i> <i>TERMIN&Eacute;</i></h1>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <div class="col">
        <div class="card text-white bg-dark border-success">
          <div class="card-body">
            <h5 class="card-title">Tournoi complété</h5>
            <p class="card-text">Ce tournoi a déjà été entièrement complété. </p>   
            <form method="post" action="indexlogin.php">          
              <button type="submit" class="btn btn-success" name="retourAccueil" id="retourAccueil"><i class="bi bi-check-circle"></i> Page d'accueil</button>
            </form>
          </div><!--Card-Body-->          
        </div><!--Card-->
      </div>
    </div>
  </div>
  <?php } else{ ?>

    <div id="rencontres" class="container-fluid">
      <h1 id="rencontres" class="text-primary"><i>RENCONTRES</i> <i class="bi bi-people"></i> <i>TOUR <?=$numTour?></i></h1>

      <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php if($nMa != (($nEq)/2)){?> <!--Si tous les matchs n'ont pas encore saisis-->
        <div class="col">
          <div class="card text-white bg-dark border-primary">

            <!-- CREATION DES MATCHS-->
            
              <div class="card-header">
                <span class="badge rounded-pill bg-primary"><i class="bi bi-hourglass-split"></i> En cours...</span>
              </div>
              
              <div class="card-body">
                <!-- Boutons pour savoir si inscription auto ou manuelle -->
                <form method="post" action="">
                  <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="Remplissage" id="RemplAuto" value="rA" onclick="this.form.submit();" <?php if($_POST['Remplissage'] == 'rA'){echo 'checked';}?>>
                    <label class="btn btn-outline-primary" for="RemplAuto">Remplissage automatique</label>

                    <input type="radio" class="btn-check" name="Remplissage" id="RemplMan" value="rM" onclick="this.form.submit();" <?php if($_POST['Remplissage'] == 'rM'){echo 'checked';}?>>
                    <label class="btn btn-outline-primary" for="RemplMan">Remplissage manuel</label>
                  </div>
                </form>
                <!-- Fin choix bouton -->

                <form method="post" action="rencontresverif.php">
                <?php if($_POST['Remplissage'] == 'rM'){$_SESSION['Rempl'] = 'rM';?>
                
                <div class="row">
                  <div class="col-md-6 my-1">
                    <label class="form-label">&Eacute;quipe 1</label>
                    <select class="form-select bg-dark text-white" name="Equ1" aria-label="Default select example" <?php if($nMa == (($nEq)/2)){echo 'disabled';}?> required>
                      <option selected>Choisir...</option>
                      <!-- Récupération de toutes les équipes pour les mettre en choix -->
                      <?php 
                      if($numTour == 1){
                        foreach($TabEqu as $monE){
                          $affEq = 0;
                          foreach ($mesMatchs as $unMatch) {
                            if(($monE['Id_Equipe']!=$unMatch['Id_Equipe_A'])&&($monE['Id_Equipe']!=$unMatch['Id_Equipe_B'])){
                              $affEq += 1;
                            }
                          }/*2e foreach*/
                          if($affEq == $nMa){
                          ?>
                        <option value="<?=$monE['Id_Equipe'];?>"> <?= $monE['Nom_Equipe'].' – Niv.'.$monE['Niveau_Equipe']; ?></option>
                      <?php }/*if précédent*/}/*1e foreach*/}/*le if*/
                      else{
                        $eqGagnantes = $bdd->query("SELECT * FROM Equipes WHERE Id_Tournoi = {$_SESSION['monIdT']} AND Id_Equipe IN (SELECT Id_Equipe_A FROM Matchs WHERE Phase = {$numTour}-1 AND Score_A > Score_B) OR Id_Equipe IN(SELECT Id_Equipe_B FROM Matchs WHERE Phase = {$numTour}-1 AND Score_B > Score_A)");
                        foreach($eqGagnantes as $monE){
                          $affEq = 0;
                          foreach ($mesMatchs as $unMatch) {
                            if(($monE['Id_Equipe']!=$unMatch['Id_Equipe_A'])&&($monE['Id_Equipe']!=$unMatch['Id_Equipe_B'])){
                              $affEq += 1;
                            }
                          }/*2e foreach*/
                          if($affEq == $nMa){
                          ?>
                        <option value="<?=$monE['Id_Equipe'];?>"> <?= $monE['Nom_Equipe'].' – Niv.'.$monE['Niveau_Equipe']; ?></option>
                      <?php }/*if précédent*/}/*1e foreach*/}/*else*/?>
                    </select>
                  </div><!-- div Equipe 1 -->

                  <div class="col-md-6 my-1">
                    <label class="form-label">&Eacute;quipe 2</label>
                    <select class="form-select bg-dark text-white" name="Equ2" aria-label="Default select example" <?php if($nMa == (($nEq)/2)){echo 'disabled';}?> required>
                      <option selected>Choisir...</option>
                      <!-- Récupération de toutes les équipes pour les mettre en choix -->
                      <?php 
                      if($numTour == 1){
                      foreach($TabEqu as $monE){
                        $affEq = 0;
                        foreach ($mesMatchs as $unMatch) {
                          if(($monE['Id_Equipe']!=$unMatch['Id_Equipe_A'])&&($monE['Id_Equipe']!=$unMatch['Id_Equipe_B'])){
                            $affEq += 1;
                          }
                        }
                        if($affEq == $nMa){?>
                      <option value="<?=$monE['Id_Equipe'];?>"> <?= $monE['Nom_Equipe'].' – Niv.'.$monE['Niveau_Equipe']; ?></option>
                      <?php }}}
                      else{
                        $eqGagnantes = $bdd->query("SELECT * FROM Equipes WHERE Id_Tournoi = {$_SESSION['monIdT']}AND Id_Equipe IN (SELECT Id_Equipe_A FROM Matchs WHERE Phase = {$numTour}-1 AND Score_A > Score_B) OR Id_Equipe IN(SELECT Id_Equipe_B FROM Matchs WHERE Phase = {$numTour}-1 AND Score_B > Score_A)");
                        foreach($eqGagnantes as $monE){
                          $affEq = 0;
                          foreach ($mesMatchs as $unMatch) {
                            if(($monE['Id_Equipe']!=$unMatch['Id_Equipe_A'])&&($monE['Id_Equipe']!=$unMatch['Id_Equipe_B'])){
                              $affEq += 1;
                            }
                          }/*2e foreach*/
                          if($affEq == $nMa){
                          ?>
                        <option value="<?=$monE['Id_Equipe'];?>"> <?= $monE['Nom_Equipe'].' – Niv.'.$monE['Niveau_Equipe']; ?></option>
                      <?php }/*if précédent*/}/*1e foreach*/}/*else*/?>?>
                    </select>
                  </div><!-- div Equipe 2 -->
                </div><!-- div row -->
                
                <?php } elseif($_POST['Remplissage'] == 'rA'){$_SESSION['Rempl'] = 'rA';}?> 

              </div><!-- card-body -->
              
              <div class="card-footer"> 
                <button type="submit" class="btn btn-primary" name="envoyerMatchs" <?php if($nMa == (($nEq)/2)){echo 'disabled';}?>><i class="bi bi-arrow-bar-up"></i> Envoyer</button> 
              </div><!-- footer -->

            </form><!-- formulaire de création des matchs -->
  
          </div><!-- card text-white...-->
        </div><!-- col -->

      <?php } elseif($nMa == (($nEq)/2)){?><!--Si tous les matchs ont été saisis-->
        <div class="col col-md-4">
          <div class="card text-white bg-dark border-primary">

            <div class="card-header">
                <span class="badge rounded-pill bg-primary"><i class="bi bi-check-circle"></i> Fini</span>
            </div>
            
            <div class="card-body">
              <h5 class="card-title">Rencontres Saisies</h5>
              <p>Toutes les rencontres ont été saisies. <br>Vous pouvez valider pour passer à la saisie des scores.</p>
            </div><!-- card-body -->
            
          </div><!--card text-white-->
        </div><!--col-->

      <?php }?>

        <!-- AFFICHAGE DES MATCHS-->

        <?php if($nMa != 0){?> <!--Si il existe au moins 1 match-->
        <div class="col-xl-4">
          <div class="card text-white bg-dark border-primary">
            
            <div class="card-header">
              <span class="badge rounded-pill bg-primary"><i class="bi bi-record-circle"></i> Tour <?=$numTour?></span>
              <span class="badge rounded-pill bg-primary"><i class="bi bi-check-circle"></i> Inscris</span>
            </div>
            
            <div class="card-body">

              <?php 
              $cpt = 0;

                foreach ($mesMatchs as $matchCree) {
                  $cpt += 1;

                  //Contenu des tables des deux équipes pour chaque match
                  $nomE_A = $bdd->query("SELECT Nom_Equipe FROM Equipes WHERE Id_Equipe = {$matchCree['Id_Equipe_A']}");
                  $nomE_B = $bdd->query("SELECT Nom_Equipe FROM Equipes WHERE Id_Equipe = {$matchCree['Id_Equipe_B']}");

                  //Varibales des noms des deux équipes
                  $E_A = $nomE_A->fetch();
                  $E_B = $nomE_B->fetch();

              ?>
              <form method="post" action="rencontresverif.php">
                <div class="row">
                <div class="col-xl-8 my-1">
                  <h5><?= $E_A['Nom_Equipe'].' – '.$E_B['Nom_Equipe']; ?></h5>
                </div>
                <div class="col-xl-4 my-1">
                  <button type="submit" class="btn btn-primary" name="Reinit_<?=$matchCree['Id_Match'];?>" <?php if($_SESSION['estValide'] == 1){echo 'disabled';}?>><i class="bi bi-bootstrap-reboot"></i> Réinitialiser </button>
                </div>
              </div>
              </form>

              <?php if($cpt != $nMa){echo '<hr>';}/*trait entre les matchs*/}/*fin foreach*/?>
            </div><!-- card-body -->
            <div class="card-footer">
              <form method="post" action="rencontresverif.php">
                <button type="submit" class="btn btn-primary" name="validerMatchs" <?php if($_SESSION['estValide'] == 2){echo 'disabled';}?>><i class="bi bi-check-circle"></i> Valider</button>
              </form>
            </div><!-- card-footer -->    
          </div><!-- card text-white... -->
        </div><!-- col-xl-4 -->
      </div><!-- row row-cols... -->
    </div><!-- container-fluid -->

    <!-- SCORES A SAISIR -->

    <?php }

    if ($_SESSION['estValide'] == 2){

    //Condition pour création du bloc "Scores à saisir" 
    foreach ($mesMatchs as $matchTrouve) {

      //Condition des scores de match à saisir
      if(($matchTrouve['Score_A'] == 0)&&($matchTrouve['Score_B'] == 0)){
        $passage = 1;

    ?>

      <div id="rencontres" class="container-fluid">
        <h1 id="rencontres" class="text-warning"><i>SCORES &Agrave; SAISIR</i> <i class="bi bi-input-cursor-text"></i></h1>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 g-4">
          <?php break;}
          else{
            $passage = 0;
          }}/* fin de la condition */

          //Afficher chaque Match un à un
          foreach ($mesMatchs as $matchTrouve) {

            $idMT = $matchTrouve['Id_Match'];

            //Condition des scores de match à saisir
            if (($matchTrouve['Score_A']==0)&&($matchTrouve['Score_B']==0)) {

            //Contenu des tables des deux équipes pour chaque match
            $nomE_A = $bdd->query("SELECT Nom_Equipe FROM Equipes WHERE Id_Equipe = {$matchTrouve['Id_Equipe_A']}");
            $nomE_B = $bdd->query("SELECT Nom_Equipe FROM Equipes WHERE Id_Equipe = {$matchTrouve['Id_Equipe_B']}");

            //Varibales des noms des deux équipes
            $E_A = $nomE_A->fetch();
            $E_B = $nomE_B->fetch();
            ?>

            <form method="post" action="rencontresverif.php" autocomplete="off">

              <!-- Création de chaque card par match -->
              <div class="col">
                <div class="card text-white bg-dark border-warning">
                  
                  <div class="card-header"><span class="badge rounded-pill bg-warning text-dark"><i class="bi bi-record-circle"></i> <?= 'Tour '.$matchTrouve['Phase']; ?></span></div>
                  
                  <div class="card-body">
                    <p><i class="bi bi-exclamation-triangle"></i> Aucune saisie de match nul est autorisée.</p>
                    <div class="row">
                      <div class="col">
                        <label for="scoreA" class="form-label"><?php echo $E_A['Nom_Equipe']; ?></label>
                        <input type="number" class="form-control bg-dark text-white" placeholder="Score" name="scoreA_<?=$idMT;?>" id="scoreA" min="0" max="1000" required>
                      </div>
                      <div class="col">
                        <label for="scoreB" class="form-label"><?php echo $E_B['Nom_Equipe']; ?></label>
                        <input type="number" class="form-control bg-dark text-white" placeholder="Score" name="scoreB_<?=$idMT;?>" id="scoreB" min="0" max="1000" required>
                      </div>
                    </div><!-- row -->
                  </div><!-- card-body -->
                
                  <div class="card-footer">
                    <div class="input-group has-validation">
                      <button type="submit" class="btn btn-warning" name="Submit"><i class="bi bi-arrow-bar-up"></i> Envoyer</button>
                    </div>
                  </div><!-- card-footer -->   
                </div><!-- card text-white -->
              </div><!-- col -->
            </form><!-- formulaire Scores à saisir -->

            <?php
            } /* fin du if */
          } /* fin du foreach */

          //$nomE_A->closeCursor();
          //$nomE_B->closeCursor();

          ?>

        </div> <!-- div row-cols -->
      </div> <!-- div container-fluid -->

      <!-- SCORES SAISIS -->

      <?php 
      /*Condition pour création du bloc "Scores saisis"*/
      foreach ($mesMatchs as $matchTrouve) {

        //Condition des scores de match déjà saisis
        if(($matchTrouve['Score_A'] != 0)||($matchTrouve['Score_B'] != 0)){
      ?>

      <div id="rencontres" class="container-fluid">
        <h1 id="rencontres" class="text-success"><i>SCORES SAISIS</i> <i class="bi bi-server"></i></h1>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 g-4">
          <?php break;}} /* fin de la condition */

          //Afficher chaque Match un à un
          foreach ($mesMatchs as $matchTrouve) { 

            //Variables de temps pour affichage de la màj
            $dateM = $matchTrouve['Date_Maj'];
            $dateAff = strftime("%d %b à %X", strtotime($dateM));

            $idMT = $matchTrouve['Id_Match'];

            //Condition des scores de match déjà saisis
            if(($matchTrouve['Score_A']!=0)||($matchTrouve['Score_B']!=0))
            {

            //Contenu des tables des deux équipes pour chaque match
            $nomE_A = $bdd->query("SELECT Nom_Equipe FROM Equipes WHERE Id_Equipe = {$matchTrouve['Id_Equipe_A']}");
            $nomE_B = $bdd->query("SELECT Nom_Equipe FROM Equipes WHERE Id_Equipe = {$matchTrouve['Id_Equipe_B']}");

            //Varibales des noms des deux équipes
            $E_A = $nomE_A->fetch();
            $E_B = $nomE_B->fetch();

            ?>
            <!-- Création de chaque formulaire par match -->
            <form method="post" action="rencontresverif.php" autocomplete="off">

              <!-- Création de chaque card par match -->
              <div class="col">
                <div class="card text-white bg-dark border-success">
                  
                  <div class="card-header">
                    <span class="badge rounded-pill bg-success"><i class="bi bi-record-circle"></i> <?= 'Tour '.$matchTrouve['Phase']; ?></span>
                    <span class="badge rounded-pill bg-success"><i class="bi bi-clock"></i> <?= 'MAJ : '.$dateAff; ?></span>
                  </div>
                  
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <label for="scoreA" class="form-label"><?php echo $E_A['Nom_Equipe']; ?></label>
                        <input type="number" class="form-control border-success bg-dark text-white" required value="<?= $matchTrouve['Score_A']; ?>" id="scoreA" min="0" max="1000" placeholder="<?= $matchTrouve['Score_A']; ?>" name="scoreA_<?=$idMT;?>">
                      </div>
                      <div class="col">
                        <label for="scoreB" class="form-label"><?php echo $E_B['Nom_Equipe']; ?></label>
                        <input type="number" class="form-control border-success bg-dark text-white" required value="<?= $matchTrouve['Score_B']; ?>" id="scoreB" min="0" max="1000" placeholder="<?= $matchTrouve['Score_B']; ?>" name="scoreB_<?=$idMT;?>">
                      </div>
                    </div><!-- row -->
                  </div><!-- card-body -->
                
                  <div class="card-footer">
                    <button type="submit" class="btn btn-success" name="Modif"><i class="bi bi-arrow-counterclockwise"></i> Modifier</button>
                  </div><!-- card-footer -->  
                </div><!-- card text-white... -->
              </div><!-- col -->
            </form><!-- formulaire Scores Saisis-->
            <?php
            } /* fin du if */
          } /* fin du foreach */
        }/*condition affichage des scores à saisir & saisis*/

          //$nomE_A->closeCursor();
          //$nomE_B->closeCursor(); 

        ?>
        </div><!-- row row-cols... -->
      </div><!-- container-fluid -->

      <?php 
      if($nTotalMa != $nEq-1){
        if(($passage == 0)&&($_SESSION['estValide']==2)){?>
        <div id="rencontres" class="container-fluid">
          <h1 id="rencontres" class="text-danger"><i>TOUR SUIVANT</i> <i class="bi bi-chevron-double-right"></i></h1>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-3">
              <div class="col">
                <div class="card text-white bg-dark border-danger">
                  <div class="card-header">
                    <span class="badge rounded-pill bg-danger"><i class="bi bi-exclamation-circle"></i>&nbsp;Info </span> 
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Tour <?=$numTour;?> complété</h5>
                    <p class="card-text">Vous pouvez passer au tour suivant </p>   
                    <form method="post" action="">          
                      <button type="submit" class="btn btn-danger" name="tourSuivant" id="tourSuivant"><i class="bi bi-arrow-right-circle"></i> Tour suivant</button>
                    </form>
                  </div><!--Card-Body-->          
                </div><!--Card-->
              </div><!--Col-->
            </div><!-- row row-cols... -->
        </div><!-- container-fluid --> 
        <?php } }
        elseif(($nTotalMa == $nEq-1)&&($_SESSION['estValide']==2)&&($passage == 0)){?>
          <div id="rencontres" class="container-fluid">
          <h1 id="rencontres" class="text-danger"><i>TOURNOI FINI</i> <i class="bi bi-check2-all"></i></h1>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-3">
              <div class="col">
                <div class="card text-white bg-dark border-danger">
                  <div class="card-header">
                    <span class="badge rounded-pill bg-danger"><i class="bi bi-exclamation-circle"></i>&nbsp;Info </span> 
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Tour <?=$numTour;?> complété</h5>
                    <p class="card-text">Vous avez entièrement complété le tournoi </p>   
                    <form method="post" action="">          
                      <button type="submit" class="btn btn-danger" name="Finir" id="Finir"><i class="bi bi-check-circle"></i> Finir</button>
                    </form>
                  </div><!--Card-Body-->          
                </div><!--Card-->
              </div><!--Col-->
            </div><!-- row row-cols... -->
        </div><!-- container-fluid --> 
        <?php } ?>   
    </div> <!-- div row-cols -->
  </div> <!-- div container-fluid -->
<?php }?>
  </body>
</html>
<?php
  }
  else{header('Location: index.php');}
?>
