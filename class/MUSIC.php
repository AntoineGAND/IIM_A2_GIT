<?php
	class MUSIC{
		public static function add($tmp,$name){
			$id = SESSION::getUserID();
		
			if (!is_null($id)){
				
				$name = Transform::string($name);
				if (!is_string($name)){
					return 'Une erreur inconnue s\'est produite lors de l\'ajout de votre musique.';
				}
				
				$id = BDD::query('insert into `musics` (`title`,`user_id`) value("'.Transform::mysqlString($name).'",'.$id.')','insert_id');
				
				Dir::create('/files/musics/');
				
				move_uploaded_file($tmp,Dir::getParent().'files/musics/'.$id.'.mp3');
				
				return null;
			}else{
				return 'Vos n\'etes pas connecté';
			}
		}
		
		public static function isOwn($musicID){
			$id = SESSION::getUserID();
		
			if (!is_null($id)){
				return $id == $musicID;
			}else{
				return false;
			}
		}
		
		public static function getFile($id){
			
			$url = 'files/musics/'.$id.'.mp3';
			
			if (file_exists(Dir::getParent().$url)){
				return $url;
			}
			
			return null;
		}
		
		public static function getList(){
			$req = BDD::query('SELECT musics.*, users.id AS user_id, users.username FROM musics LEFT JOIN users ON musics.user_id = users.id ORDER BY musics.created_at DESC limit 30',true);
		
			$return = [];
			
			$i = 0;
			$max = count($req);
			
			while($i < $max){
				$return[$i] = [
					'user' => [
						'username' => $req[$i]['username'],
						'id' => $req[$i]['user_id'],
						'avatar' => USER::getAvatar($req[$i]['user_id']),
					],
					'file' => MUSIC::getFile($req[$i]['id']),
					'id' => MUSIC::getFile($req[$i]['id']),
					'title' => $req[$i]['title'],
					'created_at' => $req[$i]['created_at'],
				];
				
				$i++;
			}
		
			return $return;
		}
	}