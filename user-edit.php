<?php

	spl_autoload_register(function($class){
		require_once 'class/'.$class.'.php';
	});

	if(is_null(SESSION::getUserID())){
		header('Location: login.php');
	}else{
		include 'view/user-edit-image.php';
	}