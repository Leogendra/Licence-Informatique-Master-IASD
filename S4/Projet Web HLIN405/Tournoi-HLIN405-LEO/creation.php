<?php 
  include('connexion.php');
  session_start();
  if(isset($_SESSION['login']) && isset($_SESSION['mdp'])) {

  $Nom_Tournoi=$_POST["Nom_Tournoi"];
  $Categorie=$_POST["Categorie"];
  $Lieu=$_POST["Lieu"];
  $Date_Debut=$_POST["Date_Debut"];
  $Date_Fin=$_POST["Date_Fin"];
  $Nombre_Equipes_Tournoi=$_POST["Nombre_Equipes_Tournoi"];
  $Description=$_POST["Description"];
  $Plus_Infos=$_POST["Plus_Infos"];

    if($Date_Debut > $Date_Fin) { $invalide='is-invalid'; $ok='required'; $message='<i class="bi bi-hourglass-split"></i> En cours...';}
    elseif ($Date_Debut == $Date_Fin) { $ok='required'; $message='<i class="bi bi-hourglass-split"></i> En cours...'; }
    else { $invalide=''; $ok='disabled'; $message='<i class="bi bi-check-circle"></i> Envoyé'; $valid='is-valid';}

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
    body {position: relative; background-color: rgb(0, 0, 0);}
    h1 {color: white;}

    #live {padding-top:30px; background-color: rgb(0, 0, 0);}
    #prochainement {padding-top:30px; background-color: rgb(0, 0, 0);}
    #fini {padding-top:30px; background-color: rgb(0, 0, 0);}

  </style>

  <body data-spy="scroll" data-target=".navbar" data-offset="50">

    <?php 
      $demain = date('Y-m-d', strtotime('+1 day'));
    ?>

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

  <!-- EN LIVE -->

    <div id="live" class="container-fluid">
      <h1 id="live" class="text-primary"><i>CREATION </i> <i class="bi bi-pencil-fill"></i></h1>
      <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col">
          <div class="card text-white bg-dark border-primary">
            
            <div class="card-header">
              <span class="badge rounded-pill bg-primary"><?=$message?></span> 
            </div>
            
            <div class="card-body">
              <h5 class="card-title">Création Tournoi</h5>
            
              <form class="row" method="post" action="">

                <div class="col-xl-6 my-1">
                  <label class="form-label">Nom du tournoi</label>
                  <input type="text" class="form-control bg-dark text-white <?=$valid?>" name="Nom_Tournoi" value="<?php echo $Nom_Tournoi?>" placeholder="Roland-Garros 2021" <?=$ok?>>
                </div>
                <div class="col-xl-3 my-1">
                  <label class="form-label">Catégorie</label>
                  <select class="form-select bg-dark text-white <?=$valid?>" name="Categorie" value="<?php echo $Categorie?>" <?=$ok?>>
                    <option value="Tennis">Tennis</option>
                    <option value="Rugby">Rugby</option>
                    <option value="Football">Football</option>
                    <option value="Basket-Ball">Basket-Ball</option>
                    <option value="Handball">Handball</option>
                    <option value="Badminton">Badminton</option>
                    <option value="Baseball">Baseball</option>
                    <option value="Boxe">Boxe</option>
                    <option value="Curling">Curling</option>
                    <option value="Cyclisme">Cyclisme</option>
                    <option value="Beach Volley">Beach Volley</option>                    
                    <option value="Volley-Ball">Volley-Ball</option>
                  </select>
                </div>
                <div class="col-xl-3 my-1">
                  <label class="form-label">Lieu</label>
                  <input type="text" class="form-control bg-dark text-white <?=$valid?>" name="Lieu" placeholder="Paris" value="<?php echo $Lieu?>" <?=$ok?>>
                </div>

                <div class="col-md-12 my-1">
                  <label class="form-label">Description</label>
                  <textarea rows="2" class="form-control bg-dark text-white <?=$valid?>" name="Description" placeholder="Roland-Garros est un tournoi de tennis sur terre battue créé en 1925 et qui se tient annuellement depuis 1928 à Paris, dans le stade Roland-Garros. Il succède au Championnat de France créé en 1891..." <?=$ok?>><?php echo $Description?></textarea> 
                </div>

                <div class="col-xl-4 my-1">
                  <label class="form-label">Date Début</label>
                  <input type="date" class="form-control bg-dark text-white <?=$valid?>" name="Date_Debut" min="<?=$demain?>" value="<?php echo $Date_Debut?>" <?=$ok?>>
                </div>
                <div class="col-xl-4 my-1">
                  <label class="form-label">Date Fin</label>
                  <input type="date" <?='class="form-control '.$invalide.' bg-dark text-white '.$valid.'"'?> name="Date_Fin" min="<?=$demain?>" value="<?php echo $Date_Fin?>" <?=$ok?>>
                </div>
                <div class="col-xl-4 my-1">
                  <label class="form-label">Nombre d'équipes</label>
                  <select class="form-select bg-dark text-white <?=$valid?>" value="<?php echo $Nombre_Equipes_Tournoi?>" name="Nombre_Equipes_Tournoi" <?=$ok?>>
                    <option value="2">2</option>
                    <option value="4">4</option>
                    <option value="8">8</option>
                    <option value="16">16</option>
                    <option value="32">32</option>
                    <option value="64">64</option>
                    <option value="128">128</option>
                    <option value="256">256</option>
                  </select>
                </div>

                <div class="col-md-12 my-1">
                  <label class="form-label">Plus d'infos</label>
                  <textarea rows="2" class="form-control bg-dark text-white <?=$valid?>" name="Plus_Infos" placeholder="Le tournoi de qualification du simple messieurs de Roland-Garros 2020 se déroule du 21 au 25 septembre 2020. 16 des 128 joueurs engagés se qualifient pour le tableau principal du tournoi, au terme de trois tours." <?=$ok?>><?php echo $Plus_Infos?></textarea>
                </div>

            </div>
            
            <div class="card-footer"> 
              <?php 
              if($ok == 'disabled'){
                echo '
                  <div class="btn-group">
                    <a href="indexlogin.php" class="btn btn-primary"><i class="bi bi-arrow-left-circle"></i> Acceuil</a>
                    <a href="creation.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nouveau</a>
                  </div> ' ;
              }
              else{
                echo '<button type="submit" name="valider" class="btn btn-primary"> <i class="bi bi-arrow-bar-up"></i> Envoyer</button>';
              }
              ?>
            </div> 
            
            </form>    

          </div>
        </div>

        
<?php

$req = $bdd->prepare('INSERT INTO Evenements (Nom_Evenement, Description_Evenement, Lieu_Evenement, Date_Debut, Date_Fin, Categorie, Gestionnaire) VALUES(?, ?, ?, ?, ?, ?, ?)');
$req->execute(array($_POST['Nom_Tournoi'], $_POST['Description'],$_POST['Lieu'], $_POST['Date_Debut'],$_POST['Date_Fin'], $_POST['Categorie'],$_SESSION['login']));

$reponse = $bdd->query("SELECT Id_Evenement FROM Evenements ORDER BY Id_Evenement DESC LIMIT 0, 1"); 
$result = $reponse->fetch();

$req = $bdd->prepare('INSERT INTO Tournois (Id_Evenement, Nom_Tournoi, Nombre_Equipes_Tournoi, Plus_Infos) VALUES(?, ?, ?, ?)');
$req->execute(array($result['Id_Evenement'], $_POST['Nom_Tournoi'], $_POST['Nombre_Equipes_Tournoi'],$_POST['Plus_Infos']));


?>



        <div class="col">
          <div class="card text-white bg-dark border-primary">
            
            <div class="card-header">
              <span class="badge rounded-pill bg-primary"><i class="bi bi-exclamation-circle"></i> Info</span> 
            </div>
            
            <div class="card-body">
              <h5 class="card-title">Création Événement</h5>
              <p class="card-text">Vous pouvez ajouter un événement. Un événement est un ensemble tournois</p> 

              <form class="row" method="post" action="creationbis.php">
                <div class="col-xl-4 my-1">
                  <label class="form-label">Nombre de tournois</label>
                  <input type="number" placeholder="2" min="2" max="10" class="form-control bg-dark text-white" name="NbTournois" required>
                </div>
          
              <?php $_SESSION['NbTournois'] = $_POST['NbTournois']; ?>

            </div>

            <div class="card-footer"> 
              <button type="submit" name="valider" class="btn btn-primary"><i class="bi bi-pencil-fill"></i> Création</button>
            </form>
            </div> 

          </div>
        </div>

      </div>
    </div>

  </body>
</html>

<?php
  }
  else{header('Location: index.php');}
?>
