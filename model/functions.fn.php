<?php 
	spl_autoload_register(function($class){
		require_once 'class/'.$class.'.php';
	});

/*******************************************************************
SUMMARY
	1!FUNCTIONS
		1.1!userRegistration
		1.2!userConnection
		1.3!listMusics
		1.4!selectMusic
		1.5!addMusic
		1.6!updateMusic
		1.7!deleteMusic
		1.8!isEmailAvailable
		1.9!isUsernameAvailable
		2.0!isMusicOwner
		2.1!updateProfilPicture	

********************************************************************/

/**************************************************
					1!FUNCTIONS
**************************************************/



	function updateMusic(PDO $db, $music_id, $title, $user_id){
		$music_owner = isMusicOwner($db, $music_id, $user_id);

		if($music_owner == true){
			$sql = "
				UPDATE
					musics
				SET
		            title = :title
				WHERE
					 id = :id
			";

			$req = $db->prepare($sql);
			$req->execute(array(
				':title' => $title,
				':id' => $music_id
			));

			return true;
		}
		else{
			return false;
		}
	}

	
	
	