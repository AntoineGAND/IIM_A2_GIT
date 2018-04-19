<?php
	require_once 'model/functions.fn.php';

	$data = [];
	$error = [];

	if (Verify::get(['get' => 'string'])){
		$get = explode(',',$_GET['get']);
		
		$o = 0;
		$maxGet = count($get);
		while($o < $maxGet){
			if (trim($get[$o]) == 'users'){
				$array = [];
				
				if (Verify::get(['email' => 'string'])){
					$array['email'] = $_GET['email'];
				}
				if (Verify::get(['id_user' => 'int'])){
					$array['id'] = $_GET['id_user'];
				}
				if (Verify::get(['username' => 'string'])){
					$array['username'] = $_GET['username'];
				}
				
				if (count($array) > 0){
					$data['users'] = User::json(User::get($array));
				}else{
					$data['users'] = [];
					
					$users = User::getList();
					
					$i=0;
					$max = count($users);
					while($i < $max){
						
						$data['users'][] = User::json($users[$i]);
						
						$i++;
					}
				}
			}elseif (trim($get[$o]) == 'musics'){				
				if (Verify::get(['id_music' => 'int'])){
					$data['musics'] = MUSIC::json(MUSIC::get($_GET['id']));
				}else{
					$data['musics'] = [];
					
					$users = MUSIC::getList();
					
					$i=0;
					$max = count($users);
					while($i < $max){
						
						$data['musics'][] = MUSIC::json($users[$i]);
						
						$i++;
					}
				}
			}
			
			$o++;
		}
	}
	
	echo json_encode([
		'data' => $data,
		'error' => $error,
	]);
?>