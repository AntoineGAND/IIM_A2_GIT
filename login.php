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

if(Verify::post(['email' => 'string','password'=> 'string'])){
	$error = User::login(['email' => $_POST['email']],$_POST['password']);
	
	if (!is_string($error)){
		header('Location: dashboard.php');
	}
}

/******************************** 
			VIEW 
********************************/
include 'view/_header.php';
include 'view/login.php';
include 'view/_footer.php';