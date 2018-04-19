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
		
		public static function add_comment($id,$comment){
			$session = SESSION::getUserID();
		
			if (!is_null($session)){
				
				$comment = Transform::string($comment);
				if (!is_string($comment)){
					return 'Une erreur inconnue s\'est produite lors de l\'ajout de votre commentaire.';
				}
				$id = Transform::int($id);
				if (!is_numeric($id)){
					return 'Une erreur inconnue s\'est produite lors de l\'ajout de votre commentaire.';
				}
				
				BDD::query('insert into `musics_comment` (`comment`,`id_user`,`id_music`,`time`) value("'.Transform::mysqlString($comment).'",'.$session.','.$id.','.time().')','insert_id');
				
				return null;
			}else{
				return 'Vos n\'etes pas connecté';
			}
		}
		
		public static function update($id,$title){
			$session = SESSION::getUserID();
		
			if (!is_null($session)){
				
				$id = Transform::int($id);
				if (!is_numeric($id)){
					return 'Une erreur inconnue s\'est produite lors de la modification de votre musique.';
				}
				$title = Transform::string($title);
				if (!is_string($title)){
					return 'Une erreur inconnue s\'est produite lors de la modification de votre musique.';
				}
				
				$music = self::get($id);
				
				if (self::isOwn($music['user']['id'])){
					
					BDD::query('update `musics` set `title`="'.Transform::mysqlString($title).'" where `id`='.$id.' limit 1');
					
					return null;
				}else{
					return 'Vous n\'avez pas les droits pour supprimer cette music';
				}
			}else{
				return 'Vos n\'etes pas connecté';
			}
		}
		
		public static function remove($id){
			$session = SESSION::getUserID();
		
			if (!is_null($session)){
				
				$id = Transform::int($id);
				if (!is_numeric($id)){
					return 'Une erreur inconnue s\'est produite lors de la suppression de votre musique.';
				}
				
				$music = self::get($id);
				
				if (self::isOwn($music['user']['id'])){
					
					$url = Dir::getParent().'files/musics/'.$id.'.mp3';
					if (file_exists($url)){
						unlink($url);
					}
					
					BDD::query('delete from `musics` where `id`='.$id.' limit 1');
					
					return null;
				}else{
					return 'Vous n\'avez pas les droits pour supprimer cette music';
				}
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
		
		public static function like($id){
			$session = SESSION::getUserID();
		
			if (!is_null($session)){
				
				$id = Transform::int($id);
				if (!is_numeric($id)){
					return 'Une erreur inconnue s\'est produite.';
				}
				
				if (self::isLiked($id) === true){
					BDD::query('delete from `musics_like` where `id_user`='.$session.' and `id_music`='.$id);
				}else{
					BDD::query('insert into `musics_like` (`id_user`,`id_music`,`time`) value('.$session.','.$id.','.time().')');
				}
				
				return null;
			}else{
				return 'Vos n\'etes pas connecté';
			}
		}
		
		public static function isLiked($id){
			
			if (is_numeric($id)){
				
				$session = SESSION::getUserID();
				if (!is_null($session)){
					$like = BDD::query('select `id_music` from `musics_like` where `id_user`='.$session.' and `id_music`='.$id.' limit 1',true);
					
					return (count($like) > 0);
				}else{
					return false;
				}
			}else{
				$return = [];
				
				$max = count($id);
				
				if ($max > 0){
					$i = 0;

					while($i < $max){
						$return[$id[$i]] = false;
						$i++;
					}
					
					$session = SESSION::getUserID();
					if (!is_null($session)){
						
						$like = BDD::query('select `id_music` from `musics_like` where `id_user`='.$session.' and '.BDD::getOperation('id_music','=',$id),true);
						
						$i = 0;
						$max = count($like);
						while($i < $max){
							$return[$like[$i]['id_music']] = true;
							$i++;
						}
					}
				}
				
				return $return;
			}
		}
		
		public static function get_likes($id){
			if (is_numeric($id)){
				$req = BDD::query('select `musics_like`.`id_user`,`musics_like`.`time`,`musics_like`.`id_user`,`users`.`username`,`users`.`email` from `musics_like` left join `users` on `users`.`id`=`musics_like`.`id_user` where `id_music`='.$id.' order by `time` desc limit 30',true);
				$return = [];
				
				$i = 0;
				$max = count($req);
				while($i < $max){
					$return[] = [
						'time' => $req[$i]['time'],
						'user' => [
							'username' => $req[$i]['username'],
							'email' => $req[$i]['email'],
							'id' => $req[$i]['id_user'],
						],
					];
					$i++;
				}
				
				return $return;
			}else{
				$return = [];
				
				$i = 0;
				$max = count($id);
				
				if (count($max) > 0){
					$i = 0;
					while($i < $max){
						$return[$id[$i]] = [];
						$i++;
					}
					
					$req = BDD::query('select `musics_comment`.`id_music`,`musics_comment`.`id_user`,`musics_comment`.`comment`,`musics_comment`.`time`,`users`.`id` as `id_user`,`users`.`username`,`users`.`email` from `musics_comment` left join `users` on `users`.`id`=`musics_comment`.`id_user` where '.BDD::getOperation('id_music','=',$id).' order by `time` desc limit 30',true);
					
					$i = 0;
					$max = count($req);
					while($i < $max){
						$return[$req[$i]['id_music']][] = [
							'time' => $req[$i]['time'],
							'comment' => $req[$i]['comment'],
							'user' => [
								'username' => $req[$i]['username'],
								'email' => $req[$i]['email'],
								'id' => $req[$i]['id_user'],
							],
						];
						$i++;
					}
					
				}
				
				return $return;
			}
		}
		
		public static function get_comment($id){
			
			if (is_numeric($id)){
				$req = BDD::query('select `musics_comment`.`id_user`,`musics_comment`.`comment`,`musics_comment`.`time`,`users`.`id` as `id_user`,`users`.`username`,`users`.`email` from `musics_comment` left join `users` on `users`.`id`=`musics_comment`.`id_user` where `id_music`='.$id.' order by `time` desc limit 30',true);
				$return = [];
				
				$i = 0;
				$max = count($req);
				while($i < $max){
					$return[] = [
						'time' => $req[$i]['time'],
						'comment' => $req[$i]['comment'],
						'user' => [
							'username' => $req[$i]['username'],
							'email' => $req[$i]['email'],
							'id' => $req[$i]['id_user'],
						],
					];
					$i++;
				}
				
				return $return;
			}else{
				$return = [];
				
				$i = 0;
				$max = count($id);
				
				if (count($max) > 0){
					$i = 0;
					while($i < $max){
						$return[$id[$i]] = [];
						$i++;
					}
					
					$req = BDD::query('select `musics_comment`.`id_music`,`musics_comment`.`id_user`,`musics_comment`.`comment`,`musics_comment`.`time`,`users`.`id` as `id_user`,`users`.`username`,`users`.`email` from `musics_comment` left join `users` on `users`.`id`=`musics_comment`.`id_user` where '.BDD::getOperation('id_music','=',$id).' order by `time` desc limit 30',true);
					
					$i = 0;
					$max = count($req);
					while($i < $max){
						$return[$req[$i]['id_music']][] = [
							'time' => $req[$i]['time'],
							'comment' => $req[$i]['comment'],
							'user' => [
								'username' => $req[$i]['username'],
								'email' => $req[$i]['email'],
								'id' => $req[$i]['id_user'],
							],
						];
						$i++;
					}
					
				}
				
				return $return;
			}
		}
		
		public static function get($id){
			$req = BDD::query('SELECT count(musics_like.id_user) as `nbr-likes`,count(musics_comment.id_user) as `nbr-comment`,musics.*, users.id AS user_id, users.username FROM musics LEFT JOIN users ON musics.user_id = users.id left join `musics_comment` on `musics_comment`.`id_music`=`musics`.`id` left join `musics_like` on `musics_like`.`id_music`=`musics`.`id` where `musics`.`id`='.$id.' ORDER BY musics.created_at DESC limit 1',true);
			
			if (count($req) > 0){
				return [
					'user' => [
						'username' => $req[0]['username'],
						'id' => $req[0]['user_id'],
						'avatar' => USER::getAvatar($req[0]['user_id']),
					],
					'file' => MUSIC::getFile($req[0]['id']),
					'id' => $req[0]['id'],
					'comments' => self::get_comment($req[0]['id']),
					'nbr-likes' => $req[0]['nbr-likes'],
					'nbr-comments' => $req[0]['nbr-comment'],
					'liked' => self::isLiked($req[0]['id']),
					'title' => $req[0]['title'],
					'created_at' => $req[0]['created_at'],
				];
			}else{
				return [];
			}
		}
		
		public static function getList(){
			$req = BDD::query('SELECT count(musics_like.id_user) as `nbr-likes`,count(musics_comment.id_user) as `nbr-comment`,musics.*, users.id AS user_id, users.username FROM musics LEFT JOIN users ON musics.user_id = users.id left join `musics_comment` on `musics_comment`.`id_music`=`musics`.`id` left join `musics_like` on `musics_like`.`id_music`=`musics`.`id` ORDER BY musics.created_at DESC limit 30',true);
		
			$ids = [];
			
			$i = 0;
			$max = count($req);
			
			while($i < $max){
				$ids[] = $req[$i]['id'];
				$i++;
			}
			
			$comments = self::get_comment($ids);
			$likes = self::get_likes($ids);
			$return = [];
			$i = 0;
			while($i < $max){
				$return[$i] = [
					'user' => [
						'username' => $req[$i]['username'],
						'id' => $req[$i]['user_id'],
						'avatar' => USER::getAvatar($req[$i]['user_id']),
					],
					'file' => MUSIC::getFile($req[$i]['id']),
					'id' => $req[$i]['id'],
					'nbr-likes' => $req[$i]['nbr-likes'],
					'nbr-comments' => $req[$i]['nbr-comment'],
					'comments' => $comments[$req[$i]['id']],
					'likes' => $likes[$req[$i]['id']],
					'title' => $req[$i]['title'],
					'created_at' => $req[$i]['created_at'],
				];
				
				$i++;
			}
		
			return $return;
		}
		
		public static function dashboard(){
			$req = BDD::query('SELECT count(musics_like.id_user) as `nbr-likes`,count(musics_comment.id_user) as `nbr-comment`,musics.*, users.id AS user_id, users.username FROM musics LEFT JOIN users ON musics.user_id = users.id left join `musics_comment` on `musics_comment`.`id_music`=`musics`.`id` left join `musics_like` on `musics_like`.`id_music`=`musics`.`id` ORDER BY musics.created_at DESC limit 30',true);
		
			$ids = [];
			
			$i = 0;
			$max = count($req);
			
			while($i < $max){
				$ids[] = $req[$i]['id'];
				$i++;
			}
			
			$liked = self::isLiked($ids);

			$return = [];
			$i = 0;
			while($i < $max){
				$return[$i] = [
					'user' => [
						'username' => $req[$i]['username'],
						'id' => $req[$i]['user_id'],
						'avatar' => USER::getAvatar($req[$i]['user_id']),
					],
					'file' => MUSIC::getFile($req[$i]['id']),
					'id' => $req[$i]['id'],
					'liked' => $liked[$req[$i]['id']],
					'nbr-likes' => $req[$i]['nbr-likes'],
					'nbr-comments' => $req[$i]['nbr-comment'],
					'title' => $req[$i]['title'],
					'created_at' => $req[$i]['created_at'],
				];
				
				$i++;
			}
		
			return $return;
		}
		
		public static function json($array){
			return [
				'file' => $array['file'],
				'user' => $array['user'],
				'id' => $array['id'],
				'title' => $array['title'],
				'created_at' => $array['created_at'],
				'comments' => [
					'count' => $array['nbr-comments'],
					'data' => $array['comments'],
				],
				'likes' => [
					'count' => $array['nbr-likes'],	
				]
			];
		}
	}