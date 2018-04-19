<?php

	spl_autoload_register(function($class){
		require_once 'class/'.$class.'.php';
	});


	include 'view/user-login.php';