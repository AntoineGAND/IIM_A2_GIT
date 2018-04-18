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
				echo $tmp.'<br>';
				die(Dir::getParent().'files/musics/'.$id.'.mp3');
				
				return null;
			}else{
				return 'Vos n\'etes pas connecté';
			}
		}
	}