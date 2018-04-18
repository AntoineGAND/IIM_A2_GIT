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



	/*1.3!listMusics
		return :
			array of results
		$db -> 				database object
	*/
	function listMusics(PDO $db){
		$sql = "SELECT musics.*, users.id AS user_id, users.username, users.picture FROM musics LEFT JOIN users ON musics.user_id = users.id ORDER BY musics.created_at DESC";

		$req = $db->prepare($sql);
		$req->execute();

		$results = $req->fetchAll(PDO::FETCH_ASSOC);

		return $results;
	}

	/*1.4!selectMusic
		return :
			array
		$db -> 				database object
		$music_id -> 		value : music_id
	*/
	function selectMusic(PDO $db, $music_id){
		$sql = "SELECT * FROM musics WHERE id = :id";

		$req = $db->prepare($sql);
		$req->execute(array(
			':id' => $music_id
		));

		$result = $req->fetch(PDO::FETCH_ASSOC);

		return $result;
	}

	/*1.5!addMusic
		return :
			true if music added
		$db -> 			database object
		$user_id -> 	current user_id
		$title -> 		field value : title
		$file -> 		file object : music
	*/
	function addMusic(PDO $db, $user_id, $title, $file){
		$sql = "
			INSERT INTO
				musics
			SET
				user_id = :user_id,
				title = :title,
				file = :file
		";

		$req = $db->prepare($sql);
		$req->execute(array(
			':user_id' => $user_id,
			':title' => $title,
			':file' => $file,
		));

		return true;
	}

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

	/*1.7!deleteMusic
		return :
			true if music deleted
			false if not deleted
		$db -> 			database object
		$user_id -> 	current user_id
		$title -> 		field value : title
		$music_id -> 	music id
	*/
	function deleteMusic(PDO $db, $music_id, $user_id){
		$music_owner = isMusicOwner($db, $music_id, $user_id);

		if($music_owner == true){
			$sql = "DELETE FROM musics WHERE id = :id";

			$req = $db->prepare($sql);
			$req->execute(array(':id' => $music_id));

			return true;
		}
		else{
			return false;
		}
	}


	/*2.0!isMusicOwner*/
	function isMusicOwner(PDO $db, $music_id, $user_id){
		$sql = "
			SELECT
				COUNT(id) AS is_owner
			FROM
				musics
			WHERE
				id = :music_id
			AND
				user_id = :user_id
		";

		$req = $db->prepare($sql);
		$req->execute(array(
			':music_id' => $music_id,
			':user_id' => $user_id
		));

		$result = $req->fetch();

		if($result['is_owner'] > 0){
			return true;
		}
		else{
			return false;
		}
	}

	
	
	