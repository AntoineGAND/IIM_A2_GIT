<?php session_start();

/******************************** 
	 DATABASE & FUNCTIONS 
********************************/

error_reporting(E_ALL);
ini_set('display_errors','true');

require('config/config.php');
require('model/functions.fn.php');


/********************************
			PROCESS
********************************/

if(isset($_POST['email']) && isset($_POST['password'])){
	if(!empty($_POST['email']) && !empty($_POST['password'])){

		// TODO

		// Force user connection to access dashboard
		userConnection($db, $_POST['email'], $_POST['password']);
		
		header('Location: dashboard.php');

	}else{
		$error = 'Champs requis !';
	}
}

/******************************** 
			VIEW 
********************************/
include 'view/_header.php';
include 'view/login.php';
include 'view/_footer.php';