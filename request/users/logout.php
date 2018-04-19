<?php
	spl_autoload_register(function($class){
		require_once '../../class/'.$class.'.php';
	});
	
	SESSION::removeUserID();
	
	SESSION::setSucces('Vous êtes désormais déconnecté.');
	
	header('Location: ../../login.php');