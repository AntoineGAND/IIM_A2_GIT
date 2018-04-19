<?php

	spl_autoload_register(function($class){
		require_once 'class/'.$class.'.php';
	});

	$music = [];
	if (Verify::get(['id' => 'int'])){
		$music = MUSIC::get($_GET['id']);
	}
	if (empty($music)){
		header("HTTP/1.0 404 Not Found");
	}else{
		include 'view/music-view.php';
	}