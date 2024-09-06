<?php
ini_set('display_errors',1);
      error_reporting(E_ALL);

    $dsn= "mysql:host=localhost;dbname=projet;charset=utf8";
    $username= "UTILISATEUR";
    $password = "MOTDEPASSE";
    $dbh=null;

    try{
        $dbh = new PDO($dsn,$username,$password);
        
    }
    catch(PDOException $e){
       die('ERROR: '.$e->getMessage());
    }
    
