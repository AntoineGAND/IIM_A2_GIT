<?php session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);



if(isset($_SESSION) && !empty($_SESSION)){
	header('Location: dashboard.php');
}

else{
	header('Location: login.php');
}