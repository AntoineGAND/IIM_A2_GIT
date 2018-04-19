<?php

	spl_autoload_register(function($class){
		require_once '../../../class/'.$class.'.php';
	});

	if(Verify::files(['image' => ''])){
		$error = User::setAvatar($_FILES['image']['tmp_name']);
		
		if (!is_string($error)){
			SESSION::setSucces('Votre avatar a bien été modifié');
			header('Location: ../../../dashboard.php');
		}else{
			SESSION::setError($error);
			header('Location: ../../../user-edit.php');
		}
	}else{
		SESSION::setError('Une inconnue s\'est produite lors de la modification de votre avatar');
		header('Location: ../../../user-edit.php');
	}
