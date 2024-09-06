<?php 

session_start();

include('connexion.php');

$tousTournois = $bdd->query("SELECT Id_Tournoi FROM Tournois")->fetchAll();
foreach($tousTournois as $result) {
  if(isset($_POST['idTR_'.$result['Id_Tournoi']])){
    $_SESSION['monIdT'] = $_POST['result_idTR_'.$result['Id_Tournoi']];
  }
}

$TabEqu = $bdd->query("SELECT * FROM Equipes WHERE Id_Tournois = {$_SESSION['monIdT']}")->fetchAll();
$nEq = count($TabEqu);

$maxPhase = $bdd->query("SELECT Phase FROM Matchs WHERE Id_Tournoi = {$_SESSION['monIdT']} ORDER BY Phase DESC")->fetch();

//Récupérer l'entièreté de la table Matchs juste pour affecter valeur à $numTour
$nbTotalMatchs = $bdd->query("SELECT * FROM Matchs WHERE Id_Tournoi = {$_SESSION['monIdT']}")->fetchAll();
$nTotalMa = count($nbTotalMatchs);//Je compte mon nombre de Matchs total

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

//Contenu table Matchs
$nomMatch = $bdd->query("SELECT * FROM Matchs WHERE Id_Tournoi = {$_SESSION['monIdT']} AND Phase = {$numTour}");
$tr = $nomMatch->fetchAll();

/*fonction pour inscrire/créer des nouveaux matchs*/
if(isset($_POST['envoyerMatchs'])){
  if($_SESSION['Rempl'] == 'rM'){
    if((isset($_POST['Equ1']))&&(isset($_POST['Equ2']))){
      if($_POST['Equ1']!=$_POST['Equ2']){
        $reqEnvMatch = $bdd->prepare('INSERT INTO Matchs(Id_Equipe_A,Id_Equipe_B,Score_A,Score_B,Phase,Id_Tournoi) VALUES(:nomEA,:nomEB,:scA,:scB,:phase,:idtournoi)');
        $reqEnvMatch->execute(array(
          'nomEA' => $_POST['Equ1'],
          'nomEB' => $_POST['Equ2'],
          'scA' => 0,
          'scB' => 0,
          'phase' => $numTour,
          'idtournoi' => $_SESSION['monIdT'] 
        ));
        $reqEnvMatch->closeCursor();
        $_SESSION['estValide'] = 0;
      }
    }
  }
  
  
  elseif($_SESSION['Rempl'] == 'rA'){
    if($numTour == 1){
      if(count($tr)==0){$mesidE = $TabEqu;}
      else {
        $mesidE = $bdd->query("SELECT Id_Equipe FROM Equipes WHERE Id_Tournois = {$_SESSION['monIdT']} AND Id_Equipe NOT IN (SELECT Id_Equipe_A FROM Matchs) AND Id_Equipe NOT IN (SELECT Id_Equipe_B FROM Matchs)")->fetchAll();}
    }
    elseif($numTour!=0){
      $mesidE = $bdd->query("SELECT Id_Equipe FROM (SELECT * FROM Equipes WHERE Id_Equipe IN (SELECT Id_Equipe_A FROM Matchs WHERE Phase = {$numTour}-1 AND Score_A > Score_B) OR Id_Equipe IN (SELECT Id_Equipe_B FROM Matchs WHERE Phase = {$numTour}-1 AND Score_B > Score_A)) AS EqGagnantes WHERE Id_Tournois = {$_SESSION['monIdT']} AND Id_Equipe NOT IN (SELECT Id_Equipe_A FROM Matchs WHERE Phase = {$numTour}) AND Id_Equipe NOT IN (SELECT Id_Equipe_B FROM Matchs WHERE Phase = {$numTour})")->fetchAll();
    }
    $nbMesEq = count($mesidE);
        $i = 0; $j = 1;
        while($i < $nbMesEq){
          $reqEnvMatchRandom = $bdd->prepare('INSERT INTO Matchs(Id_Equipe_A,Id_Equipe_B,Score_A,Score_B,Phase,Id_Tournoi) VALUES(:idEA,:idEB,:scA,:scB,:phase,:idtournoi)');
          $reqEnvMatchRandom->execute(array(
            'idEA' => $mesidE[$i][Id_Equipe],
            'idEB' => $mesidE[$j][Id_Equipe],
            'scA' => 0,
            'scB' => 0,
            'phase' => $numTour,
            'idtournoi' => $_SESSION['monIdT'] 
          ));
          //$reqEnvMatchRandom->closeCursor();
          $i+=2;
          $j+=2;
        }
        $_SESSION['estValide'] = 0;
    } 
}

/*fonction pour supprimer un match déjà créé*/
foreach ($tr as $matchCree) {
  
  if(isset($_POST['Reinit_'.$matchCree['Id_Match']])){
    $reqReinitMatch = $bdd->exec("DELETE from Matchs WHERE Id_Match = {$matchCree['Id_Match']}");
    $_SESSION['estValide'] = 0;
  }
}

/*fonction pour valider tous les matchs qui s'affronteront dans le tournoi*/
if(isset($_POST['validerMatchs'])){
  $_SESSION['estValide'] = 2;
}


/*fonction pour rentrer les scores des matchs dans la base de donnée*/ 
foreach ($tr as $matchTrouve) {

  $idMT = $matchTrouve['Id_Match'];

  if((isset($_POST['Submit']))||(isset($_POST['Modif']))){

    if($_POST['scoreA_'.$idMT] != $_POST['scoreB_'.$idMT]){

    $req = $bdd->prepare("UPDATE Matchs SET Score_A = :scorea, Score_B = :scoreb, Date_Maj = CURRENT_TIMESTAMP() WHERE Id_Match = {$matchTrouve['Id_Match']}");

    $req->execute(array(
      'scorea' => $_POST['scoreA_'.$idMT],
      'scoreb' => $_POST['scoreB_'.$idMT]
    ));

    $req->closeCursor();
    }
  }

}

/*if(isset($_POST['tourSuivant'])){
  $_SESSION['passeTS'] = 1;
}*/

$nomMatch->closeCursor();

header('Location: rencontres.php');
exit;

?>