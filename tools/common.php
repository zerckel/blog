<?php

//fichier de fonctionnalités communes à plusieurs scripts

//paramétrage de la langue de traduction pour PHP
setlocale(LC_ALL, "fr_FR");

//connexion à la base de données
function connect() {
	try{
		$db = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch (Exception $exception)
	{
		die( 'Erreur : ' . $exception->getMessage() );
	}
	return $db;
}

function isAdmin(){
	if(!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0){
		header('location:../blog-addItems-loginRegister/index.php');
		exit;
	}
}




//ouverture de session pour connexions utilisateurs et admins
session_start();

