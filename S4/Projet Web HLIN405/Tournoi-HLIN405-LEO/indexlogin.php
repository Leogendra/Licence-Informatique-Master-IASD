<?php 
  session_start();
  if(isset($_SESSION['login']) && isset($_SESSION['mdp'])) {
    $login = $_SESSION['login'];
?>

<!DOCTYPE html>
<html>
<?php include('connexion.php'); ?>

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

    #live {padding-top:30px; background-color: rgb(0, 0, 0);}
    #prochainement {padding-top:30px; background-color: rgb(0, 0, 0);}
    
    .form-control{
       background-color:rgba(255,255,255,0.1);
       color: white;
    }
  </style>

  <body data-spy="scroll" data-target=".navbar" data-offset="50">

  <!-- NAVIGATION -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <div class="d-flex justify-content-start">
          <a class="navbar-brand" href="indexlogin.php"><i class="bi bi-house-fill"></i> MesTournois.com </a>
            <div class="btn-group">
              <a href="creation.php" class="btn btn-outline-success"><i class="bi bi-pencil-fill"></i> Création</a>
              <a href="logout.php" class="btn btn-outline-success"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
            </div>
        </div>
      </div>
    </nav>  

  <!-- EN LIVE --------------------------------------------------------------------------------------------------------------------->

    <div id="live" class="container-fluid">
      <h1 id="live" class="text-primary"><i>EN L!VE </i><i class="bi bi-broadcast"></i></h1>
      <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 g-4">
    
        <?php 

          $reponse = $bdd->query("SELECT * FROM Evenements, Tournois WHERE Tournois.Id_Evenement=Evenements.Id_Evenement and Evenements.Gestionnaire='$login'");

          $idevenement = 1 ;

          while ($result = $reponse->fetch()) { 

            $modal = str_replace(' ', '', $result['Nom_Tournoi']) ; 
            $today = date('Y-m-d') ;

          setlocale(LC_TIME, 'fr_FR.utf8','fra');
          $datedebutINIT = $result['Date_Debut'];
          $datedebut = strftime("%d %b %Y", strtotime($datedebutINIT));
          $datefinINIT = $result['Date_Fin'];
          $datefin = strftime("%d %b %Y", strtotime($datefinINIT));
        
          if($idevenement < $result['Id_Evenement']): $idevenement = $result['Id_Evenement'];endif;
          if($idevenement == $result['Id_Evenement'] && $result['Date_Debut'] <= $today && $today <= $result['Date_Fin']):
            $passagelive = 1 ; 

        ?>
        
        <div class="col">
          <div class="card text-white bg-dark border-primary">
            <div class="card-header">
              <span class="badge rounded-pill bg-primary"><i class="bi bi-geo-fill"></i>&nbsp;<?= $result['Lieu_Evenement'] ?></span> 
              <span class="badge rounded-pill bg-primary"><i class="bi bi-tags"></i>&nbsp;<?= $result['Categorie'] ?></span>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= $result['Nom_Evenement'] ?></h5>
              <p class="card-text"><?= $result['Description_Evenement'] ; ?></p>
        
              <!------------------->
              <!-- SI UN TOURNOI -->
          
              <?php if($result['Nom_Evenement'] == $result['Nom_Tournoi']): ?>
              
                <form method="post" action="rencontresverif.php">
                  <input type="hidden" value="<?=$result['Id_Tournoi'];?>" name="result_idTR_<?=$result['Id_Tournoi'];?>">          
                  <button type="submit" class="btn btn-primary" name="idTR_<?=$result['Id_Tournoi'];?>" value="<?=$result['Id_Tournoi'];?>"><i class="bi bi-people"></i> Rencontres</button>
                </form>  

                
              <!--------------------------->
              <!-- SI PLUSIEURS TOURNOIS -->

              <?php else:

                $reponseBOUCLE = $bdd->query('SELECT * FROM Evenements, Tournois WHERE Tournois.Id_Evenement=Evenements.Id_Evenement ORDER BY Nom_Tournoi');

                while ($resultBOUCLE = $reponseBOUCLE->fetch()) { 

                $modalBOUCLE = str_replace(' ', '', $resultBOUCLE['Nom_Tournoi']) ;

                if($idevenement == $resultBOUCLE['Id_Evenement']):

                //$_SESSION['idTR'][$resultBOUCLE['Id_Tournoi']] = $resultBOUCLE['Id_Tournoi']; 

                ?>

                <hr>
                <h6 class="card-title"><?= $resultBOUCLE['Nom_Tournoi'] ; ?></h6>
                <form method="post" action="rencontresverif.php">
                  <input type="hidden" value="<?=$resultBOUCLE['Id_Tournoi'];?>" name="result_idTR_<?=$resultBOUCLE['Id_Tournoi'];?>">          
                  <button type="submit" class="btn btn-primary" name="idTR_<?=$resultBOUCLE['Id_Tournoi'];?>"><i class="bi bi-people"></i> Rencontres</button>
                </form>  

                <?php endif; } /*$reponseBOUCLE->closeCursor(); */?>

              <!--------------------------->
              <!--------------------------->

              <?php endif;?>

            </div><!--Card-Body-->
            <div class="card-footer">
              <i class="bi bi-calendar3"></i>&nbsp; <?= $datedebut ; ?> <i class="bi bi-arrow-right-short"></i> <?= $datefin ; ?>
            </div>          
          </div><!--Card-->
        </div><!--Col-->

        <?php 

          $idevenement += 1;
          endif;
          } 
          if ($passagelive != 1) { ?>
            
            <div class="col">
          <div class="card text-white bg-dark border-primary">
            <div class="card-header">
              <span class="badge rounded-pill bg-primary"><i class="bi bi-exclamation-circle"></i>&nbsp;Info </span> 
            </div>
            <div class="card-body">
              <h5 class="card-title">Aucun Tournoi</h5>
              <p class="card-text">Vous pouvez ajouter un tournoi ou événement</p>             
                <div class="btn-group">
                  <a href="creation.php" class="btn btn-primary"><i class="bi bi-pencil-fill"></i> Création</a>
                </div>
            </div><!--Card-Body-->          
          </div><!--Card-->
        </div><!--Col-->

        <?php } ?>   

      </div>
    </div>

  <!-- PROCHAINEMENT --------------------------------------------------------------------------------------------------------------->

    <div id="prochainement" class="container-fluid">
      <h1 id="prochainement" class="text-warning"><i>PROCHAINEMENT </i><i class="bi bi-bell"></i></h1>
      <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 g-4">
    
        <?php 

          $reponse = $bdd->query("SELECT * FROM Evenements, Tournois WHERE Tournois.Id_Evenement=Evenements.Id_Evenement and Evenements.Gestionnaire='$login'");

          $idevenement = 1 ;

          while ($result = $reponse->fetch()) { 

          $modal = str_replace(' ', '', $result['Nom_Tournoi']) ; 
          $today = date('Y-m-d') ;

          setlocale(LC_TIME, 'fr_FR.utf8','fra');
          $datedebutINIT = $result['Date_Debut'];
          $datedebut = strftime("%d %b %Y", strtotime($datedebutINIT));
          $datefinINIT = $result['Date_Fin'];
          $datefin = strftime("%d %b %Y", strtotime($datefinINIT));

          if($idevenement < $result['Id_Evenement']): $idevenement = $result['Id_Evenement'];endif;
          if($idevenement == $result['Id_Evenement'] && $today < $result['Date_Debut'] && $today < $result['Date_Fin']):
            $passageprochainement = 1 ; 
        ?>
        
        <div class="col">
          <div class="card text-white bg-dark border-warning">
            <div class="card-header">
              <span class="badge rounded-pill bg-warning text-dark"><i class="bi bi-geo-fill"></i>&nbsp;<?= $result['Lieu_Evenement'] ?></span> 
              <span class="badge rounded-pill bg-warning text-dark"><i class="bi bi-tags"></i>&nbsp;<?= $result['Categorie'] ?></span>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= $result['Nom_Evenement'] ?></h5>
              <p class="card-text"><?= $result['Description_Evenement'] ; ?></p>
        
              <!------------------->
              <!-- SI UN TOURNOI -->
          
              <?php if($result['Nom_Evenement'] == $result['Nom_Tournoi']): ?>
              
                <div class="btn-group">
                  <a href="verification.php" class="btn btn-warning"><i class="bi bi-file-earmark-check"></i> Validation</a>
                </div>   

              <!--------------------------->
              <!-- SI PLUSIEURS TOURNOIS -->

              <?php else:

                $reponseBOUCLE = $bdd->query('SELECT * FROM Evenements, Tournois WHERE Tournois.Id_Evenement=Evenements.Id_Evenement ORDER BY Nom_Tournoi');

                while ($resultBOUCLE = $reponseBOUCLE->fetch()) { 

                $modalBOUCLE = str_replace(' ', '', $resultBOUCLE['Nom_Tournoi']) ;  

                if($idevenement == $resultBOUCLE['Id_Evenement']):

                ?>

                <hr>
                <h6 class="card-title"><?= $resultBOUCLE['Nom_Tournoi'] ; ?></h6>
                <div class="btn-group">
                  <a href="verification.php" class="btn btn-warning"><i class="bi bi-file-earmark-check"></i> Validation</a>
                </div>   

                <?php endif; } /*$reponseBOUCLE->closeCursor(); */?>

              <!--------------------------->
              <!--------------------------->

              <?php endif;?>

            </div><!--Card-Body-->
            <div class="card-footer">
              <i class="bi bi-calendar3"></i>&nbsp; <?= $datedebut ; ?> <i class="bi bi-arrow-right-short"></i> <?= $datefin ; ?>
            </div>
          </div><!--Card-->
        </div><!--Col-->

        <?php 

          $idevenement += 1;
          endif;
          } 
          if ($passageprochainement != 1) { 
        ?>
            
            <div class="col">
          <div class="card text-white bg-dark border-warning">
            <div class="card-header">
              <span class="badge rounded-pill text-dark bg-warning"><i class="bi bi-exclamation-circle"></i>&nbsp;Info </span> 
            </div>
            <div class="card-body">
              <h5 class="card-title">Aucun Tournoi</h5>
              <p class="card-text">Vous pouvez ajouter un tournoi ou événement</p>             
                <div class="btn-group">
                  <a href="creation.php" class="btn text-dark btn-warning"><i class="bi bi-pencil-fill"></i> Création</a>
                </div>
            </div><!--Card-Body-->          
          </div><!--Card-->
        </div><!--Col-->

        <?php } ?> 

      </div>
    </div>

  </body>
</html>

<?php
  }
  else{header('Location: index.php');}
?>
