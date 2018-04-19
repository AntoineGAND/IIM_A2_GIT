<?php 

spl_autoload_register(function($class){
		require_once '../../class/'.$class.'.php';
	});

if(Verify::post([
	'username' => 'string',
	'email' => 'string',
	'email1' => 'string',
	'password' => 'string',
	'password1' => 'string',
	])) {
		
	$error = USER::add($_POST['username'],$_POST['email'],$_POST['email1'],$_POST['password'],$_POST['password1']);

	if (!is_string($error)){
		SESSION::setSucces('Vous êtes désormais inscris');
		header('Location: ../../dashboard.php');
	}else{
		SESSION::setError($error);
		header('Location: ../../register.php');
	}
	
}else{
    SESSION::setError('Une erreur inconnue s\'est produite.');
	header('Location: ../../register.php');
}

