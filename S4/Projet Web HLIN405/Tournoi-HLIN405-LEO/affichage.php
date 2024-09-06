<html>
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<style type="text/css">
    table td { border:none!important;}
    :root{
    --bg-color: #000000;
}

td {
    background-color: var(--bg-color);
    border: none;
}

.not-empty{
    background-color: #fff;
}
.score-span{
    margin-left: 10px;

}
.masq{
    background-color: black;
    border-right: 3px white solid !important;
}
</style>
</head>
<body>
<?php
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    include 'connexionbis.php';

    $Id_Tournois= '5';

    function getListEquipes(){
        global $dbh,$Id_Tournois;
        $stmt = $dbh->prepare("SELECT * FROM Equipes where Id_Tournois = ? ");
        $eTournois = array();
        if ($stmt->execute(array($Id_Tournois))){
            foreach ($stmt as $row) {
                $eTournois[] = $row;
            }
        }
        return $eTournois;
    }

    function getListMatchs(){
        global $dbh,$Id_Tournois;
        $stmt = $dbh->prepare("SELECT * FROM Matchs where Id_Tournois = ? ");
        $eTournois = array();
        if ($stmt->execute(array($Id_Tournois))){
            foreach ($stmt as $row) {
                $eTournois[] = $row;
            }
        }
        return $eTournois;
    }

    function getNomEquipeById(){
        $listEquipes= getListEquipes();
        $listNomEquipes= array();
        foreach ($listEquipes as $equipe) {
            $listNomEquipes[$equipe['Id_Equipe']]=$equipe['Nom_Equipe'];
        }
        return $listNomEquipes;
    }


    function getResultList(){
        $listEquipes=getListEquipes();
        $total_rounds = floor(log(count($listEquipes), 2)) + 1;
        $nomEquipe= getNomEquipeById();
        $listMatchs= getListMatchs();
        $listEq= array();
        $listScore= array();
        $listGagnats= array();
        for ($round = 1; $round <= $total_rounds; $round++) {
            $lequipe= array();
            $lscore= array();
            $lgagnats= array();
            foreach ($listMatchs as $match) {
                if ($match['Phase']==$round){
                    $lequipe[]= $nomEquipe[$match['Id_Equipe_A']];
                    $lequipe[]= $nomEquipe[$match['Id_Equipe_B']];
                    $lscore[]= $match['Score_A'];
                    $lscore[]= $match['Score_B'];
                    $lgagnats[]= $nomEquipe[$match['Gagnant']];
                }
            }

            $listEq[]= $lequipe;
            $listScore[]= $lscore;
            $listGagnats[]= $lgagnats;
        }

        return array('equipe'=>$listEq,'score'=>$listScore,'gagnat'=>$listGagnats);
    }   

    function crea_matchesTournois($eTournois){
        for ($i = 0; $i < count($equipesTournois); $i+=2){
            $equipe1 = $eTournois[$i+1];
            $equipe2 = $eTournois[$i];
            $sql = "INSERT INTO Match {Id_Equipe_A, Id_Equipe_B} VALUES ($equipe1, $equipe2)";
        }
    }


    function is_player($round, $row, $team) {
        return $row == pow(2, $round-1) + 1 + pow(2, $round)*($team - 1);
    }


    $listEquipes=getListEquipes();
    $num_teams = count($listEquipes);
    $result= getResultList();
    $Equipes= $result['equipe'];  
    $Gagnats= $result['gagnat'];    
    $Score= $result['score'];
    $total_rounds = floor(log($num_teams, 2)) + 1;
    $max_rows = $num_teams*2;
    $team_array =  array();
    $unpaired_array = array();
    $score_array = array();

    $array1 = ["gagnant","final", "demi", "quart","8eme","16eme","32eme","64eme"];
    
    for ($round = 1; $round <= $total_rounds; $round++) {
        $team_array[$round] = 1;
        $unpaired_array[$round] = False;
        $score_array[$round] = False;
    }


    echo "<table style=\"border: solid black\" cellspacing=\"2\" cellpadding=\"10\" class=\"table\" >\n";
    echo "\t<tr border=\" solid black\">\n";

    for ( $round = 1; $round <= $total_rounds; $round++) {
        $c=$total_rounds-$round;

        echo "\t\t<td colspan=\"2\" class='not-empty'><strong> $array1[$c] </strong></td>\n";

    }

    echo "\t</tr>\n";

    for ($row = 1; $row <= $max_rows; $row++) {

        echo "\t<tr>\n";

        for ($round = 1; $round <= $total_rounds; $round++) {
            $score_size = pow(2, $round)-1;
            if (is_player($round, $row, $team_array[$round])) {
                $unpaired_array[$round] = !$unpaired_array[$round];
                if (count($Equipes[$round-1])){
                    $equipe_nom= array_shift($Equipes[$round-1]);
                    $sc= array_shift($Score[$round-1]);
                }
                else if($round>1 && count($Gagnats[$round-2])){
                    $equipe_nom= array_shift($Gagnats[$round-2]);
                    $sc= "---";
                }
                else{
                    $equipe_nom= "Equipe";
                    $sc= "---";
                }
                echo "\t\t<td class=\"active\" colspan=\"2\" class='not-empty'><span class='equipe-span'>$equipe_nom</span><span class='score-span'>$sc</span> </td>\n";
                $team_array[$round]++;
                $score_array[$round] = False;
            } else {
                if ($unpaired_array[$round] && $round != $total_rounds) {
                    if (!$score_array[$round]) {
                        echo "\t\t<td colspan=\"2\" rowspan=\"$score_size\" class='masq not-empty'></td>\n";

                        $score_array[$round] = !$score_array[$round];
                    }
                } else {
                    echo "\t\t<td colspan=\"2\">&nbsp;</td>\n";
                }
            }

        }

        echo "\t</tr>\n";

    }

    echo "</table>\n";

?>
</body>
</html>
