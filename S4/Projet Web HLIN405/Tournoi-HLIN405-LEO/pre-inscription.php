<?php 
  session_start();
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
  </style>

  <body data-spy="scroll" data-target=".navbar" data-offset="50">

    <?php $today = date('Y-m-d'); ?>

  <!-- NAVIGATION -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <div class="d-flex justify-content-start">
          <a class="navbar-brand" href="index.php"><i class="bi bi-house-fill"></i> MesTournois.com </a>
          <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#connexion"><i class="bi bi-box-arrow-in-right"></i> Connexion</button>
        </div>
      </div>
    </nav>  

  <!-- EN LIVE -->

    <div id="live" class="container-fluid">
      <h1 id="live" class="text-warning"><i>Pré-inscription <i class="bi bi-plus-circle-dotted"></i></i></h1>
      <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col">
          <div class="card text-white bg-dark border-warning">
            
            <div class="card-header">
              <span class="badge rounded-pill text-dark bg-warning"><i class="bi bi-hourglass-split"></i><?php echo $_SESSION[$result['Id_Tournois']] ?></span> 
            </div>
            
            <div class="card-body">
            
              <form class="row" method="post"> 

                <div class="col-xl-4 my-1">
                  <label class="form-label">Nom équipe </label>
                  <input type="text" class="form-control bg-dark text-white" name="NomEquipe" placeholder="Stade Toulousain" required>
                </div>
                <div class="col-xl-4 my-1">
                  <label class="form-label">Telephone</label>
                  <input type="tel"  class="form-control bg-dark text-white" name="Telephone" placeholder="06 07 08 09 10" required>
                </div>
                <div class="col-xl-4 my-2">
                  <label class="form-label">Adresse mail</label>
                  <input type="email" class="form-control bg-dark text-white" name="Mail" placeholder="stade.toulousain@gmail.com" required>
                </div>
                <div class="col-xl-6 my-1">
                  <label class="form-label">Prenom du Capitaine </label>
                  <input type="text" class="form-control bg-dark text-white" name="Pcapitaine" placeholder="Julien" required>
                </div>
                <div class="col-xl-6 my-1">
                  <label class="form-label">Nom du capitaine</label>
                  <input type="text" class="form-control bg-dark text-white" name="Ncapitaine" placeholder="Marchand" required>
                </div>
                <div class="col-xl-12 my-1">
                  <label class="form-label">Adresse </label>
                  <textarea class="form-control bg-dark text-white" name="Adresse" placeholder="114, rue des Troènes, 31022 Toulouse Cedex 2" rows="2" required></textarea>
                </div>   
            </div>
            
            <div class="card-footer"> 
              <button type="submit" name="inscription" class="btn btn-warning"><i class="bi bi-arrow-bar-up"></i> Envoyer</button> 
            </div> 
            
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>  
</html>

<?php

  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $NomEquipe = $_POST['NomEquipe'];
  $Mail= $_POST['Mail'];
  $Pcapitaine = $_POST['Pcapitaine'];
  $Ncapitaine = $_POST['Ncapitaine'];
  $Telephone = $_POST['Telephone'];
  $Adresse = $_POST['Adresse'];

  $sql = "INSERT INTO `Equipes`( `Nom_Equipe`, `Id_Tournois`, `Niveau_Equipe`, `Adresse_Equipe`, `Mail_Equipe`, `Tel_Equipe`, `Nom_Cap_Equipe`, `Prenom_Cap_Equipe`,`validee`) VALUES (?,5,0,?,?,?,?,?,false)";
  
  
  
  $stmt = $bdd->prepare($sql);
  $stmt->execute([$NomEquipe,$Adresse,$Mail,$Telephone,$Ncapitaine,$Pcapitaine]);
  //header ('location: index.php'); //Marche pas !!!
?> 
