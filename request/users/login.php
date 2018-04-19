<?php
	spl_autoload_register(function($class){
		require_once '../../class/'.$class.'.php';
	});

	if(Verify::post(['email' => 'string','password'=> 'string'])){
		$error = User::login(['email' => $_POST['email']],$_POST['password']);
		if(!is_string($error)){
			SESSION::setSucces('Vous êtes désormais connecté.');
			header('Location: ../../dashboard.php');
		}else{
			SESSION::setError($error);
			header('Location: ../../login.php');
		}
	}else{
		SESSION::setError('Une erreur inconnu s\'est produite lors de la suppression de votre musique.');
		header('Location: ../../login.php');
	}