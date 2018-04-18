<?php
class USER{
	public static function add($username = null, $email = null,$email1 = null,$password = null, $password1 = null){
		
		$username = Transform::string($username);
		$email = Transform::string($email);
		$password = Transform::string($password);
		
		if (!is_string($password) or !is_string($password) or !is_string($password)){
			return 'Une erreur inconnue s\'est produite lors de l\'ajout de l\'utilisateur';
		}
		
		$verify = Verify::username($username);
		if (is_string($verify)){
			return $verify;
		}
		
		$verify = Verify::email($email,$email1);
		if (is_string($verify)){
			return $verify;
		}
		if (count(self::getUser(['email' => $email])) > 0){
			return 'Votre adresse email n\'est pas disponible.';
		}
		if (count(self::getUser(['username' => $username])) > 0){
			return 'Votre username n\'est pas disponible.';
		}
		
		$verify = Verify::password($password,$password1);
		if (is_string($verify)){
			return $verify;
		}
		
		$password =  Transform::password($password);
		
		BDD::query('insert into `users` (`username`,`email`,`pasword`) value("'.Transform::mysqlString($username).'","'.Transform::mysqlString($email).'","'.Transform::mysqlString($password).'")');
	}
	
	
	public static function getUser($array){
		$where = [];
		
		if (array_key_exists('id',$array)){
			$where[] = '`id`='.$array['id'];
		}
		if (array_key_exists('email',$array)){
			$where[] = '`email`='.$array['email'];
		}
		if (array_key_exists('username',$array)){
			$where[] = '`username`="'.$array['username'];
		}
		
		if (count($where) > 0){
			$req = BDD::query('select from `users` where '.join(' and ',$where).' limit 1',true);
			
			return $req;
		}else{
			return [];
		}
	}
}