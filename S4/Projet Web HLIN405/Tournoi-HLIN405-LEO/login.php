<?php
include('connexion.php');

$login = $_POST['login'];
$mdp = $_POST['mdp'];

$sql_count = $bdd->query("SELECT COUNT(*) FROM `Gestionnaires` WHERE login='$login' and mdp='$mdp'");
$donnee = $sql_count->fetch();

	if($donnee['COUNT(*)']==1){
		session_start ();
		$_SESSION['login'] = $_POST['login'];
		$_SESSION['mdp'] = $_POST['mdp'];
		header ('location: indexlogin.php');
	}

	else {
		header ('location: index.php');
	}

?>
