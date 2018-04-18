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


	/*1.2!userConnection
		return :
			true for connection OK
			false for fail
		$db -> 				database object
		$email -> 			field value : email
		$password -> 		field value : password
	*/
	function userConnection(PDO $db, $email, $password){
		if(!empty($email) && !empty($password)){
			//Requête SQL
			$sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

			$req = $db->prepare($sql);
			$req->execute(array(
				'email' => $email
			));

			$result = $req->fetchAll(PDO::FETCH_ASSOC);

			//Si le fetch réussi, alors un résultat a été retourné donc le couple email / password est correct
			if(count($result) == 1 and password_verify($password,$result[0]['password']) === true){
				
				//on définit la SESSION
				$_SESSION['id'] = $result[0]['id'];
				$_SESSION['username'] = $result[0]['username'];
				$_SESSION['email'] = $result[0]['email'];
				$_SESSION['created_at'] = $result[0]['created_at'];
				$_SESSION['image'] = $result[0]['picture'];

				return true;
			}else{
				return false;
			}
		}else{

			return false;
		}
	}

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

	/*1.6!updateMusic
		return :
			true if music updated
			false if not updated
		$db -> 			database object
		$music_id -> 	music id
		$title -> 		field value : title
		$user_id -> 	current user id
	*/
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

	/*1.8!isEmailAvailable*/
	function isEmailAvailable(PDO $db, $email){
		$sql = "SELECT id FROM users WHERE email = :email LIMIT 1";

		$req = $db->prepare($sql);
		$req->execute(array(':email' => $email));

		$result = $req->fetch(PDO::FETCH_ASSOC);

		if(!empty($result)) return false;
		else return true;
	}

	/*1.9!isUsernameAvailable*/
	function isUsernameAvailable(PDO $db, $username){
		$sql = "SELECT id FROM users WHERE username = :username LIMIT 1";

		$req = $db->prepare($sql);
		$req->execute(array(':username' => $username));

		$result = $req->fetch(PDO::FETCH_ASSOC);

		if(!empty($result)) return false;
		else return true;
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

	function verify_mdp(&$mdp){
		if (!empty($mdp)){
			if (strlen($mdp) >= 6){
				return true;
			}else{
				return 'Votre mot de passe doit contenir au moins 6 caractères';
			}
		}else{
			return 'Mot de passe vide';
		}
	}	
	
	function verify_mail(&$mail){
		if (!empty($mail)){
			if (filter_var($mail,FILTER_VALIDATE_EMAIL)){
				return true;
			}else{
				return 'L\'adresse email a une syntaxe incorrecte';
			}
		}else{
			return 'Adresse email vide';
		}
	}

	/*2.1!updateProfilPicture*/
	function updateProfilPicture(PDO $db, $pic, $user_id){
		$sql = "
								UPDATE
									users
								SET
						            picture = :pic
								WHERE
									 id = :id
							";

		$req = $db->prepare($sql);
		$req->execute(array(
			':pic' => $pic,
			':id' => $user_id
		));
	}

	
	
	