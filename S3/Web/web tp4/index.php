<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<title>Factorielle</title>
</head>
<body>
<hl>Factorielle<br></hl>

<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

function fact(int $n){
if ($n<=0) return 1;
else return $n*fact($n-1);
}

if (isset($_GET['n']) && preg_match('/^[0-9]+$/' ,$_GET['n'])){
echo "Resultat: {$_GET['n']}! = ",fact((int)$_GET['n'])."<br ./>";} 
else {echo "Saisir un entier !";}
?>

<form method="get">
<input type="number" name="n" size="10" pattern="\d+" required>
<input type="submit" value="Calculer !">
</form>
</body>
</html>