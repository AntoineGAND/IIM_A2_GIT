<?php
class USER{
	public static function add($username = null, $email = null,$email1 = null,$password = null, $password1 = null){
		
		$username = Transform::string($username);
		$email = Transform::string($email);
		$password = Transform::string($password);
		
		if (!is_string($password) or !is_string($password) or !is_string($password)){
			return 'Une erreur inconnue s\'est produite lors de l\'ajout de l\'utilisateur.';
		}
		
		$verify = Verify::username($username);
		if (is_string($verify)){
			return $verify;
		}
		
		$verify = Verify::email($email,$email1);
		if (is_string($verify)){
			return $verify;
		}
		if (count(self::get(['email' => $email])) > 0){
			return 'Votre adresse email n\'est pas disponible.';
		}
		if (count(self::get(['username' => $username])) > 0){
			return 'Votre username n\'est pas disponible.';
		}
		
		$verify = Verify::password($password,$password1);
		if (is_string($verify)){
			return $verify;
		}
		
		$password = Transform::password($password);
		
		BDD::query('insert into `users` (`username`,`email`,`password`) value("'.Transform::mysqlString($username).'","'.Transform::mysqlString($email).'","'.Transform::mysqlString($password).'")');
		return null;
	}
	
	public static function JSON($data = null){
		$return = [];
		
		if (array_key_exists('username',$data)){
			$return['username'] = $data['username'];
		}
		if (array_key_exists('email',$data)){
			$return['email'] = $data['email'];
		}
		if (array_key_exists('id',$data)){
			$return['id'] = $data['id'];
			$return['avatar'] = self::getAvatar($data['id']);
		}
		
		return $return;
	}
	
	public static function setAvatar($tmp = null){
		$image = imagecreatefromstring(file_get_contents($tmp));
		
		$id = SESSION::getUserID();
		
		if (!is_null($id)){
			$url = 'files/avatars/'.$id.'.png';
			
			if (file_exists(Dir::getParent().$url)){
				unlink(Dir::getParent().$url);
			}
			
			Dir::create('/files/avatars/');
			
			imagepng($image,Dir::getParent().$url);
			
			return null;
		}else{
			return 'Vos n\'etes pas connecté';
		}
	}
	
	public static function getAvatar($id){
		
		$url = 'files/avatars/'.$id.'.png';
		
		if (file_exists(Dir::getParent().$url)){
			return $url;
		}
		
		return 'view/profil_pic/undefined.jpg';
	}
	
	public static function login($array = null,$password = null){
		$password = Transform::string($password);
		
		if (!is_string($password) or !is_string($password) or !is_string($password)){
			return 'Une erreur inconnue s\'est produite lors de votre tentative de connexion.';
		}
		$verify = Verify::password($password);
		if (is_string($verify)){
			return $verify;
		}
		
		$user = self::get($array);
		if (count($user) > 0){
			if (password_verify($password,$user['password']) === true){				
				SESSION::setUserID($user['id']);
				
				return null;
			}else{
				return 'Votre mots de passe est faux.';
			}
		}else{
			return 'Le compte correspondant à vos informations données n\'existe pas.';
		}
	}
	
	public static function getList(){
		$return = [];
		
		$req = BDD::query('select * from `users` limit 30',true);

		$i = 0;
		$max = count($req);
		while($i < $max){
			$return[] = [
				'avatar' =>  self::getAvatar($req[$i]['id']),
				'username' =>  $req[$i]['username'],
				'email' =>  $req[$i]['email'],
				'id' =>  $req[$i]['id'],
			];
			
			$i++;
		}
		
		return $return;
	}
}