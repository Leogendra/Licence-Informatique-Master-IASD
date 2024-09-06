<?php 
  session_start();
  if(isset($_SESSION['login']) && isset($_SESSION['mdp'])) { header('Location: indexlogin.php'); }
  else{
  include('connexion.php');

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

      #live {padding-top:30px; background-color: rgb(0, 0, 0);}
      #prochainement {padding-top:30px; background-color: rgb(0, 0, 0);}
      #fini {padding-top:30px; background-color: rgb(0, 0, 0);}
      
      .form-control{ background-color:rgba(255,255,255,0.1); color: white; }
      .form-select{ background-color:rgba(255,255,255,0.1); color: white; }
  </style>

  <body data-spy="scroll" data-target=".navbar" data-offset="50">

  <!-- NAVIGATION -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    	<div class="container-fluid">
    		<div class="d-flex justify-content-start">
    			<a class="navbar-brand" href="index.php"><i class="bi bi-house-fill"></i> MesTournois.com </a>
          <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#connexion"><i class="bi bi-box-arrow-in-right"></i> Connexion</button>
    		</div>
        <div class="d-flex justify-content-end">
          <button class="navbar-toggler " type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
          </button>
        </div>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <p> &nbsp;&nbsp; </p>
            <li class="nav-item">
              <a class="nav-link" href="#live"> En Live </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#prochainement"> Prochainement </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#fini"> Fini </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>  

  <!-- Modal Connexion -->
    <div class="modal fade" id="connexion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content text-white bg-dark border-success">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Connexion</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-white">
            
            <form action="login.php" method="post">

            <div class="form-floating mb-3">
              <input type="text" name="login" class="form-control bg-dark text-white" placeholder="Identifiant" required>
              <label for="floatingInput">Identifiant</label>
            </div>
            <div class="form-floating">
              <input type="password" name="mdp" class="form-control bg-dark text-white" placeholder="Mot de passe" required>
              <label for="floatingPassword">Mot de passe</label>
            </div>

          </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-success" value="Connexion">
            </form>
          </div>
        </div>
      </div>
    </div>

  <!-- EN LIVE -->

    <div id="live" class="container-fluid">
      <h1 id="live" class="text-primary"><i>EN L!VE </i><i class="bi bi-broadcast"></i></h1>
      <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 g-4">
    
        <?php 

          $reponse = $bdd->query('SELECT * FROM Evenements, Tournois WHERE Tournois.Id_Evenement=Evenements.Id_Evenement');

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
          if($idevenement == $result['Id_Evenement']  && $result['Date_Debut'] < $today && $today < $result['Date_Fin']):

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
              
                <div class="btn-group">
                  <a href="affichage.php" class="btn btn-primary"><i class="bi bi-diagram-2"></i> Suivre</a>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" <?= 'data-bs-target="#'.$modal.'"';?> >
                    <i class="bi bi-info-circle"></i> Plus d'infos
                  </button>
                </div>   
                <div class="modal fade" <?= 'id="'.$modal.'"' ;?> data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content text-white bg-dark border-primary">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Plus d'Infos</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-white">              
                        <?= $result['Plus_Infos'] ; ?>
                      </div><!--Body-->
                    </div><!--Modal-content-->
                  </div><!--Modal-dialog-->
                </div><!--Modal fade-->

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
                  <a href="affichage.php" class="btn btn-primary"><i class="bi bi-diagram-2"></i> Suivre</a>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" <?= 'data-bs-target="#'.$modalBOUCLE.'"';?> >
                    <i class="bi bi-info-circle"></i> Plus d'infos
                  </button>
                </div>                    
                <div class="modal fade" <?= 'id="'.$modalBOUCLE.'"' ;?> data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content text-white bg-dark border-primary">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Plus d'Infos</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-white">              
                        <?= $resultBOUCLE['Plus_Infos'] ; ?>
                      </div><!--Body-->
                    </div><!--Modal-content-->
                  </div><!--Modal-dialog-->
                </div><!--Modal fade-->

                <?php endif; } $reponseBOUCLE->closeCursor(); ?>

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

        ?>   

      </div>
    </div>

  <!-- PROCHAINEMENT -->

    <div id="prochainement" class="container-fluid">
      <h1 id="prochainement" class="text-warning"><i>PROCHAINEMENT </i><i class="bi bi-bell"></i></h1>
      <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 g-4">
    
        <?php 

          $reponse = $bdd->query('SELECT * FROM Evenements, Tournois WHERE Tournois.Id_Evenement=Evenements.Id_Evenement');

          $idevenement = 1 ;

          while ($result = $reponse->fetch()) { 

          $modal = str_replace(' ', '', $result['Nom_Evenement']) ; 
          $today = date('Y-m-d') ;

          setlocale(LC_TIME, 'fr_FR.utf8','fra');
          $datedebutINIT = $result['Date_Debut'];
          $datedebut = strftime("%d %b %Y", strtotime($datedebutINIT));
          $datefinINIT = $result['Date_Fin'];
          $datefin = strftime("%d %b %Y", strtotime($datefinINIT));

          if($idevenement < $result['Id_Evenement']): $idevenement = $result['Id_Evenement'];endif;
          if($idevenement == $result['Id_Evenement'] && $today < $result['Date_Debut'] && $today < $result['Date_Fin']):

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
                  <?php $_SESSION[$result['Id_Tournois']] = $result['Nom_Tournoi']; ?> <!--MARCHE PAS !!!!!-->
                  <a href="pre-inscription.php" class="btn btn-warning"><i class="bi bi-plus-circle-dotted"></i> Pré-inscription</a>
                  <button type="button" class="btn btn-warning" data-bs-toggle="modal" <?= 'data-bs-target="#'.$modal.'"';?> >
                    <i class="bi bi-info-circle"></i> Plus d'infos
                  </button>
                </div>   
                <div class="modal fade" <?= 'id="'.$modal.'"' ;?> data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content text-white bg-dark border-warning">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Plus d'Infos</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-white">              
                        <?= $result['Plus_Infos'] ; ?>
                      </div><!--Body-->
                    </div><!--Modal-content-->
                  </div><!--Modal-dialog-->
                </div><!--Modal fade-->

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
                  <?php $_SESSION[$result['Id_Tournois']] = $result['Nom_Tournoi']; ?> <!--MARCHE PAS !!!!!-->
                  <a href="pre-inscription.php" class="btn btn-warning"><i class="bi bi-plus-circle-dotted"></i> Pré-inscription</a>
                  <button type="button" class="btn btn-warning" data-bs-toggle="modal" <?= 'data-bs-target="#'.$modalBOUCLE.'"';?> >
                    <i class="bi bi-info-circle"></i> Plus d'infos
                  </button>
                </div>                    
                <div class="modal fade" <?= 'id="'.$modalBOUCLE.'"' ;?> data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content text-white bg-dark border-warning">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Plus d'Infos</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-white">              
                        <?= $resultBOUCLE['Plus_Infos'] ; ?>
                      </div><!--Body-->
                    </div><!--Modal-content-->
                  </div><!--Modal-dialog-->
                </div><!--Modal fade-->

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

        ?>   

      </div>
    </div>

  <!-- FINI -->

    <div id="fini" class="container-fluid">
      <h1 id="fini" class="text-success"><i>FINI </i><i class="bi bi-calendar2-check"></i></h1>
      <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 g-4">
    
        <?php 

          $reponse = $bdd->query('SELECT * FROM Evenements, Tournois WHERE Tournois.Id_Evenement=Evenements.Id_Evenement');

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
          if($idevenement == $result['Id_Evenement'] && $result['Date_Debut'] < $today && $result['Date_Fin'] < $today):

        ?>
        
        <div class="col">
          <div class="card text-white border-success bg-dark">
            <div class="card-header">
              <span class="badge rounded-pill bg-success"><i class="bi bi-geo-fill"></i>&nbsp;<?= $result['Lieu_Evenement'] ?></span> 
              <span class="badge rounded-pill bg-success"><i class="bi bi-tags"></i>&nbsp;<?= $result['Categorie'] ?></span>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= $result['Nom_Evenement'] ?></h5>
              <p class="card-text"><?= $result['Description_Evenement'] ; ?></p>
        
              <!------------------->
              <!-- SI UN TOURNOI -->
          
              <?php if($result['Nom_Evenement'] == $result['Nom_Tournoi']): ?>
              
                <div class="btn-group">
                  <a href="affichage.php" class="btn btn-success"><i class="bi bi-clock-history"></i> Revoir</a>
                  <button type="button" class="btn btn-success" data-bs-toggle="modal" <?= 'data-bs-target="#'.$modal.'"';?> >
                    <i class="bi bi-info-circle"></i> Plus d'infos
                  </button>
                </div>   
                <div class="modal fade" <?= 'id="'.$modal.'"' ;?> data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content text-white bg-dark border-success">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Plus d'Infos</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-white">              
                        <?= $result['Plus_Infos'] ; ?>
                      </div><!--Body-->
                    </div><!--Modal-content-->
                  </div><!--Modal-dialog-->
                </div><!--Modal fade-->

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
                  <a href="affichage.php" class="btn btn-success"><i class="bi bi-clock-history"></i> Revoir</a>
                  <button type="button" class="btn btn-success" data-bs-toggle="modal" <?= 'data-bs-target="#'.$modalBOUCLE.'"';?> >
                    <i class="bi bi-info-circle"></i> Plus d'infos
                  </button>
                </div>                    
                <div class="modal fade" <?= 'id="'.$modalBOUCLE.'"' ;?> data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content text-white bg-dark border-success">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Plus d'Infos</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-white">              
                        <?= $resultBOUCLE['Plus_Infos'] ; ?>
                      </div><!--Body-->
                    </div><!--Modal-content-->
                  </div><!--Modal-dialog-->
                </div><!--Modal fade-->

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

        ?>   

      </div>
    </div>

  </body>
</html>

<?php } ?>
