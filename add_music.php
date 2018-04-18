<?php
session_start();
require('config/config.php');
require('model/functions.fn.php');

if( Verify::post(['title' => 'string']) and Verify::files(['music' => ''])){

	$ext = strtolower(substr(strrchr($_FILES['music']['name'], '.')  ,1));
<<<<<<< HEAD

=======
var_dump($_FILES);
>>>>>>> a56edd31940ecd1d1c0d6b8988acf7a56d90552c
	if (preg_match('/\.(mp3|ogg)$/i', $_FILES['music']['name'])) {
		$verify = MUSIC::add($_FILES['music']['tmp_name'],$_POST['title']);
		if (is_string($verify)){
			$error = $verify;
		}
	}else{
		$error = 'Erreur, le fichier n\'a pas une extension autorisée !';
	}
}

include 'view/_header.php';
include 'view/add_music.php';
include 'view/_footer.php';