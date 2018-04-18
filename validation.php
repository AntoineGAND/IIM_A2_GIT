<?php
require('config/config.php');
require('model/functions.fn.php');
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if(Verify::post([
	'username' => 'string',
	'email' => 'string',
	'email1' => 'string',
	'password' => 'string',
	'password1' => 'string',
	])) {
		
	$error = USER::add($_POST['username'],$_POST['email'],$_POST['email1'],$_POST['password'],$_POST['password1']);

	if (!is_string($error)){
		userConnection($db, $email, $password);
		header('Location: dashboard.php');
	}else{
		$_SESSION['message'] = $error;
		header('Location: register.php');
	}
	
}else{
    $_SESSION['message'] = 'Une erreur inconnue s\'est produite.';
    header('Location: register.php');
}