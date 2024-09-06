<?php 
  session_start();
  if(isset($_SESSION['login']) && isset($_SESSION['mdp'])) {
    $login = $_SESSION['login'];
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
    
    .form-control{ background-color:rgba(255,255,255,0.1); color: white; }
    .form-select{ background-color:rgba(255,255,255,0.1); color: white; }
  </style>

  <body data-spy="scroll" data-target=".navbar" data-offset="50">

    <?php $today = date('Y-m-d'); ?>

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

<?php

  try {
    $servername = "localhost";
    $username = "sasha@localhost";
    $password = "sasha1234";
    $dbname = "projet";
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $res = $conn->query('SELECT * FROM Equipes WHERE validee = 0 AND Niveau_Equipe = 0');

  } 
  catch(PDOException $e) {
  echo $e->getMessage();
  }

  $cpt = 1;
  $tabId = array();


  ?>

    <div id="live" class="container-fluid">
      <h1 id="live" class="text-warning"><i>VERIFICATION</i> <i class="bi bi-pencil-fill"></i></h1>
      <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 g-4">

        <?php 
        while ($row = $res->fetch()) {
        $tabId[] = $row['Id_Equipe'];
        ?>

        <div class="col">
          <div class="card text-white bg-dark border-warning">

            <div class="card-body">

                <div class="row">
                <div class="col-xl-12 my-1">
                  <label class="form-label">Nom équipe : </label>
                  <?php echo $row['Nom_Equipe']; ?>
                </div>
                <div class="col-xl-12 my-1">
                  <label class="form-label">Adresse mail : </label>
                  <?php echo $row['Mail_Equipe']; ?>
                </div>
                <div class="col-xl-12 my-1">
                  <label class="form-label">Prenom du Capitaine :</label>
                  <?php echo $row['Prenom_Cap_Equipe']; ?>
                </div>
                <div class="col-xl-12 my-1">
                  <label class="form-label">Nom du capitaine :</label>
                  <?php echo $row['Nom_Cap_Equipe']; ?>
                </div>
                <div class="col-xl-12 my-1">
                  <label class="form-label">Telephone :</label>
                  <?php echo $row['Tel_Equipe']; ?>
                </div>
                </div>
                <div class="col-xl-12 my-1">
                  <label class="form-label">Adresse :</label>
                  <?php echo $row['Adresse_Equipe']; ?>
                </div>
                
                
                  <div class="col-xl-4 my-1">
                  <label class="form-label">Niveau :</label>
                  <input type="number" name="Niveau" min="1" max="100" class="form-control bg-dark text-white">
                  </div>

            </div>

            <div class="card-footer">
              <form method="post">

              <button type="submit" name="validation<?php echo $cpt ?>" class="btn btn-warning"><i class="bi bi-check-circle"></i> Valider</button>
              <button type="submit" name="refuser<?php echo $cpt ?>" class="btn btn-warning"><i class="bi bi-check-circle"></i> Refuser</button>
              <?php
              $cpt = $cpt + 1;
              ?>
              </form>
            </div>
                
          </div> 
        </div>


              

              <?php 
              }

              for($i = 1; $i <= $cpt ; $i = $i + 1)
              {
                if(isset($_POST['validation'.$i]))
                {
                  $niveau=$_POST['Niveau'];
                  $ind = $i - 1;
                  $ideq = $tabId[$ind];
                  
                  $conn->query("UPDATE `Equipes` SET validee =1 , Niveau_Equipe = $niveau  WHERE  Id_Equipe = $ideq");
                  header("Refresh:0");
                }
              }

              for($j = 1; $j <= $cpt ; $j = $j + 1)
              {
                if(isset($_POST['refuser'.$j]))
                {
                  $niveau=$_POST['Niveau'];
                  $ind2 = $j - 1;
                  $ideq2 = $tabId[$ind2];
                  $conn->query("UPDATE Equipes SET validee = 2 , Niveau_Equipe = $niveau  WHERE Id_Equipe = $ideq2");
                  header("Refresh:0");
                }
              }
              
              ?>
            </div>
          </div>
  </body>
</html>




<?php

$conn = null;
?>
<?php
  }
  else{header('Location: index.php');}
?>
